<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('area.create-area', [
            'title' => 'DnG Store | Tambah Area',
            'user'  => auth()->user(),
            'menu'  => ['Area', 'Tambah Area'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store1(Request $request){
    //     return dd('hi');
    // }
    // public function simpan(Request $request)
    // {
    //     return dd($request->all());
    // }
    public function simpan(Request $request)
    {
        // return dd($request->all());
        $request->validate([
            'provinsi'   => 'required|not_in:pilih',
            'kabupaten'  => 'required|not_in:pilih',
            'kecamatan'  => 'required|not_in:pilih',
            'kelurahan'  => 'required|not_in:pilih|unique:areas',
            'ongkir'     => 'required|numeric',
        ]);
        DB::table('areas')->insert([
            'provinsi'   => $request->provinsi,
            'kabupaten'  => $request->kabupaten,
            'kecamatan'  => $request->kecamatan,
            'kelurahan'  => $request->kelurahan,
            'ongkir'     => $request->ongkir,
        ]);
        $session = [
            'message' => 'Berhasil menambah area pengiriman!',
            'type' => 'Tambah Area',
            'alert' => 'Tambah Berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('su.area')->with($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */


    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('area.edit-area', [
            'title' => 'DnG Store | Edit Area',
            'menu' => ['Area', 'Edit Area'],
            'user' => auth()->user(),
            'area' => $area
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'provinsi'   => 'required|not_in:pilih',
            'kabupaten'  => 'required|not_in:pilih',
            'kecamatan'  => 'required|not_in:pilih',
            'kelurahan'  => 'required|not_in:pilih|unique:areas',
            'ongkir'     => 'required|numeric',
        ]);
        DB::table('areas')->where('id', $area->id)
            ->update([
                'provinsi'   => $request->provinsi,
                'kabupaten'  => $request->kabupaten,
                'kecamatan'  => $request->kecamatan,
                'kelurahan'  => $request->kelurahan,
                'ongkir'     => $request->ongkir
            ]);
        $session = [
            'message' => 'Berhasil mengubah area pengiriman!',
            'type' => 'Edit Area',
            'alert' => 'Update Berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('su.area')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function delete(Area $area)
    {
        DB::table('areas')->delete($area->id);
        $session = [
            'message' => 'Berhasil menghapus area pengiriman!',
            'type' => 'Hapus Area',
            'alert' => 'Hapus Berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('su.area')->with($session);
    }

    public function destroy($id)
    {
        try {
            $Area = Area::findOrFail($id);

            // Cek apakah ada relasi produk yang terhubung dengan kategori ini
            if ($Area->invoice()->exists()) {
                throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
            }

            $Area->delete();
            $session = [
                'message' => 'Berhasil menghapus area!',
                'type' => 'Hapus Area',
                'alert' => 'Hapus Berhasil!',
                'class' => 'success'
            ];
            return redirect()->route('su.area')->with($session);
        } catch (\Exception) {
            $session = [
                'message' => 'Tidak dapat menghapus data area ini karena masih terhubung dengan invoice',
                'type' => 'Hapus Area',
                'alert' => 'Gagal Menghapus!',
                'class' => 'success'
            ];
            return redirect()->back()->with($session);
        }
    }
}
