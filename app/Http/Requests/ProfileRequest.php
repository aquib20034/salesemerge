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


           
            if(!empty($this->profile_pic)){
                $con['profile_pic']     = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }

            return $con; 

        }else{
            $con = [];
            if((!empty($this->old_password)) || (!empty($this->password))){
                $con['old_password']     = 'required|min:8';
                $con['password']        = 'required|min:8|confirmed';
            }

            // $con    =   [
            //                 'old_password'      => 'required|min:8',
            //                 'password'          => 'required|min:8|confirmed',
            //             ];

            if(!empty($this->profile_pic)){
                $con['profile_pic']     = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }

            return $con; 
        }
    }
}
    
