<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @todo Reescrever essa lógica quanto tivermos a estrutura de processo seletivo #23
     * O cpf só pode ser alterado se o usuário nunca participou de um edital inteiro por completo
     * Por hora retorna sempre TRUE
     */
    public function updateCPF(User $user, User $userToUpdate)
    {     
        if (Gate::denies('isAdmin')) {
            return $user->id === $userToUpdate->id && $this->checkIfNeverParticipatedInAFinalizedNotice($userToUpdate);
        }
        return $this->checkIfNeverParticipatedInAFinalizedNotice($userToUpdate);
    }

    private function checkIfNeverParticipatedInAFinalizedNotice(User $userToUpdate)
    {
        $subscriptions = $userToUpdate->subscriptions()->whereHas('notice',function(Builder $query) {
            $now = Carbon::now();
            $query->where('subscription_final_date', '<=', $now);
        })->count();        
        return $subscriptions < 1;
        
    }

    
    public function deleteUser(User $user, User $userToDelete)
    {   
        if (Gate::allows('isAdmin')) {
            return $userToDelete->subscriptions()->count() < 1 ;
        }        
        return $userToDelete->subscriptions()->count() < 1 && $user->id === $userToDelete->id;
    }

}
