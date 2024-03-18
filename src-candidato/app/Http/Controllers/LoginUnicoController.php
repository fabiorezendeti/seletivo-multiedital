<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\UserValidationRules;
use App\Models\User;
use Carbon\Traits\Date;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use \Exception;
use Illuminate\Support\Facades\Validator;

class LoginUnicoController extends Controller
{
    use PasswordValidationRules;
    use UserValidationRules;

    protected $json_output_jwk;
    protected $TOKEN_JSON;
    protected $ACCESS_TOKEN_JSON;
    protected $ID_TOKEN_JSON;


    /*
     * Redireciona para o Login Unico
     */
    public function redirect()
    {
        if (env('LOGIN_UNICO_ENABLE')) {
            $url = env('LOGIN_UNICO_PROVIDER') . "/authorize?response_type=code"
                . "&client_id=" . env('LOGIN_UNICO_CLIENT_ID')
                . "&scope=" . env('LOGIN_UNICO_SCOPE')
                . "&redirect_uri=" . urlencode(env('LOGIN_UNICO_REDIRECT_URL'))
                . "&nonce=" . $this->getRandomHex()
                . "&state=" . $this->getRandomHex();
            return new RedirectResponse($url);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Recebe o retorno do Login Único
     * @return RedirectResponse
     * @throws Exception
     */
    public function callback()
    {
        if (env('LOGIN_UNICO_ENABLE') && Auth::guest()) {
            if (!session()->exists('login-unico') or session('login-unico') == null) {
                $this->setToken();
            } else {
                $this->TOKEN_JSON = session('login-unico');
                $this->json_output_jwk = session('json_output_jwk');
            }
            try {
                $this->setAccessToken($this->TOKEN_JSON['access_token']);
                $this->setIdTokenJson($this->TOKEN_JSON['id_token'], $this->json_output_jwk);
            } catch (Exception $e) {
                session()->flush();
                return redirect()->route('login_url');
            }

            // Busca um usuário com o CPF recebido do login único
            $user = User::where('cpf', $this->maskCPF($this->ID_TOKEN_JSON->sub,'###.###.###-##'))->first();

            // Se o usuário não estiver cadastrado encaminha para a tela de cadastro
            if (empty($user)) {
                session([
                    'nome' => $this->ID_TOKEN_JSON->name,
                    'email' => $this->ID_TOKEN_JSON->email,
                    'cpf' => $this->ID_TOKEN_JSON->sub,
                    'telefone' => $this->ID_TOKEN_JSON->phone_number,
                    'login-unico' => $this->TOKEN_JSON,
                    'json_output_jwk' => $this->json_output_jwk
                ]);
                return redirect()->route('register');
            } else if($user->email != $this->ID_TOKEN_JSON->email){
                //se o usuário já era cadastrado no sistema mas o email não é o mesmo do login único
                Auth::login($user);
                session([
                    'email' => $this->ID_TOKEN_JSON->email
                ]);
                return redirect()->route('profile.show');
            }
            Auth::login($user);
            session([
                'email' => $this->ID_TOKEN_JSON->email
            ]);
            return redirect()->route('dashboard');
        }
        return redirect()->route('welcome');
    }

    function maskCPF($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    /*
     * Cria o novo usuário do sistema
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), $this->getUserRegistrationValidationRules(),[],$this->getBasicMessageWithContact())->validate();
        DB::beginTransaction();
        try {
            $User = User::create([
                'name' => $request->name,
                'email' => session('email'),
                'password' => Hash::make($request->pin),
                'cpf' => $this->maskCPF(session('cpf'),'###.###.###-##'),
                'rg' => $request->rg,
                'rg_emmitter' => $request->rg_emmitter,
                'social_name' => $request->social_name,
                'mother_name' => $request->mother_name,
                'birth_date' => Carbon::createFromFormat('d/m/Y', $request->birth_date),
                'nationality' =>$request->nationality,
                'is_foreign' => $request->is_foreign
            ]);
            $User->contact()->create([
                'street' => $request->street,
                'number' => $request->number,
                'district' => $request->district,
                'zip_code' => $request->zip_code,
                'city_id' => $request->city,
                'phone_number' => $request->phone_number,
                'alternative_phone_number' => $request->alternative_phone_number,
                'has_whatsapp' => $request->has_whatsapp,
                'has_telegram' => $request->has_telegram,
                'complement' => $request->complement
            ]);
            DB::commit();
            Auth::login($User);
            return redirect()->route('dashboard');
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all());
        }
    }

    private function setToken()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode(env('LOGIN_UNICO_CLIENT_ID') . ":" . env('LOGIN_UNICO_SECRET'))
            ])->asForm()->post(env('LOGIN_UNICO_PROVIDER') . '/token', [
                'grant_type' => urlencode('authorization_code'),
                'code' => urlencode($_GET['code']),
                'redirect_uri' => env('LOGIN_UNICO_REDIRECT_URL')
            ]);
            $this->TOKEN_JSON = $response->json();

        } catch (Exception $e) {
            throw new Exception('Não foi possível recuperar o token de acesso: ' . $e->getMessage());
        }
    }

//    public function sendToLogout(){
//        $url = env('LOGIN_UNICO_PROVIDER') . "/logout?post_logout_redirect_uri=" . env('APP_URL') . "/logout";
//        return new RedirectResponse($url);
//    }

    public function logout(Request $request){
        if($request->method() == 'POST'){
            $url = env('LOGIN_UNICO_PROVIDER') . "/logout?post_logout_redirect_uri=" . env('APP_URL') . "/logout";
            return new RedirectResponse($url);
        }
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flush();
        return redirect()->route('welcome');
    }

    private function setAccessToken($access_token)
    {
        try {
            $this->json_output_jwk = !is_null($this->json_output_jwk) ? $this->json_output_jwk : Http::get(env('LOGIN_UNICO_PROVIDER') . '/jwk')->json();
            $this->ACCESS_TOKEN_JSON = $this->processToClaims($access_token, $this->json_output_jwk);
        } catch (Exception $e) {
            throw new Exception('Não foi possível validar o token de acesso: ' . $e->getMessage());
        }
    }

    private function setIdTokenJson($id_token, $json_output_jwk)
    {
        try {
            $this->ID_TOKEN_JSON = $this->processToClaims($id_token, $json_output_jwk);
        } catch (Exception $e) {
            throw new Exception('Não foi possível recuperar dados do usuário: ' . $e->getMessage());
        }
    }

    private function getRandomHex($num_bytes = 4)
    {
        return bin2hex(openssl_random_pseudo_bytes($num_bytes));
    }

    private function processToClaims($token, $jwk)
    {
        $modulus = JWT::urlsafeB64Decode($jwk['keys'][0]['n']);
        $publicExponent = JWT::urlsafeB64Decode($jwk['keys'][0]['e']);
        $components = array(
            'modulus' => pack('Ca*a*', 2, $this->encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack('Ca*a*', 2, $this->encodeLength(strlen($publicExponent)), $publicExponent)
        );

        $RSAPublicKey = pack(
            'Ca*a*a*',
            48,
            $this->encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
        );
        $rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
        $RSAPublicKey = chr(0) . $RSAPublicKey;
        $RSAPublicKey = chr(3) . $this->encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;
        $RSAPublicKey = pack(
            'Ca*a*',
            48,
            $this->encodeLength(strlen($rsaOID . $RSAPublicKey)),
            $rsaOID . $RSAPublicKey
        );
        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split(base64_encode($RSAPublicKey), 64) . '-----END PUBLIC KEY-----';

        JWT::$leeway = 3 * 60; //em segundos

        $decoded = JWT::decode($token, new Key($RSAPublicKey, 'RS256'));

        return $decoded;
    }

    private function encodeLength($length)
    {
        if ($length <= 0x7F) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }
}
