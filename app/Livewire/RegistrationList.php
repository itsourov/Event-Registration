<?php

namespace App\Livewire;

use App\Enums\RegistrationStatuses;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrationList extends Component
{
    use WithPagination;

    public $activeTab = RegistrationStatuses::PAID->value, $search;
    protected $queryString = [
        'activeTab',
        'search' => ['except' => ''],

    ];



    public function render()
    {


        return view('livewire.registration-list',[
            'registrations' => Registration::where('status', '=', $this->activeTab)->
            where('name', 'like', "%$this->search%")->paginate(18),
        ]);
    }
}
