<div>
    <input type="text" wire:model.debounce.300ms="search" class="form-control mb-2" placeholder="🔍 Rechercher...">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($professeurs as $prof)
            <tr>
                <td>{{ $prof->user->name }}</td>
                <td>{{ $prof->user->prenom }}</td>
                <td>{{ $prof->user->email }}</td>
                <td>
                    <a href="{{ route('professeurs.edit', $prof->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                    <form action="{{ route('professeurs.destroy', $prof->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $professeurs->links() }}
</div>
