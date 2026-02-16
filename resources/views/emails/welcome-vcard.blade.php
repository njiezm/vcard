@component('mail::message')

<div style="text-align: center; margin-bottom: 30px;">
<div style="background: linear-gradient(135deg, #6366f1, #4f46e5); width: 60px; height: 60px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);">
<span style="color: white; font-size: 30px;">📇</span>
</div>
<h1 style="color: #0f172a; font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; margin: 0;">Félicitations, {{ $customer->firstname }} !</h1>
<p style="color: #64748b; font-size: 16px; margin-top: 5px;">Votre vCard professionnelle est prête à être partagée.</p>
</div>

📱 Votre Profil Public

Votre carte de visite numérique est accessible instantanément via ce lien unique.

@component('mail::button', ['url' => $vcardUrl, 'color' => 'success'])
Voir ma vCard
@endcomponent

⚙️ Espace d'Administration

Personnalisez vos informations, changez votre photo et suivez vos statistiques en temps réel.

Lien d'accès : [{{ $adminUrl }}]({{ $adminUrl }})

Vos identifiants sécurisés :

Identifiant : {{ $customer->slug }}

Code d'accès : <span style="background-color: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-weight: bold; color: #6366f1;">{{ $adminCode }}</span>

📚 Guide de démarrage rapide

1️⃣ Complétez votre profil : Ajoutez vos réseaux sociaux (Instagram, LinkedIn, TikTok) pour maximiser votre visibilité.
2️⃣ Photo de qualité : Une photo professionnelle augmente le taux d'enregistrement de votre contact de 80%.
3️⃣ Signature Email : Ajoutez votre lien vCard en bas de vos e-mails pour une signature moderne.

💡 Conseils de Pro

✅ Actualisation : Mettez à jour vos coordonnées dès qu'elles changent.

✅ QR Code : Utilisez la fonction QR Code intégrée pour vos rencontres physiques.

✅ Stats : Surveillez le nombre de vues pour mesurer votre impact réseau.

🆘 Un problème ?

Notre équipe est là pour vous accompagner. Répondez simplement à cet e-mail ou consultez notre FAQ.

Cordialement,

L'équipe vCard System [vcard-system.io]({{ config('app.url') }})
@endcomponent