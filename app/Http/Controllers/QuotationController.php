<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotes = Quote::orderByDesc('created_at')
            ->with([
                'contact',
                'items' => function ($query) {
                    $query->with('product');
                },
            ])
            ->select('quotes.*')
            ->selectTotal()
            ->paginate(15);
        
        return inertia('Quotes/Index', compact('quotes'));
    }

    public function create()
    {
        $products = Product::all();
        $leads = Lead::all();

        return inertia('Quotes/Create', compact('products', 'leads'));
    }

    public function store(CreateQuoteRequest $request)
    {
        $form = $request->only([
            'folio',
            'user_id',
            'client_id',
            'lead_id',
            'status',
            'items',
        ]);

        $itemsJson = $request->only(['items']);

        $items = json_decode($itemsJson['items']);

        DB::transaction(function () use ($form, $items) {
            $quote = Quote::create($form);
            
            foreach ($items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
        });

        return response(['message' => 'Registrado correctamente']);
    }

    public function edit(Quote $quote)
    {
        return inertia('Quotes/Edit', compact('product'));
    }

    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'price' => 'required',
            'slug' => 'nullable',
            'manage_stock' => 'required',
        ]);

        $form = $request->only([
            'name',
            'description',
            'status',
            'price',
            'slug',
            'manage_stock',
        ]);

        $quote->update($form);

        return response(['message' => 'Actualizado correctamente']);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return response(['message' => 'Eliminado correctamente']);
    }
}
