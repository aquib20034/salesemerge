<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'name'                  => 'required|min:2|string',
                            'code'                  => 'required|min:2|string',
                            'owner_name'            => 'required|min:2|regex:/^([^0-9]*)$/',
                            'phone_no'              => 'sometimes|unique:branches,phone_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'mobile_no'             => 'sometimes|unique:branches,mobile_no,NULL,id,deleted_at,NULL|digits:11|numeric',

                        ];

            return $con; 

        }else{
            $con    =   [
                            'name'                  => 'required|min:2|string',
                            'code'                  => 'required|min:2|string',
                            'owner_name'            => 'required|min:2|regex:/^([^0-9]*)$/',
                            'phone_no'              => 'sometimes|digits:11|numeric',
                            'mobile_no'             => 'sometimes|digits:11|numeric',
                        ];

            return $con; 
        }
    }
}
    
