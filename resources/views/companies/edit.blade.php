@extends('layouts.main')
@section('title','Company')
@section('content')
    @include( '../sweet_script')
    <div class="page-inner">
        <div class="page-header">
           <!-- <h4 class="page-title">@yield('title')</h4> -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">@yield('title') Setup</h4>
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
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center">
                                                    <h4 class="card-title">Your @yield('title')</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- begin::Form -->
                                        {!! Form::model($data, ['method' => 'PATCH','id'=>'CompaniesForm','enctype'=>'multipart/form-data','route' => ['companies.update',  $data->id]]) !!}
                                            {{  Form::hidden('created_by', Auth::user()->id ) }}
                                            {{  Form::hidden('company_id', Auth::user()->company_id, array('class' => 'company_id')) }}
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        {!! Html::decode(Form::label('name','Company name <span class="text-danger">*</span>')) !!}
                                                        {{ Form::text('name', null, array('placeholder' => 'Enter full company name','class' => 'form-control', 'required'=>'true', 'readonly'=>'true'  )) }}
                                                        @if ($errors->has('name'))
                                                            {!! "<span class='span_danger'>". $errors->first('name')."</span>"!!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        {!! Html::decode(Form::label('owner_name','Owner name  <span class="text-danger">*</span>')) !!}
                                                        {{ Form::text('owner_name', null, array('placeholder' => 'Enter owner name','class' => 'form-control', 'required'=>'true')) }}
                                                        @if ($errors->has('owner_name'))
                                                            {!! "<span class='span_danger'>". $errors->first('owner_name')."</span>"!!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        {!! Html::decode(Form::label('code','Company code  <span class="text-danger">*</span>')) !!}
                                                        {{ Form::text('code', null, array('placeholder' => 'Enter company code','class' => 'form-control', 'required'=>'true' )) }}
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
                                                        {!! Html::decode(Form::label('phone_no','Phone No')) !!}
                                                        {!! Form::number('phone_no', null, array('placeholder' => 'Enter Phone No','class' => 'form-control')) !!}
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
                                        <!--start::Branch add Form-->
                                        @can('branch-create')
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="card-title">Manage Branches</h4>
                                                        <a  href="#" class="btn btn-primary btn-xs ml-auto" data-toggle="modal" data-target="#BranchAdd">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <!-- Modal -->
                                            <div class="modal fade" id="BranchAdd" tabindex="-1" role="dialog" aria-labelledby="BranchUpdateTitle" aria-hidden="true">
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
                                                                        {!! Html::decode(Form::label('name','Branch Name <span class="text-danger">*</span>')) !!}
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
                                                                        {!! Form::text('mobile_no', null, array('placeholder' => 'Enter Mobile No','class' => 'form-control')) !!}
                                                                        @if ($errors->has('mobile_no'))
                                                                            {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div><div class="row">
                                                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('phone_no','Phone No')) !!}
                                                                        {!! Form::text('phone_no', null, array('placeholder' => 'Enter Phone no','class' => 'form-control')) !!}
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
                                                            <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary btn-xs submit_branches" data-dismiss="modal">Save changes</button>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        <!--end::Form-->
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                        <!--end::Branch add Form-->

                                        <!--start::Branch Edit Form-->
                                        @can('branch-edit')
                                            <!-- Modal update -->
                                            <div class="modal fade" id="BranchUpdate" tabindex="-1" role="dialog" aria-labelledby="BranchUpdateTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Update branch</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!--begin::Form-->
                                                            {!! Form::open(array('method'=>'POST','id'=>'form_branch_update','enctype'=>'multipart/form-data')) !!}
                                                            {{  Form::hidden('id', '', array('id' => 'id_ubranch')) }}

                                                            <div class=" row">
                                                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('name','Branch Name <span class="text-danger">*</span>')) !!}
                                                                        {{ Form::text('name', null, array('placeholder' => 'Enter full branch name','class' => 'form-control','autofocus' => '', 'id' => 'name_ubranch')) }}
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
                                                                        {!! Form::text('mobile_no', null, array('placeholder' => 'Enter Mobile No','class' => 'form-control', 'id' => 'mobile_no_ubranch')) !!}
                                                                        @if ($errors->has('mobile_no'))
                                                                            {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div><div class="row">
                                                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('phone_no','Phone No')) !!}
                                                                        {!! Form::text('phone_no', null, array('placeholder' => 'Enter Phone no','class' => 'form-control', 'id' => 'phone_no_ubranch')) !!}
                                                                        @if ($errors->has('phone_no'))
                                                                            {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('address','Address ')) !!}
                                                                        {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>1, 'class' => 'form-control', 'id' => 'address_ubranch')) !!}
                                                                        @if ($errors->has('address'))
                                                                            {!! "<span class='span_danger'>". $errors->first('address')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary btn-xs submit_ubranches" data-dismiss="modal">Update changes</button>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        <!--end::Form-->
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                        <!--end::Branch Edit Form-->

                                        <!--start::Branch View Form-->
                                        @can('branch-list')
                                            <!-- Modal update -->
                                            <div class="modal fade" id="BranchView" tabindex="-1" role="dialog" aria-labelledby="BranchViewTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">View branch</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!--begin::Form-->
                                                            {!! Form::open(array('method'=>'POST','id'=>'form_branch_update','enctype'=>'multipart/form-data')) !!}
                                                            {{  Form::hidden('id', '', array('id' => 'id_ubranch')) }}

                                                            <div class=" row">
                                                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('name','Branch Name <span class="text-danger">*</span>')) !!}
                                                                        {{ Form::text('name', null, array('placeholder' => 'Enter full branch name','class' => 'form-control','autofocus' => '', 'id' => 'name_vbranch', 'readonly'=>'true')) }}
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
                                                                        {!! Form::text('mobile_no', null, array('placeholder' => 'Enter Mobile No','class' => 'form-control', 'id' => 'mobile_no_vbranch', 'readonly'=>'true')) !!}
                                                                        @if ($errors->has('mobile_no'))
                                                                            {!! "<span class='span_danger'>". $errors->first('mobile_no')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div><div class="row">
                                                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('phone_no','Phone No')) !!}
                                                                        {!! Form::text('phone_no', null, array('placeholder' => 'Enter Phone no','class' => 'form-control', 'id' => 'phone_no_vbranch', 'readonly'=>'true')) !!}
                                                                        @if ($errors->has('phone_no'))
                                                                            {!! "<span class='span_danger'>". $errors->first('phone_no')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        {!! Html::decode(Form::label('address','Address ')) !!}
                                                                        {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>1, 'class' => 'form-control', 'id' => 'address_vbranch', 'readonly'=>'true')) !!}
                                                                        @if ($errors->has('address'))
                                                                            {!! "<span class='span_danger'>". $errors->first('address')."</span>"!!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
                                                        </div>
                                                        {!! Form::close() !!}
                                                        <!--end::Form-->
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                        <!--end::Branch View Form-->
                                        <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Branch" style="width:98% !important;">
                                            <thead>
                                            <tr>
                                                <th> Branch Name</th>
                                                <th> Mobile #</th>
                                                <th> Phone #</th>
                                                <th> Address </th>
                                                <th style="width: 5%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
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
    {!! JsValidator::formRequest('App\Http\Requests\CompanyRequest', '#form_branch'); !!}

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
                { data: 'actions', name: '{{ trans('global.actions') }}', bSortable: false }
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


        const FormFillUp = function (data) {

            let response =  data.data;
            console.log(response)
            $('#id_ubranch').val(response.id)
            $('#name_ubranch').val(response.name)
            $('#mobile_no_ubranch').val(response.mobile_no)
            $('#phone_no_ubranch').val(response.phone_no)
            $('#address_ubranch').val(response.address)

        }
        function GetBranch(id){
            data = {id: id}
            AjaxCall(`{{route('branches.edit', ':id')}}`, "GET", FormFillUp, data, id);
        }

        $(function (){
            $('.submit_ubranches').on('click', function(e){
                e.preventDefault();
                try {
                    let data = $('#form_branch_update').serialize();
                    let id = $('#id_ubranch').val();

                    AjaxCall(`{{route('branches.update', ':id')}}`, "PATCH", function (res) {
                        AlertCall(res, $('.datatable-Branch').DataTable().ajax.reload());
                        $("#form_branch_update")[0].reset();
                    }, data, id);
                }catch (e) {
                    console.log(e)
                }
            })
        })



        const ViewFormFillUp = function (data) {

            let response =  data.data;
            console.log(response)
            $('#id_vbranch').val(response.id)
            $('#name_vbranch').val(response.name)
            $('#mobile_no_vbranch').val(response.mobile_no)
            $('#phone_no_vbranch').val(response.phone_no)
            $('#address_vbranch').val(response.address)

        }

        function ViewBranch(id){
            data = {id: id}
            AjaxCall(`{{route('branches.edit', ':id')}}`, "GET", ViewFormFillUp, data, id);
        }

    </script>
@endsection

