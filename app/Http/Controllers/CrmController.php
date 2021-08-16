<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Lead;
use App\Models\User;

class CrmController extends Controller
{
    public function index()
    {
        $leads = Lead::orderByDesc('created_at')->get();
        $columns = Column::all();
        $users = User::all();

        $boards = Board::where('slug', 'prospects')
            ->with([
                'columns' => function ($query) {
                    $query->with('leads');
                }
            ])
            ->get();

        return inertia('Crm/Index', compact(
            'leads',
            'boards',
            'columns',
            'users',
        ));
    }
}
