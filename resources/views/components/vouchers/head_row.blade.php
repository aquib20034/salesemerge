        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            
                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_id','Transaction ID')) !!} </br>
                                <span class="cls_label class_transaction_id">{{hp_next_transaction_id()}}</span>
                                {{ Form::hidden('transaction_id', hp_next_transaction_id(), array('id'=>'transaction_id','class' => 'form-control','readonly' => ''  )) }}
                            </div>
                            
                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_date','Transaction date')) !!}</br>
                                <span class="cls_label cls_date">{{hp_today()}}</span>
                                {!! Form::hidden('transaction_date', hp_today(), array('id' => 'transaction_date','class' => 'form-control','readonly' => '' )) !!}
                            </div> 

                            <div class="col col_head">
                                {!! Html::decode(Form::label('account_name','User Login Branch CIH')) !!}</br>
                                <span class="cls_label cls_account_name">{{ (hp_cash_in_hand()->name) ?? ""}}</span>
                                {!! Form::hidden('account_name', (hp_cash_in_hand()->name) ?? "", array('id' => 'account_name','class' => 'form-control','readonly' => '' )) !!}
                            </div> 


                            <div class="col col_head">
                                {!! Html::decode(Form::label('account_balance','Account balance')) !!}</br>
                                <span class="cls_label cls_current_balance">33333</span>
                                {!! Form::hidden('account_balance', (hp_cash_in_hand()->current_balance) ?? "", array('id' => 'account_balance','class' => 'form-control','readonly' => '' )) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


                                