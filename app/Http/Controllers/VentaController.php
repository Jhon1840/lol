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
use App\Models\Caja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $precios = Product::pluck('Precio_venta', 'id');
        $venta = new Venta();
        $cajaActual = Caja::where('estado', 1)->first(); // Asumiendo que el modelo Caja tiene un campo 'estado'
    
        // Determinar si la caja está abierta o no
        $cajaAbierta = !is_null($cajaActual); // true si cajaActual no es null, false de lo contrario
    
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
        //dd($request->all());
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
                'billetes.*' => 'nullable|integer|min:0',
                'monedas.*' => 'nullable|integer|min:0',
                //'caja_id' => 'required|integer|exists:cajas,id' 
            ]);
    
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
            $caja = Caja::findOrFail($venta->caja_id);
            $caja->dinero += $venta->total; 
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
    
                // Guardar billetes y monedas si el método de pago es efectivo
                if ($request->metodo_pago === 'efectivo') {
                    if (!empty($request->billetes)) {
                        $this->guardarPagos($detalle->id, $request->billetes, 'billete');
                    }
                    if (!empty($request->monedas)) {
                        $this->guardarPagos($detalle->id, $request->monedas, 'moneda');
                    }
                }
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
    try {
        $cajaId = $request->input('caja_id');
        if (!$cajaId) {
            return response()->json(['success' => false, 'message' => 'ID de caja no proporcionado.']);
        }

        $caja = Caja::findOrFail($cajaId);

        if ($caja->estado != 'caja abierta') {
            return response()->json(['success' => false, 'message' => 'La caja no está abierta.']);
        }

        $caja->estado = 'caja cerrada';
        $caja->save();

        return response()->json(['success' => true, 'message' => 'Caja cerrada correctamente.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al cerrar la caja: ' . $e->getMessage()]);
    }
}

}
