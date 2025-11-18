@extends('layouts.dashboard')
@section('breadcrumb', 'Mon Profil')

@section('title', 'Mon Profil')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-4"><i class="bi bi-person-circle me-2"></i> Mon Profil</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!--  Infos utilisateur -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Informations personnelles</div>
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $user->name }}</p>
            <p><strong>Prénom :</strong> {{ $user->prenom }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>

            @if($user->etudiant)
            <hr>
            <p><strong>CNE :</strong> {{ $user->etudiant->cne }}</p>
            <p><strong>Filière :</strong> {{ $user->etudiant->groupe->filiere->nom_filiere ?? '-' }}</p>
            <p><strong>Groupe :</strong> {{ $user->etudiant->groupe->nom_groupe ?? '-' }}</p>
            @endif
        </div>
    </div>

    <!--  Formulaire de changement de mot de passe -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Changer le mot de passe</div>
        <div class="card-body">
            <form action="{{ route('profile.updatePassword') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>
@endsection