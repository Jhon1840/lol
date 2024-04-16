<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Product;
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
        $venta = new Venta();
        return view('venta.create', compact('venta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validacion de datos
    $request->validate([
        'fecha' => 'required|date',
        'total' => 'required|numeric',
        'cliente' => 'nullable|string|max:100',
        'producto' => 'required|integer|exists:products,id',
        'cantidad' => 'required|integer|min:1',
    ]);

    // Creacion de nueva venta
    $venta = new Venta();
    $venta->fecha = $request->input('fecha');
    $venta->total = $request->input('total');
    $venta->cliente = $request->input('cliente');
    $venta->save();

    // Crear el detalle de venta para el producto seleccionado
    $ventaDetalle = new Venta();
    $ventaDetalle->venta_id = $venta->id;
    $ventaDetalle->producto_id = $request->input('producto');
    $ventaDetalle->cantidad = $request->input('cantidad');
    // Obtiene el precio unitario
    $producto = Product::findOrFail($request->input('producto'));
    $ventaDetalle->precio_unitario = $producto->Precio_venta;
    $ventaDetalle->save();

    return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
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
