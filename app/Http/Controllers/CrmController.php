<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Lead;

class CrmController extends Controller
{
    public function index()
    {
        $leads = Lead::orderByDesc('created_at')->get();
        $columns = Column::all();

        $boards = Board::where('name', 'prospects')
            ->with([
                'columns' => function ($query) {
                    $query->with('leads');
                }
            ])
            ->get();

        return inertia('Crm/Index', compact('leads', 'boards', 'columns'));
    }
}
