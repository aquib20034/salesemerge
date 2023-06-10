@if((isset($records)) && (count($records)> 0))
    <div class="form-group">
        {!! Html::decode(Form::label('child_head_id','Child head<span class="text-danger">*</span>')) !!}
        {!! Form::select('child_head_id', ['0'=>"--select--"]+$records,[], array('class' => 'form-control cls_child')) !!}
        @if ($errors->has('child_head_id'))  
            {!! "<span class='span_danger'>". $errors->first('child_head_id')."</span>"!!} 
        @endif
    </div>
@endif