<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpeningBalanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'amount_type'           => 'required',
                            'amount'                => 'required|numeric|min:0',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

            return $con; 

        }else{
            $con    =   [
                            'amount_type'           => 'required',
                            'amount'                => 'required|numeric|min:0',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

            return $con; 
        }
    }
}
    
