@extends('layouts.main')
@section('title','Roles')
@section('content')

@include( '../sweet_script')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Edit @yield('title')</h4>
                        <a  href="{{ route('roles.index') }}" class="btn btn-primary btn-xs ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::model($role, ['method' => 'PATCH','id'=>'form','route' => ['roles.update', $role->id]]) !!}
                    {{  Form::hidden('updated_by', Auth::user()->id ) }}

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                              {!! Html::decode(Form::label('name','Role Name <span class="text-danger">*</span>')) !!}
                               {{ Form::text('name', null, array('placeholder' => 'role name','class' => 'form-control','autofocus' => ''  )) }}
                                @if ($errors->has('name'))  
                                    {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!} 
                                @endif
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div style="width: 100%; padding-left: -10px; ">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-separate table-head-custom dt-responsive" style="width: 100%;" cellspacing="0">
                                            <tr>
                                                <th> <label> Role Name</label></th>
                                                <th>List/ Show</th>
                                                <th>Create/ Store</th>
                                                <th>Edit/ Update</th>
                                                <th>Delete</th>
                                            </tr>
                                            <?php   $i=0;
                                                $val = $permission[0]['name'];
                                                $explodedFirstValue = explode("-", $val);
                                                $firstVal = $explodedFirstValue[0];  // exploded permission name
                                                ?>
                                            <tr>
                                                <td> <label>{{ ucfirst($firstVal)}}</label></td>

                                                <?php
                                                    foreach($permission as $value){
                                                        $currentVal = $value->name;
                                                        $explodedLastValue = explode("-", $currentVal);
                                                        $LastVal = $explodedLastValue[0];
                                                        if( $firstVal == $LastVal){
                                                ?>
                                                <td>
                                                    <!-- <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-success">
                                                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                             <span></span>  
                                                        </label>
                                                    </div> -->
                                                    <span class="switch switch-sm switch-icon switch-success">
                                                        <label> 
                                                            <!-- {{  $currentVal}} -->
                                                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'form-control', 'data-toggle'=>'toggle', 'data-onstyle'=>'success', 'data-style' => 'btn-round')) }}
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </td>
                                                <?php }else{
                                                    $firstVal = $LastVal;
                                                ?>
                                            </tr>
                                            <tr>
                                                <td> <label>{{ucfirst($firstVal)}}</label></td>
                                                <td>
                                                     <!-- <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-success">
                                                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                            <span></span>  
                                                        </label>
                                                    </div> -->
                                                    <span class="switch switch-sm switch-icon switch-success">
                                                        <label>
                                                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'form-control', 'data-toggle'=>'toggle', 'data-onstyle'=>'success', 'data-style' => 'btn-round')) }}
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </td>

                                                
                                                <?php 
                                                    // if ($LastVal == 'profile')
                                                    //     { echo "<td> </td><td> </td><td></td>";}
                                                    ?>
                                                <?php }} ?>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            
                            <div class="col-lg-12 text-right">
                                <button type="submit" class="btn btn-primary btn-xs mr-2">Save</button>
                                <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end::Form-->
            </div>
        </div>
    </div>
@endsection
