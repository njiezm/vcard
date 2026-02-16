<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.login'); // resources/views/admin/login.blade.php
    }

    
}
