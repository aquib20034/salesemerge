@if((isset($records)) && (count($records)> 0))
    <div class="form-group">
        {!! Html::decode(Form::label('account_type_id','Child head<span class="text-danger">*</span>')) !!}
        {!! Form::select('account_type_id', ['0'=>"--select--"]+$records,[], array('class' => 'form-control cls_child')) !!}
        @if ($errors->has('account_type_id'))  
            {!! "<span class='span_danger'>". $errors->first('account_type_id')."</span>"!!} 
        @endif
    </div>
@endif