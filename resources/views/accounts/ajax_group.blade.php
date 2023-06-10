@if((isset($records)) && (count($records)> 0))
    <div class="form-group">
        {!! Html::decode(Form::label('group_head_id','Group head<span class="text-danger">*</span>')) !!}
        {!! Form::select('group_head_id', ['0'=>"--select--"]+$records,[], array('class' => 'form-control cls_group')) !!}
        @if ($errors->has('group_head_id'))  
            {!! "<span class='span_danger'>". $errors->first('group_head_id')."</span>"!!} 
        @endif
    </div>
@endif