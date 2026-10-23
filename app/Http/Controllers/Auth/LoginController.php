<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
public function showLogin(){

    return view('auth.login');
}

public function login(Request $request){

   $credentials=$request->validate(
    [
      'email'=>'required|email|max:255',
       'password'=>'required|string'
    ]
   );

   if(Auth::attempt($credentials))
     {
        $request->session()->regenerate();

      return redirect()->intended('/')->with('success','login successful');

     }



    return back()
    ->withErrors([
        'email' => 'Invalid email or password'
    ])
    ->onlyInput('email');


}
public function logout(Request $request){

   Auth::logout();
   $request->session()->invalidate();
    $request->session()->regenerateToken();


    return redirect()->route('login');


}
}
