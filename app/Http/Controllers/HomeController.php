<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Termwind\Components\Raw;

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
        $user = auth()->user();
        $notification = null;
        if ($user) {
            $this->detect();
            $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        }
        $query = $request->search;
        if ($query) {
            $top_resellers = DB::raw("SELECT
            `users`.*,
            SUM(orders.qty) AS total_order
          FROM
            `users`
            INNER JOIN `orders` ON `users`.`id` = `orders`.`user_id`
            INNER JOIN `products` ON `products`.`id` = `orders`.`product_id`
          WHERE
            `role` = 'Reseller'
          GROUP BY
            `users`.`id`,
            `users`.`name`
          ORDER BY
            `total_order` DESC
          limit
            2");
            
            $products = DB::table('products as p')
                ->select('p.id as product_id', 'p.*', 'c.category')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->where('name', 'like', "%$query%")
                ->orWhere('category', 'like', "%$query%")
                ->paginate(12);
            $special_products = Product::where('special_status', '=', 'Limited Edition')->paginate(3);
        } else {
            $top_resellers = DB::raw("SELECT
            `users`.*,
            SUM(orders.qty) AS total_order
          FROM
            `users`
            INNER JOIN `orders` ON `users`.`id` = `orders`.`user_id`
            INNER JOIN `products` ON `products`.`id` = `orders`.`product_id`
          WHERE
            `role` = 'Reseller'
          GROUP BY
            `users`.`id`,
            `users`.`name`
          ORDER BY
            `total_order` DESC
          limit
            2");
            $special_products = Product::where('special_status', '=', 'Limited Edition')->paginate(3);
            $products = DB::table('products as p')
                ->select('p.id as product_id', 'p.*', 'c.category')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->paginate(12);
        }
        return view('home.index', [
            'title' => 'DnG Store',
            'menu' => 'home',
            'user' => $user,
            'products' => $products,
            'special_products' => $special_products,
            'top_resellers' => $top_resellers,
            'notifications' => $notification
        ]);
    }

    public function cart()
    {
        $user = auth()->user();
        $carts = Cart::where('user_id', $user->id)->get()->all();
        $notification = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('cart.index', [
            'title' => 'DnG Store | My Cart',
            'user' => $user,
            'menu' => ['Cart', 'Detail'],
            'carts' => $carts,
            'notifications' => $notification,
        ]);
    }

    public function order()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('invoice.index', [
            'title' => 'DnG Store | Transaksi',
            'menu' => ['Transaksi'],
            'user' => $user,
            'notifications' => $notification,
            'invoices' => $invoices
        ]);
    }

    public function notification()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get()->all();
        return view('notification.index', [
            'title' => 'DnG Store | Notifikasi',
            'menu' => ['Notification'],
            'user' => $user,
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
        if ($detect >= 2 && $user->role == 'Customer') {
            $message = "Anda telah memenuhi persyaratan untuk menjadi reseller
        <br>
        Apakah anda ingin menjadi reseller?
        <br>
        Keuntungan menjadi reseller adalah 
        <br>mendapatkan harga di bawah customer. <br>Ayo klik Ya untuk bisa jadi reseller kami!
        <div class='d-flex justify-content-evenly'>
        <a class='btn btn-sm btn-success' href='/us/become-reseller'>Ya</a>
        <a class='btn btn-sm btn-warning' data-bs-dismiss='modal'>Ingatkan Nanti</a>
        <a class='btn btn-sm btn-danger' data-bs-dismiss='modal' href='/us/reject-reseller'>Tidak</a>
        </div>";
            session()->flash('message', $message);
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

    // Tambahkan aksi untuk mengubah peran pengguna menjadi 'Reseller'
    public function becomeReseller()
    {
        $user = auth()->user();
    
        if ($user->role == 'Customer') {
            $user->role = 'Reseller';
            $user->save();
         }
         $session = [
            'message' => "Selamat, Anda Sekarang telah menjadi reseller kami.",
            'type' => 'Menerima Tawaran Reseller',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
       
    // Tambahkan logika lain yang diperlukan setelah pengguna menjadi reseller

     return redirect()->back()->with($session);
    }

    public function rejectReseller()
    {
    $user = auth()->user();
    
    if ($user->role == 'Customer') {
        // Tidak ada tindakan khusus yang perlu dilakukan
    }
    $session = [
        'message' => "Anda berhasil menolak tawaran menjadi reseller",
        'type' => 'Menolak Tawaran Reseller',
        'alert' => 'Notifikasi Sukses!',
        'class' => 'success'
    ];
    return redirect()->back()->with($session);
}

    public function requestReseller()
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
        $total = Order::select(DB::raw('COUNT(id) as total_order'), DB::raw('SUM(total) as total_pengeluaran'))->where('user_id', $user->id)->get();
        $product = Product::select('products.name', DB::raw('SUM(orders.qty) as total_order'))
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->where([
                ['orders.status', '!=', null],
                ['orders.user_id', '=', $user->id]
            ])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_order')
            ->limit(10)
            ->get();
        return view('home.profile', [
            'title' => 'DnG Store | Profile',
            'menu' => ['Home', 'profile'],
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customers'],
            'user' => $user,
            'total' => $total,
            'product' => $product,
            'notifications' => $notification
        ]);
    }

    public function editProfile()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('home.edit-profile', [
            'title' => 'DnG Store | Edit Profile',
            'menu' => ['Home', 'profile'],
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customers'],
            'user' => $user,
            'notifications' => $notification
        ]);
    }

    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'photo' => 'image|max:8192',
        ]);
        $photo = $request->file('photo');
        if ($photo) {
            $filename = $photo->store('image');
        } else {
            $filename = $user->photo;
        }

        $address = $user->address;
        if (!in_array('pilih', [$request->provinsi, $request->kabupaten, $request->kecamatan, $request->kelurahan])) {
            $address = "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi";
        }

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'photo' => $filename,
            'address' => $address
        ]);
        $session = [
            'message' => 'Berhasil mengupdate Profile!',
            'type' => 'Edit Profile',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('us.profile')->with($session);
    }

    public function changePassword()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('home.change-password', [
            'title' => 'DnG Store | Ubah Password',
            'menu' => ['Home', 'profile'],
            'user' => $user,
            'notifications' => $notification
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:4',
            'repeat_password' => 'required|same:password'
        ]);
        if (Hash::check($request->old_password, $user->password)) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->password),
                'updated_at' => now('Asia/Jakarta')
            ]);
            $session = [
                'message' => 'Berhasil mengubah password!',
                'type' => 'Ubah Password',
                'alert' => 'Notifikasi Berhasil!',
                'class' => 'success'
            ];
            return redirect()->route('us.profile')->with($session);
        }
        $session = [
            'message' => 'Gagal Mengubah password, pastikan password lama benar!',
            'type' => 'Ubah Password',
            'alert' => 'Notifikasi Gagal!',
            'class' => 'danger'
        ];
        return redirect()->route('us.change.password')->with($session);
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
