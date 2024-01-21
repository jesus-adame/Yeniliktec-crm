<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Board;
use App\Models\Column;
use App\Models\Product;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function index()
    {
        $leads = Lead::orderByDesc('created_at')->get();
        $columns = Column::where('slug', '!=', 'lost')->get();
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

    public function createQuote($id)
    {
        $products = Product::all();
        $leads = Lead::all();

        return inertia('Crm/CreateQuote', compact('products', 'leads', 'id'));
    }

    public function createAppointment($id)
    {
        $products = Product::all();
        $leads = Lead::all();

        return inertia('Crm/CreateAppointment', compact('id'));
    }

    public function changeLeadFase(Request $request, Lead $lead)
    {
        $request->validate([
            'column_id' => 'required',
        ]);

        $lead->update($request->only(['column_id']));

        return response()->json(['message' => 'Actualizado correctamente']);
    }
}
