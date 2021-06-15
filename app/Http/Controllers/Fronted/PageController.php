<?php

namespace App\Http\Controllers\Fronted;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home(){
        return view('fronted.home');
    }
    public function profile(){
        $user = Auth::user();
        return view('fronted.profile',compact('user'));
    }
}
