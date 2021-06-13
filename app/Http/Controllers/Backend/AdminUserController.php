<?php

namespace App\Http\Controllers\Backend;

use App\AdminUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
class AdminUserController extends Controller
{
    public function index(){
        return view('backend.admin_user.index');
    }

    public function ssd(){
        $data = AdminUser::query();
        return Datatables::of($data)->make(true);
    }
}
