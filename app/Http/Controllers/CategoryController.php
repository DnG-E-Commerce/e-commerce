<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $request->search;
        if ($query) {
            $categories = DB::table('categories')->where('category', 'LIKE', "%$query%")->get()->all();
        } else {
            $categories = DB::table('categories')->get()->all();
        }
        return view('category.index', [
            'title' => 'DnG Store | Category',
            'menu' => ['Kategori'],
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('category.create-category', [
            'title' => 'DnG Store | Tambah Category',
            'menu' => ['Kategori', 'Tambah'],
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|unique:categories'
        ]);
        DB::table('categories')->insert([
            'category' => $request->category
        ]);
        $session = [
            'message' => 'Berhasil menambahkan kategori!',
            'type' => 'Tambah Kategori',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.category')->with($session);
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
        return view('category.edit-category', [
            'title' => 'DnG Store | Edit',
            'menu' => ['Kategori', 'Edit Data'],
            'user' => auth()->user(),
            'category' => DB::table('categories')->where('id', $id)->first(),
        ]);
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
        $request->validate([
            'category' => 'required',
        ]);
        DB::table('categories')->where('id', $id)->update([
            'category' => $request->category
        ]);
        $session = [
            'message' => 'Berhasil mengedit kategori!',
            'type' => 'Edit Kategori',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.category')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        DB::table('categories')->delete($id);
        $session = [
            'message' => 'Berhasil menghapus kategori!',
            'type' => 'Hapus Kategori',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.category')->with($session);
    }

    public function destroy($id)
    {
        try {
            $kategoriProduk = Category::findOrFail($id);
            
            // Cek apakah ada relasi produk yang terhubung dengan kategori ini
            if ($kategoriProduk->products()->exists()) {
                throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
                
            }
            
            $kategoriProduk->delete();
            $session = [
                'message' => 'Berhasil menghapus kategori!',
                'type' => 'Hapus Kategori',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            return redirect()->route('su.category')->with($session);
        } catch (\Exception) {
            $session= [
                'message' => 'Tidak dapat menghapus kategori ini karena masih terhubung dengan produk',
                'type' => 'Hapus Kategori',
                'alert' => 'Notifikasi Gagal!',
                'class' => 'success'
            ];
            return redirect()->back()->with($session);
        }
    }
    
}
