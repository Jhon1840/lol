<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use Illuminate\Http\Request;
use App\Models\Product;

class DescuentoController extends Controller
{
    public function index()
    {
        $descuentos = Descuento::paginate(10);
        $products = Product::all(); // Obtener todos los productos

        return view('descuento.index', compact('descuentos', 'products'))
            ->with('i', (request()->input('page', 1) - 1) * $descuentos->perPage());
    }

    public function create()
    {
        $products = Product::all(); // Obtener todos los productos

        return view('descuento.create', compact('products'));
    }

    public function store(Request $request)
    {
        request()->validate(Descuento::$rules);

        $descuento = Descuento::create($request->all());

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento created successfully.');
    }

    public function show($id)
    {
        $descuento = Descuento::find($id);

        return view('descuento.show', compact('descuento'));
    }

    public function edit($id)
    {
        $descuento = Descuento::find($id);

        return view('descuento.edit', compact('descuento'));
    }

    public function update(Request $request, Descuento $descuento)
    {
        request()->validate(Descuento::$rules);

        $descuento->update($request->all());

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento updated successfully');
    }

    public function destroy($id)
    {
        $descuento = Descuento::find($id)->delete();

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento deleted successfully');
    }
}
