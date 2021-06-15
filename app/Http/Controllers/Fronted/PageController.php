<?php

namespace App\Http\Controllers\Fronted;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    public function home()
    {
        return view('fronted.home');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('fronted.profile', compact('user'));
    }
    public function updatePassword()
    {
        return view('fronted.update_password');
    }
    public function updatePasswordStore(ChangePasswordRequest $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $current_user = Auth::guard('web')->user();
        $user =  User::where('id', $current_user->id);
        ## check old password
        if (Hash::check($old_password, $current_user->password)) {
            $user->update(['password'=>Hash::make($new_password)]);
            return redirect()->route('profile')->with('info','Password change successfully !');
        } else {
            return redirect()->back()->withErrors(['old_password'=>'လက်ရှိစကား၀ှက်မှားနေပါသည်။'])->withInput();
        }
    }
}
