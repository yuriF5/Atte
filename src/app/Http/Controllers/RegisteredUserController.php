<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
public function index()
{
    $work_time_id = 1; // ここに適切な work_time_id の値を設定する

    return view('index', ['work_time_id' => $work_time_id]);
}

    public function store(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/register')->with('message','会員登録が完了しました');
    }
}

