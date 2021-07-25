<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function test(){
        return success("Successfully",'Testing API Magic Pay');
    }
    public function profile(){
        $user = auth()->user();
        $data = new ProfileResource($user);
        return success('message',$data);
    }
}
