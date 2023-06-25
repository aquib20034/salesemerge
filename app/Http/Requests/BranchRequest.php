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
                            'name'                  => 'required|unique:branches,name|min:2|string',
                            'mobile_no'             => 'required|unique:branches,mobile_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                        ];


            if(isset($this->phone_no)){
                $con['phone_no']     = 'required|digits:11|numeric';
            }
            return $con;

        }else{
            $con    =   [
                            'name'                  => 'required|min:2|string',
                            'mobile_no'             => 'required|digits:11|numeric',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'created_by'            => 'required|numeric|min:1|exists:users,id',
                        ];
            if(isset($this->phone_no)){
                $con['phone_no']     = 'required|digits:11|numeric';
            }

            return $con;
        }
    }
}

