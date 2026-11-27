<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $section = $request->query('section', 'rapor-semester');

        return app(RaporController::class)->renderSection($request, $section);
    }
}
