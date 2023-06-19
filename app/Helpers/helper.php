<?php
use App\Models\City;
use App\Models\Branch;
use App\Models\Company;
use App\Models\TransactionType;

// get logged in user id
function hp_user_id(){
    return \Illuminate\Support\Facades\Auth::user()->id;
}

// get logged in user's company
function hp_company_id(){
    return \Illuminate\Support\Facades\Auth::user()->company_id;
}

// get a selected company
function hp_companies($company_id){
    return Company::where('id',$company_id)->pluck('name','id')->all();
}

// get all branches on selected company
function hp_branches($company_id){
    return Branch::where('company_id',$company_id)->pluck('name','id')->all();
}

// get all cities
function hp_cities(){
    return City::pluck('name','id')->all();
}

// get all amount types
function hp_amount_types(){
   return array('D' => 'Debit','C' =>'Credit') ;
}

// get all transaction types
function hp_transaction_types($flag){
    if($flag){
        return TransactionType::where('id','!=',1)->pluck('name','id')->all();
    }else{
        return TransactionType::where('id','=',1)->pluck('name','id')->all();
    }
}

function hp_send_exception($e){
    dd($e);
}


function hp_currency_symbol(){
    return "PKR. ";
}