<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentType extends Model
{
    use HasFactory;    

    protected $hidden = ['age','sex','required'];

    protected $fillable = [
        'title',
        'description',
        'active',
        'context',
        'order',
        'age',
        'sex',
        'required'
    ];

    protected $ages = [
        '18+' => 'Maior de idade (18 ou mais)',
        '<18' => 'Menor de idade (menos que 18)',        
    ];
    protected $sexs = [
        'M' => 'Masculino',
        'F' => 'Feminino',        
    ];

    protected $contexts = [
        'matrícula',
        'inscrição',
        'isenção',
        'recurso'
    ];

    protected $appends = [
        'field_name'
    ];

    public function affirmativeActions()
    {
        return $this->belongsToMany(AffirmativeAction::class);
    }

    public function getContexts()
    {
        return $this->contexts;
    }

    public function getAges()
    {
        return $this->ages;
    }

    public function getSexs()
    {
        return $this->sexs;
    }

    public function getFieldNameAttribute()
    {
        return Str::slug($this->title,'_');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order','asc');
    }

    public function scopeContextEnrollment($query)
    {
        return $query->where('context','matrícula');
    }

    public function scopeAllDocumentsNeeded($query, $affirmative_action_id)
    {
        return $query->where(function ($and) use ($affirmative_action_id) {
            $and->whereDoesntHave('affirmativeActions')
                ->orWhereHas('affirmativeActions', function ($or) use ($affirmative_action_id) {
                $or->where('id', $affirmative_action_id);
            });
        });
                
    }
}
