<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index ($id) {
        if($id) {
            $user = User::find($id);
            return $user;
        } 
    } 
}
