@component('mail::message')
# Réinitialisation de votre mot de passe

Bonjour **{{ $user->prenom }} {{ $user->nom }}**,

Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte administrateur sur la plateforme **GED - Ministère des Affaires Culturelles**.

@component('mail::button', ['url' => $url, 'color' => 'success'])
🔄 Réinitialiser mon mot de passe
@endcomponent

Ce lien de réinitialisation expirera dans **{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes**.

Si vous n'avez pas demandé la réinitialisation de votre mot de passe, veuillez ignorer cet email. Aucune action n'est requise.

### Pour votre sécurité :
- Ne partagez jamais ce lien avec personne
- Ce lien ne peut être utilisé qu'une seule fois
- Si vous rencontrez des problèmes, contactez l'administrateur système

Cordialement,<br>
**L'équipe GED**<br>
Ministère des Affaires Culturelles, Tunisie

---

@component('mail::subcopy')
Si vous avez des difficultés à cliquer sur le bouton "Réinitialiser mon mot de passe", copiez et collez l'URL suivante dans votre navigateur :
[{{ $url }}]({{ $url }})
@endcomponent
@endcomponent
