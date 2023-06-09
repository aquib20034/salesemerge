@extends('layouts.main')
@section('title','Companies')
@section('content')
    @include( '../sweet_script')
    <style>
        #spinner-div{
            width:100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0,0,0,0.2);
            z-index:9999;
            display:none;
        }
    </style>
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">@yield('title')</h4>
        </div>

        <div id= "spinner-div"><i class="fas fa-spinner fa-spin" style="position:absolute; left:50%; top:50%;font-size:80px; color:#3a7ae0"></i> </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Company Setup</h4>
                            <a  href="{{ route('companies.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="nav flex-column nav-pills nav-secondary" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Branches</a>
                                    <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">General Settings</a>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <!--begin::Form-->
                                        {!! Form::model($aCountries, ['method' => 'PATCH','id'=>'CompaniesForm','enctype'=>'multipart/form-data','route' => ['companies.update',  Auth::user()->company_id]]) !!}
                                        {{  Form::hidden('created_by', Auth::user()->id ) }}
                                        {{  Form::hidden('company_id', Auth::user()->company_id, array('class' => 'company_id')) }}

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('name','Company Name <span class="text-danger">*</span>')) !!}
                                                    {{ Form::text('name', null, array('placeholder' => 'Enter full company name','class' => 'form-control', 'required'=>'true', 'readonly'=>'true'  )) }}
                                                    @if ($errors->has('name'))
                                                        {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('owner_name','Owner Name  <span class="text-danger">*</span>')) !!}
                                                    {{ Form::text('owner_name', null, array('placeholder' => 'Enter owner name','class' => 'form-control')) }}
                                                    @if ($errors->has('owner_name'))
                                                        {!! "<span class='span_danger'>". $errors->first('owner_name')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('code','Company code  <span class="text-danger">*</span>')) !!}
                                                    {{ Form::text('code', null, array('placeholder' => 'Enter company code','class' => 'form-control' )) }}
                                                    @if ($errors->has('code'))
                                                        {!! "<span class='span_danger'>". $errors->first('code')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('mobile_no','Mobile#')) !!}
                                                    {!! Form::text('mobile_no', null, array('placeholder' => 'Enter mobile#','class' => 'form-control')) !!}
                                                    @if ($errors->has('mobile_no'))
                                                        {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('phone_no','Phone#')) !!}
                                                    {!! Form::number('phone_no', null, array('placeholder' => 'Enter phone#','class' => 'form-control')) !!}
                                                    @if ($errors->has('phone_no'))
                                                        {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    {!! Html::decode(Form::label('address','Address ')) !!}
                                                    {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>1, 'class' => 'form-control')) !!}
                                                    @if ($errors->has('address'))
                                                        {!! "<span class='span_danger'>". $errors->first('address')."</span>"!!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12 text-right">
                                                <button type="submit" class="btn btn-primary btn-xs mr-2 upate_company">Save</button>
                                                <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                        <!--end::Form-->
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center">
                                                    <h4 class="card-title">Manage @yield('title')</h4>
                                                    @can('branch-create')
                                                        <a  href="#" class="btn btn-primary btn-xs ml-auto" data-toggle="modal" data-target="#exampleModalCenter">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLongTitle">Add new branch</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!--begin::Form-->
                                                                        {!! Form::open(array('route' => 'branches.store','method'=>'POST','id'=>'form_branch','enctype'=>'multipart/form-data')) !!}
                                                                        {{  Form::hidden('created_by', Auth::user()->id ) }}
                                                                        {{  Form::hidden('company_id', Auth::user()->company_id ) }}

                                                                        <div class=" row">
                                                                            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                                <div class="form-group">
                                                                                    {!! Html::decode(Form::label('name','Branch name <span class="text-danger">*</span>')) !!}
                                                                                    {{ Form::text('name', null, array('placeholder' => 'Enter full branch name','class' => 'form-control','autofocus' => ''  )) }}
                                                                                    @if ($errors->has('name'))
                                                                                        {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                                <div class="form-group">
                                                                                    {!! Html::decode(Form::label('mobile_no','Mobile#')) !!}
                                                                                    {!! Form::text('mobile_no', null, array('placeholder' => 'Enter mobile#','class' => 'form-control')) !!}
                                                                                    @if ($errors->has('mobile_no'))
                                                                                        {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                        </div><div class="row">
                                                                            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                                <div class="form-group">
                                                                                    {!! Html::decode(Form::label('phone_no','Phone#')) !!}
                                                                                    {!! Form::text('phone_no', null, array('placeholder' => 'Enter phone#','class' => 'form-control')) !!}
                                                                                    @if ($errors->has('phone_no'))
                                                                                        {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="form-group">
                                                                                    {!! Html::decode(Form::label('address','Address')) !!}
                                                                                    {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>1, 'class' => 'form-control')) !!}
                                                                                    @if ($errors->has('address'))
                                                                                        {!! "<span class='span_danger'>". $errors->first('address')."</span>"!!}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary submit_branches" data-dismiss="modal">Save changes</button>
                                                                    </div>
                                                                    {!! Form::close() !!}
                                                                    <!--end::Form-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body">
                                                    <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Branch" style="width:98% !important;">
                                                        <thead>
                                                            <tr>
                                                                <th> Branch Name</th>
                                                                <th> Mobile #</th>
                                                                <th> Phone #</th>
                                                                <th> Address </th>
                                                                <th >Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                        <h3>General Setting </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\CompanyRequest', '#CompaniesForm'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\BranchRequest', '#form_branch'); !!}

    {{--    TODO Ajax requst creating a problem after using Request validation. multi time ajax request hiting continously--}}
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('company-delete')
                deleteButton = DeleteButtonCall("{{ route('branches.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                // { data: 'placeholder', name: 'placeholder' },
                { data: 'name', name: 'name' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'phone_no', name: 'phone_no' },
                { data: 'address', name: 'address' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ]
            DataTableCall('.datatable-Branch', "{{ route('branches.index') }}", dtButtons, data)
        });

        $(function (){
            $('.submit_branches').on('click', function(e){
                e.preventDefault();
                try {
                    let data = $('#form_branch').serialize();
                    AjaxCall(`{{route('branches.store')}}`, "POST", function (res) {
                        AlertCall(res, $('.datatable-Branch').DataTable().ajax.reload());
                        $("#form_branch")[0].reset();
                    }, data);
                }catch (e) {
                    console.log(e)
                }
            })
        })
    </script>
@endsection

