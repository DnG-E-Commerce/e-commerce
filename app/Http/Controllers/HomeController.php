<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->search;
        if ($query) {
            $products = Product::where('name', 'LIKE', "%$query%")->all();
        } else {
            $products = Product::all();
        }
        return view('home.index', [
            'title' => 'DnG Store',
            'menu' => 'home',
            'user' => auth()->user(),
            'products' => $products
        ]);
    }

    public function product(Product $product)
    {
        return view('home.detail-product', [
            'title' => 'DnG Store | Detail Product',
            'user' => auth()->user(),
            'menu' => ['Product', 'Detail'],
            'product' => Product::findOrFail($product->id)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function profile($id)
    {
        return view('home.profile', [
            'title' => 'DnG Store | Profile',
            'menu' => 'profile',
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customers'],
            'user' => auth()->user(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
