<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DetalleVenta;
/**
 * Class VentaController
 * @package App\Http\Controllers
 */
class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::paginate(10);

        return view('venta.index', compact('ventas'))
            ->with('i', (request()->input('page', 1) - 1) * $ventas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    $products = Product::pluck('Nombre', 'id')->all();
    $precios = Product::pluck('Precio_venta', 'id')->all();
    $venta = new Venta();  

    return view('venta.create', compact('products', 'precios', 'venta'));
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'productos' => 'required|array',
    ]);

    // Creación de la venta
    $venta = new Venta();
    $venta->fecha = now();
    $venta->total = 0; // Se calculará a partir de los detalles
    $venta->cliente = 'Cliente por definir'; // Modificar según necesidades
    $venta->save();

    $totalVenta = 0;

    foreach ($request->productos as $prod) {
        $producto = Product::findOrFail($prod['id']);
        $subtotal = $prod['cantidad'] * $producto->Precio_venta;
        $totalVenta += $subtotal;

        // Crear cada detalle de venta
        $detalle = new DetalleVenta();
        $detalle->venta_id = $venta->id;
        $detalle->producto_id = $prod['id'];
        $detalle->cantidad = $prod['cantidad'];
        $detalle->precio_unitario = $producto->Precio_venta;
        $detalle->subtotal = $subtotal;
        $detalle->save();
    }

    // Actualizar el total de la venta
    $venta->total = $totalVenta;
    $venta->save();

    return response()->json(['message' => 'Venta creada correctamente', 'id' => $venta->id]);
}


    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venta = Venta::find($id);

        return view('venta.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $venta = Venta::find($id);

        return view('venta.edit', compact('venta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Venta $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        request()->validate(Venta::$rules);

        $venta->update($request->all());

        return redirect()->route('ventas.index')
            ->with('success', 'Venta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $venta = Venta::find($id)->delete();

        return redirect()->route('ventas.index')
            ->with('success', 'Venta deleted successfully');
    }
}
