@extends('layouts.main')
@section('title','Role')
@section('content')
@include( '../sweet_script')
    <div class="page-inner">
        <h4 class="page-title">@yield('title')</h4>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">@yield('title')</h4>
                            <a  href="{{ route('roles.index') }}" class="btn btn-primary btn-xs ml-auto">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <!-- <div class="table-responsive"> -->
                                    <table class="table">
                                        <tr>
                                            <th >Role</th>
                                            <td>{{isset($role->name) ? $role->name : ""}}</td>
                                        </tr>

                                        <tr>
                                            <th>Permissions</th>
                                            <td style="word-break: break-all">
                                            <?php 
                                                    $flag = 0;
                                            ?>
                                                @if(!empty($rolePermissions))
                                                    @foreach($rolePermissions as $v)
                                                        <?php 
                                                          $flag++;
                                                        ?>
                                                        @if($flag > 6)
                                                            <?php 
                                                                $flag=0;
                                                            ?> 
                                                            </br>
                                                        @endif
                                                        <span class="badge badge-success" style="margin:2px;">{{ $v->name }}</span>

                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        
                                      
                                        <br>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
