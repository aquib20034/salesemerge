<?php

namespace Database\Seeders;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Account opening Voucher',
            'Cash Receiving Voucher',
            'Cash Payment Voucher',
            'Bank Deposit Voucher',
            'Bank Payment Voucher',
            'Journal Voucher Voucher'
         ];
         foreach ($data as $val) {
            TransactionType::create([
                            'name' => $val,
                            'company_id' => 1,
                            'branch_id' => 1,
                        ]);
         }
     }
}
