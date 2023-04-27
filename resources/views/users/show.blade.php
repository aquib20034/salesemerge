
@extends('layouts.main')
@section('title','User')
@section('content')


<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">@yield('title')</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Show @yield('title')</h4>
                        <a  href="{{ route('users.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">

                            <div class="card-content" style="margin: 1.1rem;
                                border: none;
                                border-radius: 4px;
                                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-2">
                                            <div class="table-responsive">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td >
                                                                <center>
                                                                    <?php if($user->image){?>
                                                                        <img src="{{ asset('/uploads/users/'.$user->image) }}" alt="users view avatar" class="users-avatar-shadow rounded-circle" style= " box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" height="64" width="64">
                                                                    <?php }else{?>
                                                                        <img src="{{ asset('/uploads/users/no_image.png') }}" alt="users view avatar" class="users-avatar-shadow rounded-circle"  style= " box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);"height="64" width="64">
                                                                    <?php }?>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><a  style="align-items: center;" class="btn btn-primary btn btn-block" href="{{ route('users.index') }}"> <i class="fas fa-arrow-left"></i> Back</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><a  style="align-items: center;" class="btn btn-success btn btn-block" href="{{ route('users.edit',$user->id) }}" > <i class="fas fa-pencil-alt"></i> Edit </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <td> {{$user->name}}</td>
                                                    </tr>
                                                  
                                                    <tr>
                                                        <th>Email </th>
                                                        <td>{{$user->email}} </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact No: </th>
                                                        <td>{{$user->contact_no}} </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Role</th>
                                                        <td> {{$user->rn}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Description</th>
                                                        <td> {{$user->description}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created At</th>
                                                        <td> {{$user->created_at}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Update At</th>
                                                        <td>{{$user->updated_at}} </td>
                                                    </tr>
                                                </table>
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
    </div>
@endsection
