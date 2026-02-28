@php
    // Détection de la langue et de la monnaie
    $currency = $order->currency ?? 'EUR';
    $isEnglish = $currency !== 'EUR';
    $formattedAmount = number_format($order->amount, 2, $isEnglish ? '.' : ',', $isEnglish ? ',' : ' ');
@endphp

<!DOCTYPE html>
<html lang="{{ $isEnglish ? 'en' : 'fr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $isEnglish ? 'Invoice' : 'Facture' }} {{ $invoiceNumber }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #f8fafc;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .header h2 {
            margin: 0;
            color: #4f46e5;
        }
        .box {
            border: 1px solid #e2e8f0;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            background: #f1f5f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table th, table td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #6366f1;
            color: #fff;
        }
        .total {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <div>
            <h2>{{ $isEnglish ? 'INVOICE' : 'FACTURE' }}</h2>
            <p><strong>{{ $isEnglish ? 'Number' : 'Numéro' }} :</strong> {{ $invoiceNumber }}</p>
            <p><strong>{{ $isEnglish ? 'Date' : 'Date' }} :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        </div>

        <div style="text-align:right;">
            <strong>njiezm.fr</strong><br>
            {{ $isEnglish ? 'Sole Proprietorship' : 'Entreprise Individuelle' }}<br>
            Email : njie@njiezm.fr<br>
            SIRET : XXXXXXXX<br>
        </div>
    </div>

    <div class="box">
        <strong>{{ $isEnglish ? 'Billed to' : 'Facturé à' }} :</strong><br>
        {{ $order->full_name }}<br>
        {{ $order->email }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ $isEnglish ? 'Description' : 'Description' }}</th>
                <th>{{ $isEnglish ? 'Quantity' : 'Quantité' }}</th>
                <th>{{ $isEnglish ? 'Unit Price' : 'Prix Unitaire' }}</th>
                <th>{{ $isEnglish ? 'Total' : 'Total' }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DigitCard - {{ $isEnglish ? 'Digital Business Card' : 'Carte digitale professionnelle' }}</td>
                <td>1</td>
                <td>{{ $formattedAmount }} {{ $currency }}</td>
                <td>{{ $formattedAmount }} {{ $currency }}</td>
            </tr>
        </tbody>
    </table>

    <p class="total">
        {{ $isEnglish ? 'Total Due' : 'Total à payer' }} : {{ $formattedAmount }} {{ $currency }}
    </p>

    <div class="footer">
        @if($currency === 'EUR')
            TVA non applicable – article 293 B du CGI<br>
        @else
            VAT not applicable – article 293 B of the French Tax Code<br>
        @endif
        {{ $isEnglish ? 'Invoice generated automatically.' : 'Facture générée automatiquement.' }}
    </div>

</div>
</body>
</html>