<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'role_id',
        'campus_id'
    ];

    public function campus()
    {
        return $this->belongsTo('App\Models\Organization\Campus');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\User\Role');
    }
    

}
