<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class RegisteredUserController extends Controller
{
    public function index(){
        return view('index');
    }

}
