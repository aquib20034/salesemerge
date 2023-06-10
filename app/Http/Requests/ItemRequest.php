<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
                            // 'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            // 'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                            'manufacturer_id'       => 'required|numeric|min:1|exists:manufacturers,id',
                            'category_id'           => 'required|numeric|min:1|exists:categories,id',
                            'group_id'              => 'required|numeric|min:1|exists:groups,id',
                            'manufacturer_id'       => 'required|numeric|min:1|exists:manufacturers,id',
                            'unit_id'               => 'required|numeric|min:1|exists:units,id',
                            'tot_piece'             => 'sometimes|numeric|min:1',
                            'free_piece'            => 'sometimes|numeric|min:0',
                            'purchase_price'        => 'sometimes|numeric|min:0',
                            'sell_price'            => 'sometimes|numeric|min:0',
                            'unit_sell_price'       => 'sometimes|numeric|min:0',
                            'company_percentage'    => 'sometimes|numeric|min:0',
                            'to_percentage'         => 'sometimes|numeric|min:0',

                        ];

            return $con; 

        }else{
            $con    =   [
                            'name'                  => 'required|min:2|regex:/^([^0-9]*)$/',
                            // 'company_id'            => 'required|numeric|min:1|exists:companies,id',
                            // 'branch_id'             => 'required|numeric|min:1|exists:branches,id',
                            'manufacturer_id'       => 'required|numeric|min:1|exists:manufacturers,id',
                            'category_id'           => 'required|numeric|min:1|exists:categories,id',
                            'group_id'              => 'required|numeric|min:1|exists:groups,id',
                            'manufacturer_id'       => 'required|numeric|min:1|exists:manufacturers,id',
                            'unit_id'               => 'required|numeric|min:1|exists:units,id',
                            'tot_piece'             => 'sometimes|numeric|min:1',
                            'free_piece'            => 'sometimes|numeric|min:0',
                            'purchase_price'        => 'sometimes|numeric|min:0',
                            'sell_price'            => 'sometimes|numeric|min:0',
                            'unit_sell_price'       => 'sometimes|numeric|min:0',
                            'company_percentage'    => 'sometimes|numeric|min:0',
                            'to_percentage'         => 'sometimes|numeric|min:0',
                        ];

            return $con; 
        }
    }
}
    
