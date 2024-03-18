<?php

namespace App\Console\Commands;

use App\Models\Audit\Justify;
use App\Models\Process\Notice;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\User\Contact;
use App\Models\Security\Audit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Process\SubscriptionFreeze;
use Exception;

class DataObfuscate extends Command
{

    /**
     * 
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:obfuscate-db {adminpass=ifc789123} {--preserve-user-data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obfuscate DB Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {        
        if (env('APP_ENV','production') === 'production') {
            throw new Exception('Não use isso em produção');
            return 0;
        }

        $this->obfuscateParameters();
                
        SubscriptionFreeze::where('id', '>', 0)->delete();

        DB::table('failed_jobs')
            ->delete();

        DB::table('password_resets')
            ->delete();

        Justify::where('id','>',0)
            ->delete();

        Audit::where('id', '>', 0)->delete();

        Contact::where('id', '>', 0)->update(
            [
                'street' => Str::random(15),
                'number'    => random_int(2, 1000),
                'district' => Str::random(5),
                'zip_code' => random_int(10000, 99999) . '-000',
                'phone_number' => '(00)0000-0000',
                'alternative_phone_number' => '(00)90000-0000',
                'has_whatsapp' => random_int(0, 1),
                'has_telegram' => random_int(0, 1),
                'complement' => Str::random(20)
            ]
        );        

        $preserveUserData = $this->option('preserve-user-data');

        // A senha do usuário padrão é ifc789123        
        if ($preserveUserData) {
            DB::update("update core.users set email = concat(id,'@','ifc.edu.br'), rg = id,rg_emmitter = 'SSP', 
            social_name = concat('social do ',id), mother_name = concat('mãe do ', id), two_factor_secret = null, two_factor_recovery_codes = null,            
            \"password\" = '$2y$10\$OCMEJe28z8bhWMJd44bu8ejpWidqBUsfFHhUezgL96POoRGzy945O', remember_token = null where id > 0;");    
        } else {
            DB::update("update core.users set  cpf = id, \"name\" = concat('nome do ',id), email = concat(id,'@','ifc.edu.br'), rg = id,rg_emmitter = 'SSP',             
            social_name = concat('social do ',id), mother_name = concat('mãe do ', id), two_factor_secret = null, two_factor_recovery_codes = null,
            \"password\" = '$2y$10\$OCMEJe28z8bhWMJd44bu8ejpWidqBUsfFHhUezgL96POoRGzy945O', remember_token = null where id > 0;");    

            DB::update(
                "update core.subscriptions set elimination = null, special_need_description = null, 
                preliminary_classification_recourse = null,
                additional_test_time_analysis = null, exam_resource_description = null, exam_resource_analysis = null where id > 0"
            );
        }
        
        $notices = Notice::where('enrollment_process_enable',true)->get();
        foreach($notices as $notice) {
            $docsTable =  $notice->getEnrollmentProcessDocumentsTableName();
            try {
                DB::update("update $docsTable set url = concat('http://localhost:8001/test.pdf'), path='test.pdf'");
            } catch (Exception $exception) {

            }            
        }

        $users = User::whereHas('permissions', function ($q) {
            $q->where('role_id', 1);
        })
            ->get();
        $i = 0;
        foreach ($users as $user) {
            if (!$preserveUserData) {
                $user->name = Str::random(13);
                $user->cpf = "000.000.000-$i$i";
                $user->birth_date = now()->addYear(-80);
            }                        
            $user->password = Hash::make($this->argument('adminpass'));
            $user->is_foreign = false;
            $user->saveQuietly();            
            $i = $i + 1;
        }        
        return 0;
    }

    private function obfuscateParameters()
    {
        DB::table('parameters')->updateOrInsert(
            ['name'  => "pagtesouro_token"],
            [
                'value' => "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIxNTgxMjUifQ.gHyN_dV5d9taZhjNO30k1oNHGNdD3Crqpvru0mrwYLUKp6DWE87wssJov_nGsCSZsEG7CNpL4ZmR9Dru56Yngg3VsH9Hv2Kpsw0Vdc_9NWXEjPTQcA6Edbbxq2F4SyeF4vLIS03LkrvT7NGJxm8MkSUbKUTpdMvbIfkHCcpqMPUMbvqiUuawq6Kx5viBlnmSN1T3IDplNGQ50Zzx8GfUBO6qbICa0CLQsiBs_ccRn8bwVxHUuvScxQQyJWLt2X6Ccu4KZFiazVDPEPzaZssLUZSDd3BwnwIczYG0ajlLiI76ypUYurF69Fo24le2HJJWWKSQzX5sITK2VKHVxixRMw"
            ]
        );
        DB::table('parameters')->updateOrInsert(
            [
                'name'  => 'pagtesouro_cod_servico',
            ],
            ['value' => '1561'],
        );
        DB::table('parameters')->updateOrInsert(
            [
                'name'  => 'pagtesouro_url_solicitacao_pagamento'
            ],
            [
                'value' => 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento'
            ]
        );
        DB::table('parameters')->updateOrInsert(
            [
                'name'  => 'pagtesouro_url_consulta_pagamento'
            ],
            [
                'value' => 'https://valpagtesouro.tesouro.gov.br/api/gru/pagamentos'
            ]
        );
    }

}
