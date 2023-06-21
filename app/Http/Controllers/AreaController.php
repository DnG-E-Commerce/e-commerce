<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

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


    public function index()
    {
        $areas = DB::table('areas')->get()->all();
        return view('area.index', [
            'title' => 'DnG Store | Area Pemesanan',
            'menu'  => ['Area'],
            'user' => auth()->user(),
            'areas' => $areas
        ]);
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
    public function store(Request $request)
    {
        $request->validate([
            'provinsi'  => 'required|not_in:pilih',
            'kabupaten' => 'required|not_in:pilih',
            'kecamatan' => 'required|not_in:pilih',
            'ongkir'    => 'required|numeric',
        ]);
        DB::table('areas')->insert([
            'provinsi'  => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'ongkir'    => $request->ongkir
        ]);
        $session = [
            'message' => 'Berhasil menambahkan area pengiriman!',
            'type' => 'Tambah Area',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('area')->with($session);
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
            'kelurahan'  => 'required|not_in:pilih',
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
            'message' => 'Berhasil mengedir area pengiriman!',
            'type' => 'Edit Area',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('area')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        DB::table('areas')->delete($area->id);
        $session = [
            'message' => 'Berhasil menghapus area pengiriman!',
            'type' => 'Hapus Area',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('area')->with($session);
    }
}
