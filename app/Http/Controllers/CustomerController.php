<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Dashboard
    public function dashboard($slug)
    {
        // Vérifie si l'utilisateur est connecté et que le slug correspond
        if (!session()->has('customer_auth') || session('customer_slug') !== $slug) {
            return redirect()->route('customer.login', ['slug' => $slug])
                             ->with('error', 'Vous devez vous connecter.');
        }

        $adminCode = session('customer_admin_code');

        $customer = Customer::where('admin_code', $adminCode)
                            ->where('slug', $slug)
                            ->firstOrFail();

        return view('customer.dashboard', compact('customer'));
    }

    // Formulaire de login
    public function showLogin($slug)
    {
        return view('customer.login', compact('slug'));
    }

   public function login(Request $request, $slug)
{
    
    $request->validate([
        'admin_code' => 'required|string',
    ]);

    $customer = Customer::where('slug', $slug)
        ->where('admin_code', $request->admin_code)
        ->first();

    if (!$customer) {
        return back()->with('error', 'Slug ou code incorrect.');
    }

    // Régénère l'ID de session pour éviter les conflits
    session()->regenerate();
    
    // Stocke les infos dans la session
    session([
        'customer_auth' => true,
        'customer_slug' => $customer->slug,
        'customer_admin_code' => $customer->admin_code,
    ]);


    return redirect()->route('customer.dashboard', ['slug' => $customer->slug]);
}
    // Déconnexion
    public function logout($slug)
    {
        // Supprime toutes les sessions du client
        session()->forget([
            'customer_auth',
            'customer_slug',
            'customer_admin_code'
        ]);

        return redirect()->route('customer.login', ['slug' => $slug]);
    }

    // Mise à jour du profil
    public function update(Request $request, $slug)
    {
        $customer = Customer::where('slug', $slug)->firstOrFail();

        $request->validate([
            'profession' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'phone_secondary' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['photo', 'delete_photo']);

        // Upload photo
        if ($request->hasFile('photo')) {
            $customer->uploadPhoto($request->file('photo'));
        }

        // Suppression photo
        if ($request->has('delete_photo') && $request->delete_photo) {
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
                $customer->photo = null;
            }
        }

        $customer->update($data);

        return redirect()->back()
            ->with('success', 'Informations mises à jour avec succès!');
    }
}
