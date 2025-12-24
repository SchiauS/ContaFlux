<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GlobalViewController extends Controller
{
    /**
     * Afișează pagina de companii din Global View.
     */
    public function companies(): View
    {
        return view('global.companies');
    }

    /**
     * Afișează pagina de utilizatori din Global View.
     */
    public function users(): View
    {
        return view('global.users');
    }

    /**
     * Afișează pagina de rapoarte din Global View.
     */
    public function reports(): View
    {
        return view('global.reports');
    }
}
