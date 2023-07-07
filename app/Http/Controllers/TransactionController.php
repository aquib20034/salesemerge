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

    public function getLastTrnxId(){
        $lastTransaction = Transaction::latest()->first();
    
        if ($lastTransaction) {
            $lastId = $lastTransaction->id;
            return $lastId;
        } else {
            return 0; // or handle the case when there are no transactions
        }
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
                }elseif ($request->filled('transaction_type_id') && ($request->transaction_type_id == 6)) { // journal voucher
                    // Process cash payment voucher
                    $this->processJournalVoucher($request);
                }
            // Commit the transaction
            DB::commit();

            $last_id = $this->getLastTrnxId();
            return response()->json(['status' => 200, 'msg' => $msg, 'last_id' =>$last_id]);
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

                $data                           = array();
                $account                          = Account::select('name')->findOrFail($account_id);

                $data['method']                 = null;
                $data['reference_id']           = null;
                $data['transaction_date']       = null;
                $data['transaction_type_id']    = ($request->filled('transaction_type_id') ? $request->transaction_type_id : 0);
                $data['amount']                 = (isset($request->amounts[$key]) ? $request->amounts[$key] : 0);
                
                $data['custom_id']              = hp_last_trnx_custom_id($data['transaction_type_id']); // Call the helper function;
                $data['account_id']             = $account_id;
                $data['amount_type']            = 'C';
                $data['detail']                 = (isset($request->details[$key]) ? $request->details[$key] : null);

                // Create credit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);
                
                $data['custom_id']              = null; // reference trnx doesnot have custom trnx id
                $data['account_id']             = $csh_in_hnd_account_id;
                $data['amount_type']            = 'D';
                $data['detail']                 = "Payee: " . ((isset($account->name)) ? $account->name : "");
                $data['reference_id']           = $ref_trnx_id;

                
                // Create debit transaction
                $this->createCustomTransaction($data);

            }
        }
    }

    // Process cash payment voucher
    private function processCashPaymentVoucher($request, $csh_in_hnd_account_id)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {

                $data                           = array();
                $account                        = Account::select('name')->findOrFail($account_id);

                $data['method']                 = null;
                $data['reference_id']           = null;
                $data['transaction_date']       = null;
                $data['transaction_type_id']    = ($request->filled('transaction_type_id') ? $request->transaction_type_id : 0);
                $data['amount']                 = (isset($request->amounts[$key]) ? $request->amounts[$key] : 0);
                
                $data['custom_id']              = hp_last_trnx_custom_id($data['transaction_type_id']); // Call the helper function;
                $data['account_id']             = $account_id;
                $data['amount_type']            = 'D';
                $data['detail']                 = (isset($request->details[$key]) ? $request->details[$key] : null);

                
                // Create debit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);

                $data['custom_id']              = null; // reference trnx doesnot have custom trnx id
                $data['account_id']             = $csh_in_hnd_account_id;
                $data['amount_type']            = 'C';
                $data['detail']                 = "Receiver: " . ((isset($account->name)) ? $account->name : "");
                $data['reference_id']           = $ref_trnx_id;

                // Create credit transaction
                $this->createCustomTransaction($data);
            }
        }
    }

    

    // Process bank deposit voucher
    private function processBankDepositVoucher($request)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {
                $data                           = array();
                $account                        = Account::select('name')->findOrFail($account_id);

                $data['method']                 = $request->filled('method') ? $request->method : 0;
                $data['reference_id']           = null;
                $data['transaction_date']       = $request->filled('transaction_date') ? $request->transaction_date : null;
                $data['transaction_type_id']    = ($request->filled('transaction_type_id') ? $request->transaction_type_id : 0);
                $data['amount']                 = (isset($request->amounts[$key]) ? $request->amounts[$key] : 0);
                
                $data['custom_id']              = hp_last_trnx_custom_id($data['transaction_type_id']); // Call the helper function;
                $data['account_id']             = $account_id;
                $data['amount_type']            = 'C';
                $data['detail']                 = (isset($request->details[$key]) ? $request->details[$key] : null);

                // Create credit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);


                // $data['custom_id']              = null; // reference trnx doesnot have custom trnx id
                $data['account_id']             = $request->filled('bank_id') ? $request->bank_id : 0;
                $data['amount_type']            = 'D';
                $data['detail']                 = "Depositor: " . ((isset($account->name)) ? $account->name : "");
                // $data['reference_id']           = $ref_trnx_id;

                // Create debit transaction
                $this->createCustomTransaction($data);

            }
        }
    }


    // Process bank payment voucher  -- Bank credit and user debit
    private function processBankPaymentVoucher($request)
    {
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {
                $data                           = array();
                $account                        = Account::select('name')->findOrFail($account_id);
                
                $data['cheque_no']              = $request->filled('cheque_no') ? $request->cheque_no : null;
                $data['method']                 = null;
                $data['reference_id']           = null;
                $data['transaction_date']       = $request->filled('transaction_date') ? $request->transaction_date : null;
                $data['transaction_type_id']    = ($request->filled('transaction_type_id') ? $request->transaction_type_id : 0);
                $data['amount']                 = (isset($request->amounts[$key]) ? $request->amounts[$key] : 0);
                
                $data['custom_id']              = hp_last_trnx_custom_id($data['transaction_type_id']); // Call the helper function;
                $data['account_id']             = $account_id;
                $data['amount_type']            = 'D';
                $data['detail']                 = (isset($request->details[$key]) ? $request->details[$key] : null);

                // Create credit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);


                // $data['custom_id']              = null; // reference trnx doesnot have custom trnx id
                $data['account_id']             = $request->filled('bank_id') ? $request->bank_id : 0;
                $data['amount_type']            = 'C';
                $data['detail']                 = "Receiver: " . ((isset($account->name)) ? $account->name : "");
                // $data['reference_id']           = $ref_trnx_id;

                // Create debit transaction
                $this->createCustomTransaction($data);

            }
        }
    }

    // Process cash payment voucher
    private function processJournalVoucher($request)
    {

        // dd($request);
        $transaction_type_id    = $request->filled('transaction_type_id') ? $request->transaction_type_id : 0;
        $custom_id              = hp_last_trnx_custom_id($transaction_type_id); // Call the helper function;
        if ($request->filled('account_ids')) {
            foreach ($request->account_ids as $key => $account_id) {
                $data                           = array();
                $data['amount_type']            = 'C'; 
                $data['custom_id']              = $custom_id;
                $data['account_id']             = $account_id;
                $data['transaction_type_id']    = $transaction_type_id;
                $data['detail']                 = (isset($request->details[$key]) ? $request->details[$key] : null);
                $data['amount']                 = (isset($request->amounts[$key]) ? $request->amounts[$key] : 0);

                // Create credit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);
            }


            foreach ($request->dbt_acnt_ids as $key => $account_id) {
                $data                           = array();
                $data['amount_type']            = 'D'; 
                $data['custom_id']              = $custom_id;
                $data['account_id']             = $account_id;
                $data['transaction_type_id']    = $transaction_type_id;
                $data['detail']                 = (isset($request->dbt_details[$key]) ? $request->dbt_details[$key] : null);
                $data['amount']                 = (isset($request->dbt_amounts[$key]) ? $request->dbt_amounts[$key] : 0);

                // Create debit transaction
                $ref_trnx_id                    = $this->createCustomTransaction($data);
            }

            // $data['amount_type']            = 'D';
            // $data['custom_id']              = $custom_id;
            // $data['account_id']             = $request->filled('dbt_acnt_id') ? $request->dbt_acnt_id : 0;
            // $data['detail']                 = $request->filled('dbt_detail') ? $request->dbt_detail : null;
            // $data['amount']                 = $request->filled('dbt_amount') ? $request->dbt_amount : 0;

            // Create debit transaction
            // $this->createCustomTransaction($data);
        }
    }


   

    // create custom transaction
    private function createCustomTransaction($data)
    {
        // $custom_id=null,$reference_id=null,$method=null,$transaction_date=null, $account_id, $transaction_type_id, $detail, $amount_type, $amount

        $trnx                       = new Transaction();
        $trnx->cheque_no            = isset($data['cheque_no']) ? ($data['cheque_no']) : null;
        $trnx->custom_id            = isset($data['custom_id']) ? ($data['custom_id']) : null;
        $trnx->account_id           = isset($data['account_id']) ? ($data['account_id']) : null;
        $trnx->transaction_type_id  = isset($data['transaction_type_id']) ? ($data['transaction_type_id']) : null;
        $trnx->reference_id         = isset($data['reference_id']) ? ($data['reference_id']) : null;
        $trnx->detail               = isset($data['detail']) ? ($data['detail']) : null;
        $trnx->method               = isset($data['method']) ? ($data['method']) : null;
        $trnx->transaction_date     = isset($data['transaction_date']) ? concatenate_time_to_date($data['transaction_date']) : $this->today;
        $trnx->created_by           = Auth::user()->id;
        $trnx->company_id           = Auth::user()->company_id;
        $trnx->branch_id            = Auth::user()->branch_id;
        $trnx->save();

        $ledger                     = new Ledger();
        $ledger->transaction_id     = isset($trnx->id) ? ($trnx->id) : null;
        $ledger->account_id         = isset($data['account_id']) ? ($data['account_id']) : null;
        $ledger->amount_type        = isset($data['amount_type']) ? ($data['amount_type']) : null;
        $ledger->amount             = isset($data['amount']) ? ($data['amount']) : null;
        $ledger->save();

        $account                    = Account::where('id', $ledger->account_id)->first();
        $account->current_balance   = hp_calc_current_balance($ledger->account_id);
        $account->save();

        return $trnx->id;
    }


    public function show($id)
    {
        $company_id = hp_company_id();
        $data       = Account::where('company_id',$company_id)->findOrFail($id);


        return view('transactions.show',compact('data'));
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
