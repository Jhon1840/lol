<?php

namespace App\Http\Controllers;
use App\Models\vENTA;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        
        
        $ventas = Venta::paginate(10);

        return view('venta.index', compact('ventas'))
            ->with('i', (request()->input('page', 1) - 1) * $ventas->perPage());
        
    }

    public function venta()
    {
        return view('venta\venta');
    }
    public function ppp(ProductController $table, Request $request)
    {
        if ($request->expectsJson()) {
            return $table->getData($request);
        }

        return view('home', compact('table'));
    }
    public function metricas()
    {
        return view('metricas\metricas');
    }
    public function usuarios()
    {
        return view('usuarios\usuarios');
    }

    public function descuentos()
    {
        return view('descuentos\descuentos');
    }
}
