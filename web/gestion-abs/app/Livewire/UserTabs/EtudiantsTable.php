<?php

namespace App\Livewire\UserTabs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Etudiant;

class EtudiantsTable extends Component
{
    use WithPagination;

    public $searchEtud = ''; // nom unique pour éviter les conflits
    protected $paginationTheme = 'bootstrap';

    public function updatingSearchEtud()
    {
        $this->resetPage();
    }

    public function render()
    {
        $etudiants = Etudiant::with('user')
            ->whereHas('user', fn($q) => $q
                ->where('name', 'like', "%{$this->searchEtud}%")
                ->orWhere('prenom', 'like', "%{$this->searchEtud}%")
                ->orWhere('email', 'like', "%{$this->searchEtud}%")
                ->orWhere('cne', 'like', "%{$this->searchEtud}%"))
            ->paginate(5);

        return view('livewire.user-tabs.etudiants-table', compact('etudiants'));
    }
}
    