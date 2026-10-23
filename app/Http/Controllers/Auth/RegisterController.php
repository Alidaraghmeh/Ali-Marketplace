<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\hash;

class RegisterController extends Controller
{
    


   public function create(){

     return view('auth.register');

   }


   public function store(RegisterRequest $request){

       $data=$request->validated();

$data['password'] = Hash::make($data['password']);
        
     $user=  User::create($data);
      
      Auth::login($user);
      $request->session()->regenerate();
      return 
      redirect('/')
      ->with('success','Regisrtation succssful');
   }
}
