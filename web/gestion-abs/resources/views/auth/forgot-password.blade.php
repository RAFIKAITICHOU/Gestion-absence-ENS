<x-guest-layout>
    <div class="d-flex flex-column align-items-start">
    <!-- @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">Mot de passe oublié ?</a>
    @endif -->

    <p class="text-muted small mt-1">
        Si vous êtes un étudiant ou un professeur et que vous ne pouvez pas réinitialiser votre mot de passe, veuillez contacter l’administration.
    </p>
</div>

</x-guest-layout>
