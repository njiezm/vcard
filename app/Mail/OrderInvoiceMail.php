<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
{
    // Génération du lien sécurisé
    $invoiceUrl = route('invoice.public', [
        'order' => $this->order->id,
        'hash'  => sha1($this->order->email . $this->order->id)
    ]);

    return $this->subject('Votre facture - DigitCard - njiezm.fr')
                ->view('emails.invoice')
                ->with([
                    'invoiceUrl' => $invoiceUrl
                ]);
}
}