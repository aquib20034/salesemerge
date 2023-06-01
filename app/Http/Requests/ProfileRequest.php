<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'old_password'      => 'required|min:8',
                            'password'          => 'required|min:8|confirmed',
                        ];

            return $con; 

        }else{
            $con    =   [
                            'old_password'      => 'required|min:8',
                            'password'          => 'required|min:8|confirmed',
                        ];

            return $con; 
        }
    }
}
    
