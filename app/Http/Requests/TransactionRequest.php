<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if((isset($this->action)) && (($this->action) == "store") ){
            if((isset($this->transaction_type_id)) && (($this->transaction_type_id) == 2) ){ // cash receiving voucher

                $con    =   [
                                'account_ids'           => 'required|array|min:1',
                                'account_ids.*'         => 'required|numeric|min:1|distinct',

                                'details'               => 'required|array|min:1',
                                'details.*'             => 'required|string',

                                'amounts'                => 'required|array|min:1',
                                'amounts.*'              => 'required|numeric|min:0',

                                'company_id'            => 'required|numeric|min:1|exists:companies,id',
                                'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                            ];

                return $con; 
            }else if((isset($this->transaction_type_id)) && (($this->transaction_type_id) == 3) ){ // cash payment voucher

                $con    =   [
                                'account_ids'           => 'required|array|min:1',
                                'account_ids.*'         => 'required|numeric|min:1|distinct',

                                'details'               => 'required|array|min:1',
                                'details.*'             => 'required|string',

                                'amounts'                => 'required|array|min:1',
                                'amounts.*'              => 'required|numeric|min:0',

                                'company_id'            => 'required|numeric|min:1|exists:companies,id',
                                'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                            ];

                return $con; 

            }else if((isset($this->transaction_type_id)) && (($this->transaction_type_id) == 4) ){ // bank deposit voucher

                $con    =   [

                                'bank_id'                => 'required|numeric|min:1',
                                'method'                 => 'required|string|in:cash,cheque,online',
                                'transaction_date'       => 'required|date|after_or_equal:today',


                                'account_ids'           => 'required|array|min:1',
                                'account_ids.*'         => 'required|numeric|min:1|distinct',

                                'details'               => 'required|array|min:1',
                                'details.*'             => 'required|string',

                                'amounts'                => 'required|array|min:1',
                                'amounts.*'              => 'required|numeric|min:0',

                                'company_id'            => 'required|numeric|min:1|exists:companies,id',
                                'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                            ];

                return $con; 

            }else if((isset($this->transaction_type_id)) && (($this->transaction_type_id) == 5) ){ // bank payment voucher

                $con    =   [

                            'bank_id'                => 'required|numeric|min:1',
                            // 'method'                 => 'required|string|in:cash,cheque,online',
                            'cheque_no'              => 'required|string',
                            'transaction_date'       => 'required|date|after_or_equal:today',


                            'account_ids'           => 'required|array|min:1',
                            'account_ids.*'         => 'required|numeric|min:1|distinct',

                            'details'               => 'required|array|min:1',
                            'details.*'             => 'required|string',

                            'amounts'                => 'required|array|min:1',
                            'amounts.*'              => 'required|numeric|min:0',

                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

                return $con; 

            }else if((isset($this->transaction_type_id)) && (($this->transaction_type_id) == 6) ){ // Journal voucher
                $con    =   [

                            // 'method'                 => 'required|string|in:cash,cheque,online',
                            // 'bank_id'                => 'required|numeric|min:1',
                            // 'transaction_date'       => 'required|date|after_or_equal:today',

                            // 'dbt_acnt_id'            => 'required|numeric|min:1|exists:accounts,id',
                            // 'dbt_detail'             => 'required|string',
                            // 'dbt_amount'             => 'required|numeric|min:0',


                            'dbt_acnt_ids'            => 'required|array|min:1',
                            'dbt_acnt_ids.*'          => 'required|numeric|min:1|distinct',

                            'dbt_details'                => 'required|array|min:1',
                            'dbt_details.*'              => 'required|string',

                            'dbt_amounts'                => 'required|array|min:1',
                            'dbt_amounts.*'              => 'required|numeric|min:0',


                            
                            'account_ids'            => 'required|array|min:1',
                            'account_ids.*'          => 'required|numeric|min:1|distinct',

                            'details'                => 'required|array|min:1',
                            'details.*'              => 'required|string',

                            'amounts'                => 'required|array|min:1',
                            'amounts.*'              => 'required|numeric|min:0',

                            'company_id'             => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'              => 'required|numeric|min:1|exists:branches,id',
                        ];

                return $con; 

            }else{
                $con    =   [
                            'name'                  => 'required|min:2',
                            'account_type_id'       => 'required|numeric|min:1|exists:account_types,id',
                            'account_type_id'       => 'required|numeric|min:1|exists:account_types,id',
                            'group_head_id'         => 'required|numeric|min:1|exists:account_types,id',
                            'child_head_id'         => 'required|numeric|min:1|exists:account_types,id',
                            'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                        ];

                return $con; 
            }
        }
    }

    public function messages()
    {
        $messages  = [
            // 'client_id.required' => 'The Client ID is required'
        ];

        if(isset($this->account_ids)  && (count(($this->account_ids))> 0)){
            foreach ($this->account_ids as $key => $val) {
                $messages["account_ids.$key.min"] = "Please select account: " . ($key+1);
                $messages["account_ids.$key.numeric"] = "Account: " . ($key+1). " must be numeric.";
                $messages["account_ids.$key.required"] = "Account: " . ($key+1). " field is required.";
                $messages["account_ids.$key.distinct"] = "Account: " . ($key+1). " is same.";
            }
        }

        if(isset($this->details)  && (count(($this->details))> 0)){
            foreach ($this->details as $key => $val) {
                $messages["details.$key.min"] = "Please enter detail: " . ($key+1);
                $messages["details.$key.string"] = "detail: " . ($key+1). " must be string.";
                $messages["details.$key.required"] = "detail: " . ($key+1). " field is required.";
            }
        }


        if(isset($this->amounts)  && (count(($this->amounts))> 0)){
            foreach ($this->amounts as $key => $val) {
                $messages["amounts.$key.min"] = "Please enter amount " . ($key+1) .": greater than 0";
                $messages["amounts.$key.numeric"] = "Amount: " . ($key+1). " must be numeric.";
                $messages["amounts.$key.required"] = "Amount: " . ($key+1). " field is required.";
            }
        }

        return $messages;
    }
}
    
