
                    <div class="card-header">
                        <div class="row">
                            
                            <!-- <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_id','Transaction ID')) !!} </br>
                                <span class="cls_label class_transaction_id">{{hp_next_transaction_id()}}</span>
                                {{ Form::hidden('transaction_id', hp_next_transaction_id(), array('id'=>'transaction_id','class' => 'form-control','readonly' => ''  )) }}
                            </div>
                            
                            <div class="col col_head">
                                {!! Html::decode(Form::label('transaction_date','Transaction date')) !!}</br>
                                <span class="cls_label cls_date">{{hp_today()}}</span>
                                {!! Form::hidden('transaction_date', hp_today(), array('id' => 'transaction_date','class' => 'form-control','readonly' => '' )) !!}
                            </div>  -->

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col_head">
                                {!! Html::decode(Form::label('account_name','User Login Branch CIH')) !!}
                                <span class="cls_label cls_account_name">{{ (hp_cash_in_hand()->name) ?? ""}}</span>
                                {!! Form::hidden('account_name', (hp_cash_in_hand()->name) ?? "", array('id' => 'account_name','class' => 'form-control','readonly' => '' )) !!}
                            </div> 


                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col_head">
                                {!! Html::decode(Form::label('cih_balance','User Login Branch CIH')) !!}
                                <span class="cls_label cls_cih_balance"></span>
                                {!! Form::hidden('cih_balance', (hp_cash_in_hand()->current_balance) ?? "", array('id' => 'cih_balance','class' => 'form-control','readonly' => '' )) !!}
                            </div>


                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col_head">
                                {!! Html::decode(Form::label('selected_account_balance','Account balance')) !!}
                                <span class="cls_label cls_selected_account_balance"></span>
                                {!! Form::hidden('selected_account_balance', 0, array('id' => 'selected_account_balance','class' => 'form-control','readonly' => '' )) !!}
                            </div>
                        </div>
                    </div>
               

                                