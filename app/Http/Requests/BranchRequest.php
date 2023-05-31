<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
                            'phone_no'              => 'sometimes|unique:branches,phone_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'mobile_no'             => 'sometimes|unique:branches,mobile_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'address'               => 'sometimes|min:3',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                        ];

            return $con; 

        }else{
            $con    =   [
                            'name'                  => 'required|min:2|string',
                            'phone_no'              => 'sometimes|unique:branches,phone_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'mobile_no'             => 'sometimes|unique:branches,mobile_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'address'               => 'sometimes|min:3',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                        ];

            return $con; 
        }
    }
}
    
