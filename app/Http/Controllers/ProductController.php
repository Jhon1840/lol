<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan; // Agregar esta línea
use App\Models\Product;
use Illuminate\Support\Facades\DB;
/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   

     public function index()
     {
         // Paginación de productos
         $products = Product::paginate(10);
 
         // Consulta para obtener los productos más vendidos
         $productosMasVendidos = Product::join('venta_detalles', 'venta_detalles.producto_id', '=', 'products.id')
             ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
             ->groupBy('venta_detalles.producto_id')
             ->orderBy('total_vendido', 'desc')
             ->get();
 
         // Retorna a la vista con ambas colecciones de datos
         return view('product.index', compact('products', 'productosMasVendidos'))
             ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
     }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        return view('product.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    request()->validate(Product::$rules);

    $product = new Product($request->except(['image_url']));

    if ($request->hasFile('image_url')) {
        $path = $request->file('image_url')->store('public/products');
        $product->image_url = Storage::url($path);
    }

    $product->save();

    return redirect()->route('product.index')
        ->with('success', 'Product created successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
{
    request()->validate(Product::$rules);

    $input = $request->all();
    
    if ($request->hasFile('image_url')) {
        if ($product->image_url && Storage::exists($product->image_url)) {
            Storage::delete($product->image_url);
        }

        $path = $request->file('image_url')->store('public/products');
        $input['image_url'] = Storage::url($path);
    }

    $product->update($input);

    return redirect()->route('product.index')
        ->with('success', 'Product updated successfully');
}


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        // Si no se encuentra el producto, redirige con un mensaje de error.
        return redirect()->route('product.index')->with('error', 'Product not found.');
    }

    // Si el producto existe, procede a eliminarlo.
    $product->delete();

    // Redirige con un mensaje de éxito.
    return redirect()->route('product.index')->with('success', 'Product deleted successfully');
}



}
