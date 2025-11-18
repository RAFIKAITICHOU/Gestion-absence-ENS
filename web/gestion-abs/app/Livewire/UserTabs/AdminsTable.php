<?php

namespace App\Livewire\UserTabs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Administrateur;

class AdminsTable extends Component
{
    use WithPagination;

    public $searchAdmin = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearchAdmin()
    {
        $this->resetPage();
    }

    public function render()
    {
        $admins = Administrateur::with('user')
            ->whereHas('user', fn($q) => $q
                ->where('name', 'like', "%{$this->searchAdmin}%")
                ->orWhere('prenom', 'like', "%{$this->searchAdmin}%")
                ->orWhere('email', 'like', "%{$this->searchAdmin}%"))
            ->paginate(5);

        return view('livewire.user-tabs.admins-table', compact('admins'));
    }
}

