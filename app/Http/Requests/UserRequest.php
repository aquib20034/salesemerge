<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            $con    =   [
                            'name'                  => 'required|min:3|regex:/^([^0-9]*)$/',
                            'email'                 => 'email|unique:users,email,NULL,id,deleted_at,NULL',
                            'phone_no'              => 'sometimes|unique:users,phone_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'mobile_no'             => 'required|unique:users,mobile_no,NULL,id,deleted_at,NULL|digits:11|numeric',
                            'password'              => 'required|min:8',
                            'roles'                 => 'required',
                        ];

            if(!empty($this->profile_pic)){
                $con['profile_pic']     = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }

            return $con; 

        }else{
            // vaidate if form has profile pic
            $con    =   [
                            'name'                  => 'required|min:3|regex:/^([^0-9]*)$/',
                            'email'                 => ['email', Rule::unique('users')->ignore($this->user)], 
                            'phone_no'              => ['sometimes','digits:11','numeric', Rule::unique('users')->ignore($this->user)],
                            'mobile_no'             => ['required','digits:11','numeric', Rule::unique('users')->ignore($this->user)],
                            'roles'                 => 'required',
                        ];
            
            if(!empty($this->profile_pic)){
                $con['profile_pic']     = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }
            return $con; 
        }
    }
}
    
