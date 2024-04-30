<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DetalleVenta;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;

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

    public function proceedPago(Request $request)
    {
    $carrito = json_decode($request->carrito, true);

    // Renderizar la vista 'venta.prubea' con los datos del carrito
    return view('venta.prubea', compact('carrito'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    try {
        // Validación de los datos de entrada
        $request->validate([
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string',
            'Nombre' => 'required|string',
            'NIT' => 'required|string',
            'CI' => 'nullable|string',
            'total' => 'required|numeric',
            'productos' => 'required|array',
        ]);

        // Creación de la venta
        $venta = new Venta();
        $venta->fecha = $request->fecha;
        $venta->total = $request->total;
        $venta->cliente = $request->Nombre;
        $venta->metodo_pago = $request->metodo_pago;
        $venta->save();

        // Procesamiento de cada producto vendido
        foreach ($request->productos as $prod) {
            $producto = Product::findOrFail($prod['id']);
            $subtotal = $prod['cantidad'] * $producto->Precio_venta;

            // Actualización del stock del producto
            $producto->stock -= $prod['cantidad'];
            $producto->save();

            // Creación del detalle de venta
            $detalle = new DetalleVenta();
            $detalle->venta_id = $venta->id;
            $detalle->producto_id = $prod['id'];
            $detalle->cantidad = $prod['cantidad'];
            $detalle->precio_unitario = $producto->Precio_venta;
            $detalle->subtotal = $subtotal;
            $detalle->save();
        }

        // Generación de la factura y obtención de la URL
        $urlFactura = $this->generarFactura($request, $venta);
        if (!$urlFactura) {
            throw new \Exception("La generación de la factura falló.");
        }

        // Redirección a la URL de la factura
        return redirect($urlFactura);
    } catch (\Exception $e) {
        Log::error('Error en store de VentaController: ' . $e->getMessage());
        return redirect()->route('ventas.create')
            ->with('error', 'Error al crear la venta: ' . $e->getMessage());
    }
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

        return redirect()->route('ventas.create')
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

    
    public function generarFactura(Request $request, Venta $venta)
{
    try {
        Log::info('Iniciando generación de factura para la venta: ' . $venta->id);

        $fecha = Carbon::parse($venta->fecha);
        $invoice = Invoice::make()
            ->buyer(new Party([
                'name' => $venta->cliente,
                'custom_fields' => [
                    'NIT' => $request->NIT,
                    'CI' => $request->CI,
                ],
            ]))
            ->date($fecha)
            ->currencyCode('USD')
            ->currencySymbol('$');

        foreach ($request->productos as $prod) {
            $producto = Product::findOrFail($prod['id']);
            $item = InvoiceItem::make($producto->Nombre)
                ->title($producto->Nombre)
                ->pricePerUnit($producto->Precio_venta)
                ->quantity($prod['cantidad']);
            $invoice->addItem($item);
        }

        $filename = 'invoice-' . $venta->id . '.pdf';
        $invoice->save('public', $filename);
        $url = Storage::disk('public')->url($filename);

        Log::info('Factura generada y guardada: ' . $url);

        return $url; // Retorna la URL para redirección

    } catch (\Exception $e) {
        Log::error('Error al generar la factura: ' . $e->getMessage());
        return null; // Asegúrate de manejar esto adecuadamente en tu método store
    }
}


    


}
