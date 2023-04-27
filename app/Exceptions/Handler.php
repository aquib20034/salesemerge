<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function render($request, Throwable $exception)
    {
        // dd($request);
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return redirect()->back()->with('permission','User have not permission for this page access.');
          
        }
        // return view('home');  
        return parent::render($request, $exception);
    }
    public function register()
    {
        //
    }
}
