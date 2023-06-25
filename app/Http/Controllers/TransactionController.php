<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Gate;
use DataTables;
use App\Models\City;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\TransactionRequest;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    var $account_opening_text;
    var $today;
    function __construct()
    {
        $this->account_opening_text = "Account opening";
        $this->today                = date('Y-m-d H:i:s');
        $this->middleware('permission:transaction-list', ['only' => ['index','show']]);
        $this->middleware('permission:transaction-create', ['only' => ['create','store']]);
        $this->middleware('permission:transaction-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:transaction-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
       
        return view('transactions.index');
    }

    public function create()
    {
        $cities             = hp_cities();
        // $amount_types       = hp_amount_types();
        $transaction_types  = hp_transaction_types(true);  // false: get all except Account opening voucher
        $account_types      = AccountType::where('company_id',hp_company_id())
                                ->whereNull('parent_id')
                                ->pluck('name','id')
                                ->all();

        return view('transactions.create',compact(
                                                'cities',
                                                'account_types',
                                                // 'amount_types',
                                                'transaction_types'
                                            ));
    }

    public function store(TransactionRequest $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();
                $msg                    = "Record added successfully.";
                $csh_in_hnd_account_id  = isset(hp_cash_in_hand()->id) ? hp_cash_in_hand()->id : 0;

                // Validate the request data
                $validated = $request->validated();

                if ($request->filled('transaction_type_id') && ($request->transaction_type_id == 2)) { // cash receiving voucher
                    // Process cash receiving voucher
                    $this->processCashReceivingVoucher($request, $csh_in_hnd_account_id);
                } elseif ($request->filled('transaction_type_id') && ($request->transaction_type_id == 3)) { // cash payment voucher
                    // Process cash payment voucher
                    $this->processCashPaymentVoucher($request, $csh_in_hnd_account_id);
                }elseif ($request->filled('transaction_type_id') && ($request->transaction_type_id == 4)) { // bank deposit voucher
                    // Process cash payment voucher
                    $this->processBankDepositVoucher($request);
                }elseif ($request->filled('transaction_type_id') && ($request->transaction_type_id == 5)) { // bank payment voucher
                    // Process cash payment voucher
                    $this->processBankPaymentVoucher($request);
                }
            // Commit the transaction
            DB::commit();
            return response()->json(['status' => 200, 'msg' => $msg]);
        } catch (\Exception $e) {
            // Roll back the transaction if an exception occurs
            DB::rollBack();
            hp_send_exception($e);

            return response()->json([
                'code' => 401,
                'errors' => [0 => "Something went wrong."]
            ], 401);
        }
    }


    // Process cash receiving voucher
    private function processCashReceivingVoucher($request, $csh_in_hnd_account_id)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {

                $payee                  = Account::select('name')->findOrFail($account_id);
                $transaction_type_id    = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                $detail                 = isset($request->details[$key]) ? $request->details[$key] : null;
                $amount                 = isset($request->amounts[$key]) ? $request->amounts[$key] : 0;
                $detail2                = $detail ."; Payee: " . ((isset($payee->name)) ? $payee->name : "");

                // Create credit transaction
                $this->createCustomTransaction(null,null, $account_id, $transaction_type_id, $detail, 'C', $amount);

                // Create debit transaction
                $this->createCustomTransaction(null,null, $csh_in_hnd_account_id, $transaction_type_id, $detail2, 'D', $amount);

            }
        }
    }

    // Process cash payment voucher
    private function processCashPaymentVoucher($request, $csh_in_hnd_account_id)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {
                $receiver               = Account::select('name')->findOrFail($account_id);
                $transaction_type_id    = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                $detail                 = isset($request->details[$key]) ? $request->details[$key] : null;
                $amount                 = isset($request->amounts[$key]) ? $request->amounts[$key] : 0;
                $detail2                = $detail ."; Receiver: " . ((isset($receiver->name)) ? $receiver->name : "");

                // Create credit transaction
                $this->createCustomTransaction(null,null, $csh_in_hnd_account_id, $transaction_type_id, $detail2, 'C', $amount);

                // Create debit transaction
                $this->createCustomTransaction(null,null, $account_id, $transaction_type_id, $detail, 'D', $amount);
            }
        }
    }

    // Process bank deposit voucher
    private function processBankDepositVoucher($request)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {

                $payee                  = Account::select('name')->findOrFail($account_id);
                $transaction_type_id    = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                $detail                 = isset($request->details[$key]) ? $request->details[$key] : null;
                $amount                 = isset($request->amounts[$key]) ? $request->amounts[$key] : 0;
                $detail2                = $detail ."; Depositor: " . ((isset($payee->name)) ? $payee->name : "");
                $method                 = $request->filled('method') ? $request->method : 0;
                $transaction_date       = $request->filled('transaction_date') ? $request->transaction_date : null;

                // Create credit transaction
                $this->createCustomTransaction($method, $transaction_date, $account_id, $transaction_type_id, $detail, 'C', $amount);

                // Create debit transaction
                $bank_id               = $request->filled('bank_id') ? $request->bank_id : 0;
                $this->createCustomTransaction($method, $transaction_date, $bank_id, $transaction_type_id, $detail2, 'D', $amount);

            }
        }
    }


    // Process cash payment voucher
    private function processBankPaymentVoucher($request)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {
                $receiver               = Account::select('name')->findOrFail($account_id);
                $transaction_type_id    = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
                $detail                 = isset($request->details[$key]) ? $request->details[$key] : null;
                $amount                 = isset($request->amounts[$key]) ? $request->amounts[$key] : 0;
                $detail2                = $detail ."; Receiver: " . ((isset($receiver->name)) ? $receiver->name : "");
                $method                 = $request->filled('method') ? $request->method : 0;
                $transaction_date       = $request->filled('transaction_date') ? $request->transaction_date : null;

                // Create credit transaction
                $bank_id               = $request->filled('bank_id') ? $request->bank_id : 0;
                $this->createCustomTransaction($method, $transaction_date, $bank_id, $transaction_type_id, $detail2, 'C', $amount);

                // Create debit transaction
                $this->createCustomTransaction($method, $transaction_date, $account_id, $transaction_type_id, $detail, 'D', $amount);
            }
        }
    }


     // create custom transaction
    private function createCustomTransaction($method=null,$transaction_date=null, $account_id, $transaction_type_id, $detail, $amount_type, $amount)
    {
        $trnx                       = new Transaction();
        $trnx->account_id           = $account_id;
        $trnx->transaction_type_id  = $transaction_type_id;
        $trnx->detail               = $detail;
        $trnx->method               = $method;
        $trnx->transaction_date     = (isset($transaction_date)) ? $transaction_date : $this->today;
        $trnx->save();

        $ledger                     = new Ledger();
        $ledger->transaction_id     = $trnx->id;
        $ledger->account_id         = $account_id;
        $ledger->amount_type        = $amount_type;
        $ledger->amount             = $amount;
        $ledger->save();

        $account                    = Account::where('id', $account_id)->first();
        $account->current_balance   = hp_calc_current_balance($account->id);
        $account->save();
    }


    public function show($id)
    {
        $company_id = hp_company_id();
        $data       = Account::where('company_id',$company_id)->findOrFail($id);

        return view('accounts.show',compact('data'));
    }


    public function edit($id)
    {
        $company_id         = hp_company_id();
        $data               = Account::where('company_id',$company_id)->findOrFail($id);

        $cities             = hp_cities();
        $branches           = hp_branches($company_id);
        $companies          = hp_companies($company_id);

        $account_types      = AccountType::where('company_id',$company_id)
                                ->whereNull('parent_id')
                                ->pluck('name','id')
                                ->all();

        $group_heads        = AccountType::where('company_id',$company_id)
                                ->where('parent_id',$data->account_type_id)
                                ->pluck('name','id')
                                ->all();


        $child_heads        = AccountType::where('company_id',$company_id)
                                ->where('parent_id',$data->group_head_id)
                                ->pluck('name','id')
                                ->all();

        return view('accounts.edit',compact(
                                                'data',
                                                'cities',
                                                'branches',
                                                'companies',
                                                'account_types',
                                                'group_heads',
                                                'child_heads'
                                            ));
    }


    public function update(AccountRequest $request, $id)
    {
        // validated input data...
        $validated          = $request->validated();
        $data               = Account::findOrFail($id);
        $input              = $request->all();

        // if active is not set, make it in-active
        $input['active']    = ((isset($input['active'])) && ($input['active'] == 1 )) ?  1 : 0;
        $upd                = $data->update($input);


        return redirect()
                ->route('accounts.index')
                ->with('success','Record updated successfully.');
    }

    public function destroy(Account $account)
    {
        abort_if(Gate::denies('account-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        // reterive transaction
        $transactions       = Transaction::where('account_id',$account->id)->get();

        // reterive ledger
        $ledgers            = Ledger::where('account_id',$account->id)->get();

        // Delete transactions
        $transactions->each->delete();

        // Delete ledgers
        $ledgers->each->delete();

        // delete account
        $account->delete();
        return back()->with('success','Record deleted successfully.');
    }

}
