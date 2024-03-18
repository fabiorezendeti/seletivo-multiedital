<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamLocation extends Model
{
    use HasFactory;

    protected $casts = [
        'address'   => 'array'
    ];

    public $fillable = [
        'campus_id',
        'local_name',
        'address',
        'priority',
        'active'
    ];

    public function campus()
    {
        return $this->belongsTo('App\Models\Organization\Campus');
    }

    public function examRoomBookings(){
        return $this->hasMany('App\Models\Process\ExamRoomBooking');
    }

    public function examRooms()
    {
        return $this->hasMany('App\Models\Process\ExamRoom');
    }

    public function hasRoomForSpecialNeeds()
    {
        return $this->examRooms()->isForSpecialNeeds()->count() > 0;
    }

    public function scopeIsActivated($query)
    {
        return $query->where('active',true);
    }

    public function getAddressString()
    {
        return "Rua: {$this->address['street']}, Nº {$this->address['number']} -
         Bairro {$this->address['district']} - CEP: {$this->address['zip_code']} - {$this->address['city']['name']}, {$this->address['city']['state']['slug']}";
    }

    public function getPhoneString()
    {
        return $this->address['phone_number'];
    }

    /**
     * Verifica se o sala possui ao menos um candidato alocado
     * @return false
     */
    public function hasAllocation($notice_id){
        return $this->subscriptions->where('notice_id', $notice_id)->count() > 0 ? true : false;
    }

    /**
     * Retorna todas as inscrições do local selecionado
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function subscriptions()
    {
        return $this->hasManyThrough(Subscription::class, ExamRoomBooking::class);
    }
}
