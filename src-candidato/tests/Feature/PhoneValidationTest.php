<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PhoneValidationTest extends TestCase
{
    public function testPhoneValidation()
    {
        $rules = [
            'phone_number'  => ['required','celular_com_ddd'],
        ];

        $validator = Validator::make(['phone_number'=>'(49)99999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)9999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49) 9999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)999-9999'],$rules);        
        $this->assertTrue($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)3566-9999'],$rules);        
        $this->assertFalse($validator->fails());
    }

    public function testAlternativePhoneValidation()
    {
        $rules = [
            'phone_number'  => ['required','telefone_com_ddd'],
        ];

        $validator = Validator::make(['phone_number'=>'(49)3566-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)9566-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)93566-9999'],$rules);        
        $this->assertTrue($validator->fails());

        
    }
}
