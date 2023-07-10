<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidOrderException extends Exception
{

    public function render(Request $request)
    {
        return redirect()
            ->route('front.home')
            ->withInput()
            ->withErrors([
                // it's will take message that sent via InvalidOrderException
                'message' => $this->getMessage(),
            ])
            ->with('info', $this->getMessage());
    }
}
