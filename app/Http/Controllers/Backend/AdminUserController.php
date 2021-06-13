<?php

namespace App\Http\Controllers\Backend;

use App\AdminUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
class AdminUserController extends Controller
{
    public function index(){
        return view('backend.admin_user.index');
    }
    public function create(){
        return view('backend.admin_user.create');
    }
    public function store(AdminUserRequest $request){
        $admin_user = new AdminUser();
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = Hash::make($request->password);
        $admin_user->save();
        return redirect()->back()->with('create','Create successfully!');

    }
    ## Datatable Ajax
    public function ssd(){
        $data = AdminUser::query();
        return Datatables::of($data)->make(true);
    }
}
