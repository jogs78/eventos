@component('mail::message')
# Hola! {{$user->nombre}}

Por favor verifica tu cuenta utilizando el siguiente botón:

@component('mail::button', ['url' => route('verify', $user->token_verificacion)])
Confirmar mi cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent