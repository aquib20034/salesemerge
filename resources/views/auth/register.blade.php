@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group form-floating-label">
                            <input id="company_name" name="company_name" type="text" class="form-control input-border-bottom @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus >
                            <label for="company_name" class="placeholder">Company name</label>
                            @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="mobile_no" name="mobile_no" type="text" class="form-control input-border-bottom @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no') }}" required autocomplete="mobile_no"  >
                            <label for="mobile_no" class="placeholder">Company mobile#</label>
                            @error('mobile_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="name" name="name" type="text" class="form-control input-border-bottom @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name"  >
                            <label for="name" class="placeholder">Owner full name</label>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="email" name="email" type="text" class="form-control input-border-bottom @error('email') is-invalid @enderror" value="{{ old('email') }}" required  autocomplete="name">
                            <label for="email" class="placeholder">E-Mail Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="password" type="password"  class="form-control input-border-bottom @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" >
                            <label for="username" class="placeholder">Password</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="password" type="password"  class="form-control input-border-bottom @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                            <label for="username" class="placeholder">Confirm Password</label>
                            @error('password-confirm')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <input id="code" name="code" type="text" class="form-control input-border-bottom @error('code') is-invalid @enderror" value="{{ old('code') }}" required autocomplete="code"  >
                            <label for="code" class="placeholder">Code</label>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-12">
                                <span class="msg">I have already an account </span>
					            <a href="{{ route('login') }}" id="show-signup" class="link">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
