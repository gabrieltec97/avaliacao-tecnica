<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Component;

class Contacts extends Component
{
    public $searchTerm = '';
    public $contacts;
    public function render()
    {
       $this->contacts = Contact::where('name', 'like', '%'.$this->searchTerm.'%')
           ->orWhere('email','like', '%'.$this->searchTerm.'%')
           ->get();
        return view('livewire.contacts');
    }
}
