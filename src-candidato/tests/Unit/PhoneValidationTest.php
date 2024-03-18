<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase as TestsTestCase;

class PhoneValidationTest extends TestsTestCase
{
    public function testPhoneValidation()
    {
        $rules = [
            'phone_number'  => ['required','celular_com_ddd'],
        ];
        

        $validator = Validator::make(['phone_number'=>'(49) 99999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)9999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49) 9999-9999'],$rules);        
        $this->assertFalse($validator->fails());

        $validator = Validator::make(['phone_number'=>'(49)999-9999'],$rules);        
        $this->assertTrue($validator->fails());
    }
}
