<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DetalleVenta;
use App\Models\Descuento;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;
use App\Models\Caja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DineroCaja;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $cajaActual = Caja::latest()->first(); 
    
        $cajaAbierta = $cajaActual ? $cajaActual->estado == 'caja abierta' : false;
    
        return view('venta.index', compact('ventas', 'cajaActual', 'cajaAbierta'))
            ->with('i', (request()->input('page', 1) - 1) * $ventas->perPage());
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    $products = Product::pluck('Nombre', 'id');
    $preciosOriginales = Product::pluck('Precio_venta', 'id');
    $venta = new Venta();
    $cajaActual = Caja::where('estado', 1)->first(); // Asumiendo que el modelo Caja tiene un campo 'estado'
    
    // Determinar si la caja está abierta o no
    $cajaAbierta = !is_null($cajaActual); // true si cajaActual no es null, false de lo contrario

    // Obtener los descuentos aplicables
    $descuentos = Descuento::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->get()
        ->keyBy('product_id');

    // Calcular los precios con descuento
    $precios = [];
    foreach ($preciosOriginales as $id => $precioOriginal) {
        if (isset($descuentos[$id])) {
            $descuento = $descuentos[$id];
            $precioConDescuento = $precioOriginal - ($precioOriginal * ($descuento->discount_percentage / 100));
            $precios[$id] = $precioConDescuento;
        } else {
            $precios[$id] = $precioOriginal;
        }
    }

    // Pasar tanto cajaActual como cajaAbierta a la vista junto con los otros datos
    return view('venta.create', compact('products', 'precios', 'venta', 'cajaActual', 'cajaAbierta'));
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
            // Validación de los datos recibidos en el formulario
            $request->validate([
                'fecha' => 'required|date',
                'metodo_pago' => 'required|string',
                'Nombre' => 'required|string',
                'NIT' => 'required|string',
                'CI' => 'nullable|string',
                'total' => 'required|numeric',
                'productos' => 'required|array',
                'cambio' => 'nullable|numeric|required_if:metodo_pago,efectivo',
            ]);
    
            try {
                $caja = Caja::findOrFail($request->input('caja_id'));
            } catch (ModelNotFoundException $e) {
                return back()->withErrors(['error' => 'No se encontró una caja abierta con el ID especificado.']);
            }
    
            // Creación de la venta
            $venta = new Venta();
            $venta->fecha = $request->fecha;
            $venta->total = $request->total;
            $venta->cliente = $request->Nombre;
            $venta->metodo_pago = $request->metodo_pago;
            $venta->vendedor = $request->user() ? $request->user()->name : null;
            $venta->caja_id = $request->caja_id;
            $venta->save();
    
            // Actualizar la caja
            $dineroEnCaja = $request->total;
            if ($request->metodo_pago === 'efectivo' && $request->cambio) {
                $dineroEnCaja -= $request->cambio;
            }
            $caja->dinero += $dineroEnCaja;
            $caja->save();
    
            foreach ($request->productos as $prod) {
                $producto = Product::findOrFail($prod['id']);
                $subtotal = $prod['cantidad'] * $producto->Precio_venta;
    
                // Actualizar el stock del producto
                $producto->stock -= $prod['cantidad'];
                $producto->save();
    
                // Crear cada detalle de venta
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->producto_id = $prod['id'];
                $detalle->cantidad = $prod['cantidad'];
                $detalle->precio_unitario = $producto->Precio_venta;
                $detalle->subtotal = $subtotal;
                $detalle->save();
            }
    
            if (!is_null($request->cambio)) {
                $venta->cambio = $request->cambio;
                $venta->save();
            }
    
            return redirect()->route('ventas.create')->with('success', 'Venta creada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('ventas.create')->with('error', 'Error al crear la venta: ' . $e->getMessage());
        }
    }
    
    private function guardarPagos($detalleId, $pagos, $tipo)
    {
        foreach ($pagos as $denominacion => $cantidad) {
            if ($cantidad > 0) {
                DB::table('pagos_venta')->insert([
                    'venta_detalle_id' => $detalleId,
                    'tipo_pago' => $tipo,
                    'valor' => $denominacion,
                    'cantidad' => $cantidad,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
    


    public function cancelarVenta($id)
    {
        try {
            // Encontrar la venta por su ID
            $venta = Venta::findOrFail($id);
    
            // Verificar si la venta ya está cancelada
            if ($venta->estado === 'cancelado') {
                return redirect()->route('ventas.index')->with('error', 'La venta ya está cancelada.');
            }
    
            // Revertir los detalles de la venta
            $detalles = $venta->ventaDetalles;
            foreach ($detalles as $detalle) {
                // Devolver los productos al stock original
                $producto = Product::findOrFail($detalle->producto_id);
                $producto->stock += $detalle->cantidad;
                $producto->save();
            }
    
    
            $venta->estado = 'cancelado';
            $venta->save();
    
            return redirect()->route('ventas.index')->with('success', 'Venta cancelada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('ventas.index')->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }
    
public function cancelar(Request $request)
{
    try {
        // Crear la nueva venta con los datos proporcionados
        $venta = new Venta();
        $venta->fecha = now();  // Usamos la fecha actual del servidor
        $venta->total = 0;  // Puedes ajustar este valor según lo que necesites
        $venta->estado = $request->input('estado', 'Cancelado');  // Estado por defecto es 'Cancelado'
        $venta->cliente = null;  // No se recibe cliente desde el formulario, asigna null
        $venta->metodo_pago = null;  // No se recibe método de pago desde el formulario, asigna null
        $venta->vendedor = $request->user() ? $request->user()->name : null;  // Guarda el nombre del vendedor si el usuario está autenticado
        $venta->save();

        // Redirigir a la creación de venta con mensaje de confirmación
        return redirect()->route('ventas.create')->with('info', 'Venta cancelada correctamente y guardada en el registro.');

    } catch (\Exception $e) {
        // En caso de error, redirigir a la creación de venta con mensaje de error
        return redirect()->route('ventas.create')->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
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
            
            // Crear el objeto del comprador con datos del cliente
            $buyer = new Party([
                'name' => $venta->cliente,
                'custom_fields' => [
                    'NIT' => $request->NIT,
                    'CI' => $request->CI,
                ],
            ]);
    
            $user = auth()->user();
    
            $seller = new Party([
                'name' => $user->name,
                'Nro vendedor ' . $user->id,
            ]);
    
            $fecha = Carbon::parse($venta->fecha);
    
            $invoice = Invoice::make()
                ->buyer($buyer)
                ->seller($seller)
                ->date($fecha)
                ->currencySymbol('BS')
                ->currencyCode('Bolivianos')
                ->taxRate(13);
    
            foreach ($request->productos as $prod) {
                $producto = Product::findOrFail($prod['id']);
                $item = InvoiceItem::make($producto->Nombre)
                    ->title($producto->Nombre)
                    ->pricePerUnit($producto->Precio_venta)
                    ->quantity($prod['cantidad']);

                $invoice->addItem($item);
            }

            $invoice->filename($user->name . '' . $venta->cliente.''.$request->CI);
            $invoice->save('public'); 
    
            $link = $invoice->url();
            //$invoice ->stream();
            return $link;
    
        } catch (\Exception $e) {
            Log::error('Error al generar la factura: ' . $e->getMessage());
            throw new \Exception("No se pudo generar la factura.". $e->getMessage());
        }
    }

    public function toggleCaja(Request $request)
{
    try {
        // Asegurarse de que un usuario está autenticado antes de proceder
        if (!auth()->check()) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no autenticado.'], 401);
        }

        // Obtener los datos del usuario autenticado
        $usuario = auth()->user();
        $nombreVendedor = $usuario->name;
        $idVendedor = $usuario->id;

        $cajaExistente = Caja::where('id_vendedor', $idVendedor)
                            ->where('estado', 'caja abierta')
                            ->first();
        if ($cajaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una caja abierta para este vendedor.',
                'cajaId' => $cajaExistente->id 
            ]);
        }

        $dineroInicial = 0;

        $nuevaCaja = new Caja;
        $nuevaCaja->estado = 'caja abierta';
        $nuevaCaja->nombre_vendedor = $nombreVendedor;
        $nuevaCaja->id_vendedor = $idVendedor;
        $nuevaCaja->dinero = $dineroInicial;
        $nuevaCaja->fecha = now(); // Establece la fecha actual del servidor
        $nuevaCaja->save();
        $cajaId = $nuevaCaja->id;
        return response()->json([
            'success' => true,
            'message' => 'Caja abierta correctamente y registrada al vendedor autenticado.',
            'cajaId' => $cajaId // Devuelve el ID de la nueva caja abierta
        ]);
    } catch (\Exception $e) {
        Log::error('Error al cambiar el estado de la caja: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Error trágico al cambiar el estado de la caja.'], 500);
    }
}


public function cerrarCaja(Request $request)
{
    $request->validate([
        'caja_id' => 'required|integer|exists:caja,id',
        'dinero_en_caja' => 'required|numeric',
        'total_billetes_monedas' => 'required|numeric',
        'observaciones' => 'nullable|string'
    ]);

    try {
        $caja = Caja::findOrFail($request->caja_id);
        $caja->estado = 'caja cerrada';
        $caja->dinero_final = $request->total_billetes_monedas;
        $caja->observaciones = $request->observaciones;
        $caja->save();

        // Guardar billetes y monedas en la tabla dinero_caja
        $billetes = $request->input('billetes', []);
        $monedas = $request->input('monedas', []);

        foreach ($billetes as $denominacion => $cantidad) {
            if ($cantidad > 0) {
                DineroCaja::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'billete',
                    'denominacion' => $denominacion,
                    'cantidad' => $cantidad,
                ]);
            }
        }

        foreach ($monedas as $denominacion => $cantidad) {
            if ($cantidad > 0) {
                DineroCaja::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'moneda',
                    'denominacion' => $denominacion,
                    'cantidad' => $cantidad,
                ]);
            }
        }

        return response()->json(['success' => true, 'redirect' => route('ventas.index')]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al cerrar la caja: ' . $e->getMessage()]);
    }
}

public function getDineroEnCaja($id)
{
    $caja = Caja::find($id);
    if ($caja) {
        return response()->json(['success' => true, 'dinero' => $caja->dinero]);
    } else {
        return response()->json(['success' => false, 'message' => 'Caja no encontrada']);
    }
}


}
