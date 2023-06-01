<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'name'          => 'required|unique:roles,name',
                            'permission'    => 'required',
                        ];

            return $con; 

        }else{
            $con    =   [
                            'name'          => 'required',
                            'permission'    => 'required',
                        ];

            return $con; 
        }
    }
}
    
