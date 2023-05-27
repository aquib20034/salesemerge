@extends('layouts.main')
@section('title','Companies')
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
                                <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
                            </div>
                        </div>
                        <div class="col-7 col-md-8">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">

                                <!--begin::Form-->
                                    {!! Form::open(array('route' => 'companies.store','method'=>'POST','id'=>'form','enctype'=>'multipart/form-data')) !!}
                                    {{  Form::hidden('created_by', Auth::user()->id ) }}

                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('name','Company Name <span class="text-danger">*</span>')) !!}
                                                {{ Form::text('name', null, array('placeholder' => 'Enter full company name','class' => 'form-control','autofocus' => ''  )) }}
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
                                                {{ Form::text('code', null, array('placeholder' => 'Enter company code','class' => 'form-control')) }}
                                                @if ($errors->has('code'))
                                                    {!! "<span class='span_danger'>". $errors->first('code')."</span>"!!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('contact_no','Contact No')) !!}
                                                {!! Form::text('contact_no', null, array('placeholder' => 'Enter contact no','class' => 'form-control')) !!}
                                                @if ($errors->has('contact_no'))
                                                    {!! "<span class='span_danger'>". $errors->first('contact_no')."</span>"!!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('previous_amount','Previous Amount')) !!}
                                                {!! Form::number('previous_amount', 0, array('placeholder' => 'Enter previous amount','class' => 'form-control')) !!}
                                                @if ($errors->has('previous_amount'))
                                                    {!! "<span class='span_danger'>". $errors->first('previous_amount')."</span>"!!}
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
                                            <button type="submit" class="btn btn-primary btn-xs mr-2 submit">Save</button>
                                            <button type="reset" class="btn btn-danger btn-xs">Cancel</button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                    <!--end::Form-->
                                </div>



                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
                                    <p>The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didnâ€™t listen. She packed her seven versalia, put her initial into the belt and made herself on the way.
                                    </p>
                                </div>




                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                    <p>Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country.</p>

                                    <p> But nothing the copy said could convince her and so it didnâ€™t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their</p>
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
@endsection
@section('scripts')
    @parent
    <script>
        $(function (){
            $('.submit').on('click', function(e){
                e.preventDefault();
                try {
                   let data = $('#CompaniesForm').serialize();
                   AjaxCall(`{{route('companies.store')}}`, "POST",function (res) { AlertCall(res); $("#form")[0].reset(); }, data);
                }catch (e) {
                   console.log(e)
                }
            })
        })
    </script>
@endsection
