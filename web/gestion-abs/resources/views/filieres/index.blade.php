@extends('layouts.dashboard')

@section('title', 'Liste des Filières')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Liste des Filières</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Nom</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($filieres as $filiere)
            <tr>
                <td>{{ $filiere->nom_filiere }}</td>
                <td>{{ $filiere->description }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2">Aucune filière enregistrée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection