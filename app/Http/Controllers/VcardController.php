<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class VcardController extends Controller
{
    public function show($slug)
    {
        $customer = Customer::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment views
        $customer->incrementViews();

        return view('vcard.show', compact('customer'));
    }

    public function download($slug)
    {
        $customer = Customer::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $customer->incrementDownloads();

        $vcard = $customer->generateVCard();
        
        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="' . $customer->slug . '.vcf"');
    }
}