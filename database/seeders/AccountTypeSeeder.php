<?php

namespace Database\Seeders;
use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{

    public function insert_record($records, $parent_id=NULL){
        foreach ($records as $key => $record) {

            $type = AccountType::create([
                'name'          => isset($record['name']) ? $record['name'] : "",
                'parent_id'     => $parent_id,
                'company_id'    => 1,
                'branch_id'     => 1
            ]);

            if(isset($record['rows']) && (!empty($record['rows']))){
                $this->insert_record($record['rows'],$type->id);
            }
        }
    }

    public function run()
    {

        $company_id = 1;
        $branch_id  = 1;

        $records  = [
            // ASSETS
            array(
                'name'        => 'Assets',      
                'rows'        => [
                                   array(
                                    'name'  => 'Current Assets',
                                    'rows'  => [
                                                array('name'    => 'Cash in hand'),
                                                array('name'    => 'Bank Accounts'),
                                                array('name'    => 'Inventory Account'),
                                            ]    
                                   ),
                                   array(
                                    'name'  => 'Fixed Assets',
                                    'rows'  => [
                                                array('name'    => 'Building & Land'),
                                                array('name'    => 'Furniture & Fixtures'),
                                                array('name'    => 'Machinery & Equipements'),
                                            ]    
                                   ),
                                   array(
                                    'name'  => 'Account Receivables',
                                    'rows'  => [
                                                array('name'    => 'Customers'),
                                                array('name'    => 'Advances Receivables'),
                                            ]    
                                   ),

                                ]
            ),

            // LIABILITIES
            array(
                'name'        => 'Liabilities',      
                'rows'        => [
                                   array(
                                    'name'  => 'Short Term Liabilities',
                                    'rows'  => [array('name'    => 'Short Terms Loans')]    
                                   ),
                                   array(
                                    'name'  => 'Long Term Liabilities',
                                    'rows'  => [array('name'    => 'Long Terms Loans')]    
                                   ),
                                   array(
                                    'name'  => 'Account Payables',
                                    'rows'  => [array('name'    => 'Suppliers')]    
                                   ),
                                ]
            ),

            // CAPITAL
            array(
                'name'        => 'Capital',      
                'rows'        => [
                                   array(
                                    'name'  => 'Capital Investments',
                                    'rows'  => [array('name'    => 'Directors Accounts')]    
                                   ),
                                   array(
                                    'name'  => 'Withdrawings',
                                    'rows'  => [array('name'    => 'Directors Withdrawings Accounts')]    
                                   )
                                ]
            ),

            // REVENUES
            array(
                'name'        => 'Revenues',      
                'rows'        => [
                                   array(
                                    'name'  => 'Sale Accounts',
                                    'rows'  => [array('name'    => 'Inventory Sale Accounts')]    
                                   ),
                                   array(
                                    'name'  => 'Other Income Accounts',
                                    'rows'  => [array('name'    => 'Other Income Accounts')]    
                                   )
                                ]
            ),

            // EXPENSES
            array(
                'name'        => 'Expenses',      
                'rows'        => [
                                   array(
                                    'name'  => 'Cogs Accounts',
                                    'rows'  => [array('name'    => 'RM & PM Consumption Accounts')]    
                                   ),
                                   array(
                                    'name'  => 'Purchase & Sale Expense',
                                    'rows'  => [array('name'    => 'Purchase & Sale Expense Accounts')]    
                                   ),
                                   array(
                                    'name'  => 'Financial Expenses',
                                    'rows'  => [array('name'    => 'Banks & Governments Fees Expenses')]    
                                   ),
                                   array(
                                    'name'  => 'General & Admin Expenses',
                                    'rows'  => [array('name'    => 'Admin over Head Repairing Expense Accounts')]    
                                   )
                                ]
            ),
        ];

        $this->insert_record($records);
     }
}
