<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function usuario(Request $request){      

        if (Auth::attempt(['name' => $request->name,'password' => $request->password,'estado' => 1])){
            return redirect()->route('main');
        }

        return back()
        ->withErrors([
            'name' => trans('auth.failed'),
            'password' => trans('auth.password')
        ])
        ->withInput(request(['name','password']));

    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
