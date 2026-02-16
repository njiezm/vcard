<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'admin_code' => 'required|string',
        ]);
        
        $customer = Customer::where('admin_code', $request->admin_code)->first();
        
        if (!$customer) {
            return back()->withErrors([
                'admin_code' => 'Code administrateur invalide.',
            ])->withInput();
        }
        
        // Stocker l'ID du client en session
        Session::put('customer_id', $customer->id);
        Session::put('admin_code', $customer->admin_code);
        
        return redirect()->route('customer.dashboard', [
            'adminCode' => $customer->admin_code,
            'slug' => $customer->slug
        ]);
    }
    
    public function logout()
    {
        Session::forget(['customer_id', 'admin_code']);
        return redirect()->route('login');
    }
}