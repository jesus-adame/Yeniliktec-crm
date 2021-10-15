<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderByDesc('created_at')->paginate(10);

        return inertia('Products/Index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unities = Unity::getUnities();

        return inertia('Products/Create', compact('unities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'price' => 'required',
            'unity' => 'required',
            'slug' => 'nullable',
            'manage_stock' => 'required',
            'tax_amount' => 'nullable',
        ]);

        $form = $request->only([
            'name',
            'description',
            'status',
            'price',
            'unity',
            'slug',
            'manage_stock',
            'tax_amount',
        ]);

        Product::create($form);

        return response(['message' => 'Registrado correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $unities = Unity::getUnities();

        return inertia('Products/Edit', compact('product', 'unities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'price' => 'required',
            'unity' => 'required',
            'slug' => 'nullable',
            'manage_stock' => 'required',
            'tax_amount' => 'nullable',
        ]);

        $form = $request->only([
            'name',
            'description',
            'status',
            'price',
            'unity',
            'slug',
            'manage_stock',
            'tax_amount',
        ]);

        $product->update($form);

        return response(['message' => 'Actualizado correctamente']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->quote_items()->count()) {
            abort(401, 'No se puede borrar este producto ya que usa en cotizaciones');
        }
        
        $product->delete();

        return response(['message' => 'Eliminado correctamente']);
    }
}
