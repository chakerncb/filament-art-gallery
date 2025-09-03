<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitch extends Component
{
    public function changeLocale($locale)
    {
        // Validate the locale
        $availableLocales = ['ar', 'en', 'fr'];
        
        if (in_array($locale, $availableLocales)) {
            // Set the application locale
            App::setLocale($locale);
            
            // Store the locale in session
            Session::put('locale', $locale);
            
            // Refresh the current page to apply the new locale
            return redirect()->to(request()->header('Referer') ?: '/');
        }
    }

    public function render()
    {
        return view('livewire.language-switch');
    }
}
