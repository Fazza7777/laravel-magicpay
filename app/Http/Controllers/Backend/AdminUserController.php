<?php

namespace App\Http\Controllers\Backend;

use App\AdminUser;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminUserRequest;
use App\Http\Requests\AdminUserEditRequest;

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
    public function edit(Request $request,$id){
        $admin_user = AdminUser::findOrFail($id);
        return view('backend.admin_user.edit',compact('admin_user')) ;
    }
    public function update(AdminUserEditRequest $request,$id){

        $admin_user = AdminUser::findOrFail($id);
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        ##
       //   $admin_user->password = $request->password ? Hash::make($request->password) : $admin_user->password;
        if($request->has('password')){
            $admin_user->password = Hash::make($request->password);
        }
        $admin_user->update();
        return redirect()->back()->with('update','Update successfully!');

    }
    public function destroy($id){
        $admin_user = AdminUser::findOrFail($id);
        $admin_user->delete();
        return 'success';
    }
    ## Datatable Ajax
    public function ssd(){
        $data = AdminUser::query();
        ##edit
        return Datatables::of($data)->addColumn('action',function($each){
            $edit_icon = '<a href="'.route('admin.admin-user.edit',$each->id).'" class="text-success"><i class="fas fa-user-edit"></i></a>';
            $delete_icon =  '<a href="#" data-id="'.$each->id.'" class="text-danger delete"><i class="fas fa-trash-alt"></i></a>';
            return "<div class='action-icon'>".$edit_icon.$delete_icon."</div>";
        })->make(true);
        ## simple get data
            //return Datatables::of($data)->make(true);

        ## edit from database data to ui show
            // return Datatables::of($data)->editColumn('name',function($each){
            //     return $each->name.' -- ha ha';
            // })->make(true);
    }
}
