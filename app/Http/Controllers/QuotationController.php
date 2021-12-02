<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Quote;
use App\Models\Product;
use App\Models\QuoteItem;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Support\Facades\PDF;

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
            ->paginate(2); 
        
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
        ]);

        $itemsJson = $request->only(['items']);

        $items = json_decode($itemsJson['items']);

        DB::transaction(function () use ($form, $items) {
            $quote = Quote::create($form);
            
            foreach ($items as $item) {
                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'unity' => $item->unity,
                    'tax_amount' => $item->tax_amount,
                    'total' => $item->quantity * $item->price + ($item->price * $item->quantity * ($item->tax_amount / 100)),
                ]);
            }
        });

        return response(['message' => 'Registrado correctamente']);
    }

    public function edit($id)
    {
        $products = Product::all();
        $leads = Lead::all();
        $quote = Quote::with([
                'contact',
                'items' => function ($query) {
                    $query->with('product');
                },
            ])
            ->find($id);

        return inertia('Quotes/Edit', compact(
            'quote',
            'products',
            'leads',
        ));
    }

    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        $form = $request->only([
            'folio',
            'user_id',
            'client_id',
            'lead_id',
            'status',
        ]);

        $itemsJson = $request->only(['items']);

        $items = collect(json_decode($itemsJson['items']));

        DB::transaction(function () use ($form, $items, $quote) {
            $quote->update($form);

            QuoteItem::whereNotIn('id', $items->pluck('id'))
                ->where('quote_id', $quote->id)
                ->delete();
            
            foreach ($items as $item) {
                $itemData = [
                    'quote_id' => $quote->id,
                    'product_id' => $item->product_id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'unity' => $item->unity,
                    'tax_amount' => $item->tax_amount,
                    'total' => $item->quantity * $item->price + ($item->price * $item->quantity * ($item->tax_amount / 100)),
                ];
                
                $quoteItem = QuoteItem::where('product_id', $item->product_id)
                    ->where('quote_id', $quote->id)
                    ->first();

                if ($quoteItem) {
                    $quoteItem->update($itemData);
                } else {
                    QuoteItem::Create($itemData);
                }
            }
        });

        return response(['message' => 'Actualizado correctamente']);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return response(['message' => 'Eliminado correctamente']);
    }

    public function printQuote($id)
    {
        $quote = Quote::with([
            'contact',
            'items',
        ])
        ->find($id);

        $total = 0;
        $taxes = 0;

        foreach ($quote->items as $item) {
            $total += $item->total;
            $taxes += ($item->tax_amount / 100) * $item->price * $item->quantity;
        }

        $pdf = PDF::loadView('pdf.modern-quote', compact('quote', 'total', 'taxes'));

        return $pdf->stream();
    }
}
