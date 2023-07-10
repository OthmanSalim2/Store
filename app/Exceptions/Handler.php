<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    // here write the name of input in order to prevent passed in $request->all()
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */

    // here if I need make handel for exception by specific way me
    public function register(): void
    {
        // possible use to handel error in log file of laravel and possible specific my log file
        // if I need store the message in log file by specific way me
        $this->reportable(function (QueryException $e) {
            // Log::debug($e->getMessage()) it use for tracking thing mean possible instead of display in page I can make store in log file
            if ($e->getCode() == 23000) {
                //channel() for determine log channel that need work on it, and to use sql log file special me
                Log::channel('sql')->warning($e->getMessage());
            }
        });

        // QueryException use with foreign key, ValidationException use with handel for errors of validation process
        $this->renderable(function (QueryException $e, Request $request) {
            if ($e->getCode() == 23000) {
                $message = 'Foreign key constraint fail entry';
            } else {
                $message = $e->getMessage();
            }

            // here I expect to request return json
            if ($request->expectsJson($message)) {
                return response()->json([
                    'message' => $message,
                ], 400);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'message' => $e->getMessage(),
                ])
                ->with('info', $message);
        });
    }
}
