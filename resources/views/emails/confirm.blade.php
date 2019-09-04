@component('mail::message')
# Hola! {{$user->nombre}}

Has cambiado tu correo electrónico. Por favor verificala usando el siguiente botón:

@component('mail::button', ['url' => route('verify', $user->token_verificacion)])
Confirmar mi cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent