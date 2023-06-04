<?php

namespace App\Http\Controllers;

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
            //
            $products = DB::table('products')->select('products.id as product_id', '*')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('name', 'LIKE', "%$query%")
                ->orWhere('category', 'LIKE', "%$query%")
                ->get()->all();
        } else {
            //
            $products = DB::table('products')->select('products.id as product_id', '*')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->get()->all();
        }
        return view('home.index', [
            'title' => 'DnG Store',
            'menu' => 'home',
            'user' => auth()->user(),
            'products' => $products
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
