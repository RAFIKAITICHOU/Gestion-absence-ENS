@extends('layouts.dashboard')

@section('content')
<div class="container">
  <h2 class="mb-4">Ajouter une absence</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('absences.store') }}">
    @csrf

    <div class="mb-3">
      <label for="etudiant_id" class="form-label">Étudiant</label>
      <select name="etudiant_id" class="form-select">
        @foreach($etudiants as $etudiant)
          <option value="{{ $etudiant->id }}">{{ $etudiant->nom }} {{ $etudiant->prenom }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Date d'absence</label>
      <input type="date" name="date" class="form-control">
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="justifie" class="form-check-input" id="justifie">
      <label class="form-check-label" for="justifie">Justifiée</label>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </form>
</div>
@endsection
