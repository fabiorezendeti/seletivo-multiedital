<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
        

    public function testAdminRoutesAccessFromAdminUser()
    {
        $user = User::whereHas('permissions',function($query) { 
            $query->where('role_id',1);
         })->first();
        

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
                
    }

    public function testAdminRoutesAccessFromNonAdminUser()
    {
        $user = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();    

        $response = $this->actingAs($user)->get(route('admin.users.index'));        
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.users.edit',['user'=>$user]));
        $response->assertStatus(403);

        
        $response->assertStatus(403);

    }

}
