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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (get_class($exception) == 'Swift_TransportException') {
            if ($request->is('api/*')) {
                    return response()->json(['error' => "Can't send email", 'status'  => intval(500)], 200);
                } else {
                    return redirect()->back()->withInput()->withErrors(["error" => "Can't send email"]);
                }
           
        }

        if($exception->getMessage() == 'stream_copy_to_stream(): read of 8192 bytes failed with errno=21 Is a directory'){
            if ($request->is('api/*')) {
                    return response()->json(['error' => "File size is too large. Please upload less file image size.", 'status'  => intval(500)], 200);
                } else {
                    return redirect()->back()->withInput()->withErrors(["error" => "File size is too large. Please upload less file image size.", "send" => "fail"]);
                }
           
        }
        return parent::render($request, $exception);
    }
}
