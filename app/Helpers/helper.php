<?php
use Carbon\Carbon;
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

// get all amount types
function hp_methods(){
    return array(
                    'cash'      => 'Cash',
                    'cheque'    => 'Cheque',
                    'online'    => 'Online'
                ) ;
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
                    ->where('branch_id',hp_branch_id()) // logged in user branch
                    ->where('account_type_id',1) // Assets
                    ->where('group_head_id',2) // Current Assets
                    ->where('child_head_id',3) // Cash in Hand
                    ->select('id','name','current_balance')
                    ->first();

}

function hp_accounts(){
    return Account::where('company_id',hp_company_id())
                    ->where('branch_id',hp_branch_id())
                    ->pluck('name','id')
                    ->all();

}

function hp_banks(){
    return Account::where('company_id',hp_company_id())
                    ->where('branch_id',hp_branch_id())
                    ->where('account_type_id',1) // Assets
                    ->where('group_head_id',2) // Current Assets
                    ->where('child_head_id',4) //Bank Accounts
                    ->pluck('name','id')
                    ->all();

}

function hp_calc_current_balance($account_id)
{
    $debits = Ledger::where('account_id', $account_id)
                    ->where('amount_type', 'D')
                    ->sum('amount');

    $credits = Ledger::where('account_id', $account_id)
                     ->where('amount_type', 'C')
                     ->sum('amount');

    return $debits - $credits;
}


function hp_current_balance($account_id)
{
    $account = Account::select('current_balance')
                        ->where('id', $account_id)
                        ->first();

    return (isset($account->current_balance) ? $account->current_balance : 0);
}


function hp_last_trnx_custom_id($trnx_type_id){
    $lastId = Transaction::where('transaction_type_id', $trnx_type_id)
                ->whereNotNull('custom_id')
                ->latest()
                ->value('custom_id');

    return isset($lastId) ? ($lastId + 1) : 1;
}


function get_first_letters($str) {
    $words = explode(" ", $str);
    $firstLetters = array_map(function($word) {
        return $word[0];
    }, $words);
    return implode("", $firstLetters);
}

function concatenate_time_to_date($date){

    // Assuming $data['transaction_date'] contains the date in Y-m-d format (e.g., '2023-07-05')
    $transactionDate = Carbon::parse($date);
    
    // Concatenate the current time to the transaction date
    $dateTimeNow = Carbon::now();
    $transactionDateWithTime = $transactionDate->setTime($dateTimeNow->hour, $dateTimeNow->minute, $dateTimeNow->second);
    
    // Now $transactionDateWithTime contains the transaction date with the current time
    // You can use it as needed, for example:
    return $transactionDateWithTime->format('Y-m-d H:i:s');
    
}

function numberToWords2($number) {
    $ones = array(
        0 => 'ZERO',
        1 => 'ONE',
        2 => 'TWO',
        3 => 'THREE',
        4 => 'FOUR',
        5 => 'FIVE',
        6 => 'SIX',
        7 => 'SEVEN',
        8 => 'EIGHT',
        9 => 'NINE',
        10 => 'TEN',
        11 => 'ELEVEN',
        12 => 'TWELVE',
        13 => 'THIRTEEN',
        14 => 'FOURTEEN',
        15 => 'FIFTEEN',
        16 => 'SIXTEEN',
        17 => 'SEVENTEEN',
        18 => 'EIGHTEEN',
        19 => 'NINETEEN'
    );

    $tens = array(
        2 => 'TWENTY',
        3 => 'THIRTY',
        4 => 'FORTY',
        5 => 'FIFTY',
        6 => 'SIXTY',
        7 => 'SEVENTY',
        8 => 'EIGHTY',
        9 => 'NINETY'
    );

    $powers = array(
        0 => '',
        1 => 'THOUSAND',
        2 => 'MILLION',
        3 => 'BILLION',
        4 => 'TRILLION',
        5 => 'QUADRILLION',
        6 => 'QUINTILLION',
        7 => 'SEXTILLION',
        8 => 'SEPTILLION',
        9 => 'OCTILLION',
        10 => 'NONILLION',
        11 => 'DECILLION',
        12 => 'UNDECILLION',
        13 => 'DUODECILLION',
        14 => 'TREDECILLION',
        15 => 'QUATTUORDECILLION',
        16 => 'QUINDECILLION',
        17 => 'SEXDECILLION',
        18 => 'SEPTENDECILLION',
        19 => 'OCTODECILLION',
        20 => 'NOVEMDECILLION',
        21 => 'VIGINTILLION'
    );

    $num = number_format($number, 2, '.', '');
    $num_arr = explode('.', $num);

    $whole = (int) $num_arr[0];
    $fraction = (int) $num_arr[1];

    $result = '';

    if ($whole == 0) {
        $result = 'ZERO';
    } else {
        $power = 0;

        while ($whole > 0) {
            $w = $whole % 1000; // Process three digits at a time
            $whole = (int) ($whole / 1000);

            $h = floor($w / 100); // Hundreds
            $w %= 100;

            $t = floor($w / 10); // Tens
            $w %= 10;

            $temp = '';

            if ($h > 0) {
                $temp = $ones[$h] . ' HUNDRED ';
            }

            if ($t > 0 || $w > 0) {
                if ($t < 2) {
                    $temp .= $ones[$t * 10 + $w];
                } else{
                    $temp .= $tens[$t];
                    if ($w > 0) {
                        $temp .= '-' . $ones[$w];
                    }
                }
            }

            if (!empty($temp)) {
                if ($power > 0) {
                    $result = $temp . ' ' . $powers[$power] . ' ' . $result;
                } else {
                    $result = $temp . ' ' . $result;
                }
            }

            $power++;
        }
    }

    $result .= ' ONLY';

    return $result;
}
function numberToWords($number) {
    $ones = array(
        0 => 'ZERO',
        1 => 'ONE',
        2 => 'TWO',
        3 => 'THREE',
        4 => 'FOUR',
        5 => 'FIVE',
        6 => 'SIX',
        7 => 'SEVEN',
        8 => 'EIGHT',
        9 => 'NINE',
        10 => 'TEN',
        11 => 'ELEVEN',
        12 => 'TWELVE',
        13 => 'THIRTEEN',
        14 => 'FOURTEEN',
        15 => 'FIFTEEN',
        16 => 'SIXTEEN',
        17 => 'SEVENTEEN',
        18 => 'EIGHTEEN',
        19 => 'NINETEEN'
    );

    $tens = array(
        2 => 'TWENTY',
        3 => 'THIRTY',
        4 => 'FORTY',
        5 => 'FIFTY',
        6 => 'SIXTY',
        7 => 'SEVENTY',
        8 => 'EIGHTY',
        9 => 'NINETY'
    );

    $powers = array(
        0 => '',
        1 => 'THOUSAND',
        2 => 'LAKH',
        3 => 'CRORE',
        4 => 'ARAB',
        5 => 'KAROD',
        6 => 'NEEL',
        7 => 'PADMA',
        8 => 'SHANKH',
        9 => 'MAHA-SHANKH',
        10 => 'VRINDA',
        11 => 'KOTI',
        12 => 'ARAB-KOTI',
        13 => 'NIYUT',
        14 => 'ANT',
        15 => 'MAD',
        16 => 'PARARDHA',
        17 => 'MAHA-PARARDHA',
        18 => 'SHATAK',
        19 => 'SAKOTI',
        20 => 'NAYUT',
        21 => 'ABJAD',
        22 => 'AKSHOHI',
        23 => 'MAHAAKSHOHI',
        24 => 'FABJA',
        25 => 'FABJAAKSHOHI',
        26 => 'SHANKHA',
        27 => 'VRINDAMAHAAKSHOHI',
        28 => 'PADMAMAHAAKSHOHI',
        29 => 'SHARVA',
        30 => 'PADMASHARVA',
        31 => 'PADMASHARVA-MUKTAAKSHOHI',
        32 => 'SAMUDRA',
        33 => 'MAHA-SAMUDRA',
        34 => 'ANT-SAMUDRA',
        35 => 'MAD-SAMUDRA',
        36 => 'PARARDHA-SAMUDRA',
        37 => 'MAHA-PARARDHA-SAMUDRA',
    );

    $num = number_format($number, 2, '.', '');
    $num_arr = explode('.', $num);

    $whole = (int) $num_arr[0];
    $fraction = (int) $num_arr[1];

    $result = '';

    if ($whole == 0) {
        $result = 'ZERO';
    } else {
        $power = 0;

        while ($whole > 0) {
            $w = $whole % 100; // Process two digits at a time
            $whole = (int) ($whole / 100);

            $h = floor($w / 10); // Tens
            $w %= 10;

            $temp = '';

            if ($h > 0) {
                if ($h == 1) {
                    $temp = $ones[$h * 10 + $w];
                } else {
                    $temp = $tens[$h];
                    if ($w > 0) {
                        $temp .= '-' . $ones[$w];
                    }
                }
            } else {
                $temp = $ones[$w];
            }

            if (!empty($temp)) {
                if ($power > 0) {
                    $result = $temp . ' ' . $powers[$power] . ' ' . $result;
                } else {
                    $result = $temp . ' ' . $result;
                }
            }

            $power++;
        }
    }

    $result .= ' ONLY';

    return $result;
}

