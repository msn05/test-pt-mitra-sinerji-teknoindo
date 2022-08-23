<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\AuthPost;
use App\Exceptions\ErrorException;

class CustomLoginController extends Controller
{
    public function index()
    {
        if (!Auth::check())
            return view('auth.login');
        return redirect()->route('home');
    }

    protected function login(AuthPost $request)
    {
        $validate = $request->validated();
        if (Auth::attempt($validate)) {
            return response()->json([
                'message'   => 'Successfully.!'
            ], 200);
        }
        throw new ErrorException("Data not found.!");
    }

    protected function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}
