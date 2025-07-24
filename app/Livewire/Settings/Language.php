<?php

namespace App\Livewire\Settings;   

use Livewire\Component;
use Illuminate\Support\Facades\App;

class Language extends Component
{
    public $lang;

    public function mount()
    {
        $this->lang = App::getLocale();
    }

    public function saveLanguage()
    {
        session(['applocale' => $this->lang]);
        session()->save();
        app()->setLocale($this->lang);
        config(['app.locale' => $this->lang]);
        $this->dispatch('language-updated');
        return redirect()->route('settings.language');
    }

    public function render()
    {
        return view('livewire.settings.language');
    }
}
