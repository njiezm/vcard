<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;

class WelcomeVCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param Customer $customer
     * @param string $password
     */
    public function __construct(Customer $customer, $password = null)
    {
        $this->customer = $customer;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('🎉 Votre vCard est prête ! - ' . config('app.name'))
            ->markdown('emails.welcome-vcard')
            ->with([
                'vcardUrl' => route('vcard.show', $this->customer->slug),
                'adminUrl' => route('customer.dashboard', [
                    'slug' => $this->customer->slug
                ]),
                'adminCode' => $this->customer->admin_code,
                'email' => $this->customer->email ?? 'votre email',
                'password' => $this->password ?? 'votre mot de passe'
            ]);
    }
}