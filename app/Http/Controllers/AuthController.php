<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login(){
        return view ('Pages.Login.login');
    }
    function signup(){
        return view ('Pages.Signup.signup');
    }
    function resetpassword(){
        return view ('Pages.ResetPassword.resetpassword');
    }
}
