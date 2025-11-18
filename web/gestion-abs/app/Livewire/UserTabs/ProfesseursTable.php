<?php

namespace App\Livewire\UserTabs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Professeur;

class ProfesseursTable extends Component
{
    use WithPagination;

    public $searchProf = ''; 
    protected $paginationTheme = 'bootstrap';

    public function updatingSearchProf()
    {
        $this->resetPage();
    }

    public function render()
    {
        $professeurs = Professeur::with('user')
            ->whereHas('user', fn($q) => $q
                ->where('name', 'like', "%{$this->searchProf}%")
                ->orWhere('prenom', 'like', "%{$this->searchProf}%")
                ->orWhere('email', 'like', "%{$this->searchProf}%"))
            ->paginate(5);

        return view('livewire.user-tabs.professeurs-table', compact('professeurs'));
    }
}
