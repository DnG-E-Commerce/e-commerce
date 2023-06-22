<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Session;

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
        $this->detect();
        $user = auth()->user();
        $query = $request->search;
        if ($query) {
            $products = DB::table('products as p')
                ->select('p.id as product_id', 'p.*', 'c.category')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->where('name', 'like', "%$query%")
                ->orWhere('category', 'like', "%$query%")
                ->paginate(6);
        } else {
            $products = DB::table('products as p')
                ->select('p.id as product_id', 'p.*', 'c.category')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->paginate(6);
        }
        $notification = null;
        if ($user) {
            $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        }
        return view('home.index', [
            'title' => 'DnG Store',
            'menu' => 'home',
            'user' => $user,
            'products' => $products,
            'notifications' => $notification
        ]);
    }

    public function detect()
    {
        $user = auth()->user();
        $detect = DB::table('orders')
            ->where('user_id', $user->id)
            ->where('total', '>', 200000)
            ->count();
        $notification = Notification::where([
            ['user_id', $user->id],
            ['title', '=', 'Tawaran Menjadi Reseller']
        ])->first();
        if ($detect > 5 && $user->role == 'Customer') {
            session()->flash('message', 'Anda telah memenuhi persyaratan untuk menjadi reseller');
            session()->flash('type', 'Tawaran Upgrade');
            session()->flash('alert', 'Selamat!');
            session()->flash('class', 'success');
            if (!$notification) {
                DB::table('notifications')->insert([
                    'user_id' => $user->id,
                    'title' => 'Tawaran Menjadi Reseller',
                    'message' => htmlspecialchars("Anda telah memenuhi persyaratan untuk menjadi reseller, anda dapat mengunjungi halaman <a href='/home/pengajuan-reseller'>ini</a>"),
                    'is_read' => 0,
                    'created_at' => now('Asia/Jakarta')
                ]);
            }
        }
    }

    public function product(Product $product)
    {
        $user = auth()->user();
        $product = DB::table('products as p')->select('p.*', 'c.category')->join('categories as c', 'p.category_id', '=', 'c.id')->where('p.id', '=', $product->id)->first();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('home.detail-product', [
            'title' => 'DnG Store | Detail Product',
            'user' => $user,
            'menu' => ['Product', 'Detail'],
            'product' => $product,
            'notifications' => $notification
        ]);
    }

    public function pengajuanReseller()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('home.pengajuan-reseller', [
            'title' => 'DnG Store | Form Pengajuan Reseller',
            'menu' => ['Home', 'Pengajuan Reseller'],
            'user' => $user,
            'notifications' => $notification
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

    public function profile()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('home.profile', [
            'title' => 'DnG Store | Profile',
            'menu' => 'profile',
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customers'],
            'user' => $user,
            'notifications' => $notification
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
