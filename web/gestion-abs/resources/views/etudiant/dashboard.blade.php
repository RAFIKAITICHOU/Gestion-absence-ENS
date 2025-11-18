@extends('layouts.etudiantMenu')

@section('breadcrumb', 'Tableau de bord')

@section('content')
<div class="page-title mb-4">
    <h2>Bienvenue de nouveau , <strong>{{ Auth::user()->prenom }} {{ Auth::user()->name }}</strong> ! 👋</h2>
</div>

<div class="col-12 col-lg-6">
    <h4 class="page-title">Tableau de bord</h4>
</div>

<!--  Carte contenant le graphique -->
<div class="card mt-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Mes absences par matière</h5>

        @if(isset($chart) && $chart->labels)
        {!! $chart->container() !!}
        @else
        <p class="text-muted">Aucune donnée à afficher.</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($chart))
{!! $chart->script() !!}
@endif
@endsection