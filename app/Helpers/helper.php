<?php
use App\Models\City;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Models\TransactionType;

// get logged in user id
function hp_user_id(){
    return \Illuminate\Support\Facades\Auth::user()->id;
}

// get logged in user's company
function hp_company_id(){
    return \Illuminate\Support\Facades\Auth::user()->company_id;
}


// get logged in user's company
function hp_branch_id(){
    return \Illuminate\Support\Facades\Auth::user()->branch_id;
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

function hp_today(){
    return date('Y-m-d');
}

function hp_next_transaction_id(){
    return ((Transaction::latest()->value('id')) + 1);
}

function hp_cash_in_hand(){
    return Account::where('company_id',hp_company_id())
                    ->where('branch_id',hp_branch_id())
                    ->where('account_type_id',1) // Assets
                    ->where('group_head_id',2) // Current Assets
                    ->where('child_head_id',3) // Cash in Hand
                    ->select('name','current_balance')
                    ->first();

}

function hp_accounts(){
    return Account::where('company_id',hp_company_id())
                    ->where('branch_id',hp_branch_id())
                    ->pluck('name','id')
                    ->all();

}

function hp_current_balance($account_id)
{
    $debits = Ledger::where('account_id', $account_id)
                    ->where('amount_type', 'D')
                    ->sum('amount');

    $credits = Ledger::where('account_id', $account_id)
                     ->where('amount_type', 'C')
                     ->sum('amount');

    return $debits - $credits;
}