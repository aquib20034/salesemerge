@extends('layouts.main')
@section('title','Branches')
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
                        <h4 class="card-title">Manage @yield('title')</h4>
                        @can('company-create')
{{--                            <a  href="{{ route('branches.create') }}" class="btn btn-primary btn-round ml-auto">--}}
{{--                            <i class="fa fa-plus"></i> Add new</a>--}}

                            <a  href="#" class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#exampleModalCenter">
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
                                            {{  Form::hidden('company_id', 1 ) }}

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
                                                        {!! Html::decode(Form::label('contact_no','Contact No')) !!}
                                                        {!! Form::text('contact_no', null, array('placeholder' => 'Enter contact no','class' => 'form-control')) !!}
                                                        @if ($errors->has('contact_no'))
                                                            {!! "<span class='span_danger'>". $errors->first('contact_no')."</span>"!!}
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        {!! Html::decode(Form::label('address','Address ')) !!}
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
                                            <button type="button" class="btn btn-primary submit" data-dismiss="modal">Save changes</button>
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
                        <table class="table table-borderless table-striped table-hover ajaxTable datatable datatable-Branch">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th> Branch Name</th>
                                    <th width="10%" >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>

    $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('company-delete')
                deleteButton = DeleteButtonCall("{{ route('branches.massDestroy') }}")
            @endcan
            dtButtons.push(deleteButton)
            let data = [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'name', name: 'name' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ]
            DataTableCall('.datatable-Branch', "{{ route('branches.index') }}", dtButtons, data)
    });

</script>
@endsection
