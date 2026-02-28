@php
    $currency = $order->currency ?? 'EUR';
    $isEnglish = $currency !== 'EUR';
    $formattedAmount = number_format($order->amount, 2, $isEnglish ? '.' : ',', $isEnglish ? ',' : ' ');
@endphp

<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color:#1e293b; line-height:1.6; max-width:600px; margin:0 auto; padding:20px; background:#f8fafc; border-radius:12px;">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:30px;">
        <div style="width:60px; height:60px; background:linear-gradient(135deg, #6366f1, #4f46e5); border-radius:18px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:15px; box-shadow:0 10px 15px rgba(0,0,0,0.1);">
            <span style="font-size:28px; color:white;">🧾</span>
        </div>
        <h2 style="margin:0; font-size:22px; color:#0f172a;">{{ $isEnglish ? 'Invoice - njiezm.fr' : 'Facture - njiezm.fr' }}</h2>
    </div>

    {{-- Salutation --}}
    <p style="font-size:16px; margin-bottom:15px;">{{ $isEnglish ? 'Hello' : 'Bonjour' }} <strong>{{ $order->full_name }}</strong>,</p>
    <p style="font-size:16px; margin-bottom:25px;">{{ $isEnglish ? 'Thank you for purchasing the product' : 'Merci pour votre achat du produit' }} <strong>DigitCard</strong>.</p>

    <hr style="border:none; border-top:1px solid #e2e8f0; margin:20px 0;">

    {{-- Détails de la commande --}}
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:25px;">
        <p style="margin:8px 0;"><strong>{{ $isEnglish ? 'Amount' : 'Montant' }} :</strong> {{ $formattedAmount }} {{ $currency }}</p>
        <p style="margin:8px 0;"><strong>{{ $isEnglish ? 'Date' : 'Date' }} :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    <hr style="border:none; border-top:1px solid #e2e8f0; margin:20px 0;">

    {{-- Footer infos --}}
    <p style="margin:5px 0;"><strong>{{ $isEnglish ? 'Company' : 'Entreprise' }} :</strong> njiezm.fr</p>
    <p style="margin:5px 0;"><strong>Email :</strong> contact@njiezm.fr</p>

    <p style="margin-top:20px; font-size:16px;">{{ $isEnglish ? 'Thank you for your trust.' : 'Merci pour votre confiance.' }}</p>

    {{-- Bouton --}}
    <p style="text-align:center; margin-top:30px;">
        <a href="{{ $invoiceUrl }}" target="_blank" 
           style="display:inline-block; padding:12px 25px; background:linear-gradient(135deg, #6366f1, #4f46e5); color:#fff; text-decoration:none; font-weight:600; border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.1); transition:all 0.3s;">
            {{ $isEnglish ? 'View Invoice' : 'Voir ma facture' }}
        </a>
    </p>

</div>