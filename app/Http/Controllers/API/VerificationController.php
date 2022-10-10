<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller {

    use VerifiesEmails;

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('login')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}