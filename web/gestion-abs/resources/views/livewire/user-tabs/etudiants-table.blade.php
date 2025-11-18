<div>
<input type="text" wire:model.debounce.300ms="searchEtud" class="form-control mb-3" placeholder="🔍 Rechercher...">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>CNE</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($etudiants as $etudiant)
            <tr>
                <td>{{ $etudiant->nom }}</td>
                <td>{{ $etudiant->prenom }}</td>
                <td>{{ $etudiant->user->cne }}</td>
                <td>{{ $etudiant->user->email }}</td>
                <td>
                    <a href="{{ route('etudiants.edit', $etudiant->user->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                    <form action="{{ route('etudiants.destroy', $etudiant->user->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $etudiants->links() }}
</div>
