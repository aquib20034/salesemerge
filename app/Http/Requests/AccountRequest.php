<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'name'                  => 'required|min:2|regex:/^([^0-9]*)$/',
                            'account_type_id'       => 'required|numeric|min:1|exists:account_types,id',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

            return $con; 

        }else{
            $con    =   [
                            'name'                  => 'required|min:2|regex:/^([^0-9]*)$/',
                            'account_type_id'       => 'required|numeric|min:1|exists:account_types,id',
                            // 'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            // 'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

            return $con; 
        }
    }
}
    