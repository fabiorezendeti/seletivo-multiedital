<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Audit\JustifyUpdate;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;


    protected static function booted()
    {
        static::creating(function($user){
            $user->uuid = Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'rg',
        'rg_emmitter',
        'social_name',
        'mother_name',
        'birth_date',
        'nationality',
        'is_foreign',
        'sex',
        'rg_issue_date',
        'social_identification_number'
    ];

    protected  $dates = [
        'birth_date',
        'rg_issue_date',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date'        => 'date:d/m/Y',
        'rg_issue_date'     => 'date:Y-m-d',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url','email_confirmation'
    ];

    static public function findOrFail($uuid)
    {
        return User::where('uuid',$uuid)->firstOrFail();
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getObsfucatedCPF()
    {
        return preg_replace('/(^\d{3})|(?<=-)(\d{2})/','*',$this->cpf);
    }

    public function getObfuscatedEmail()
    {
        return preg_replace('/(?<=.).(?=.*@)/','*',$this->email);
    }


    public function getEmailConfirmationAttribute($value)
    {
        return $this->attributes['email'];
    }

    public function getSocialName() {
        return $this->social_name ?? $this->name;
    }

    public function contact()
    {
        return $this->hasOne('App\Models\User\Contact');
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\User\Permission');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription');
    }    


}
