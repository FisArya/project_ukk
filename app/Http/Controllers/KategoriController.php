<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $rsetKategori = Kategori::latest()->paginate(10);
        // return view('v_Kategori.index',compact('rsetKategori'));

        // return view('vsiswa.index');

        // $rsetKategori = Kategori::all();
        // return view('v_kategori.index',compact('rsetKategori'));
        if ($request->search){
            $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('ketKategorik(kategori) as kat'))
                                                 ->where('id','like','%'.$request->search.'%')
                                                 ->orWhere('deskripsi','like','%'.$request->search.'%')
                                                //  ->orWhere('kategori','like','%'.$request->search.'%')
                                                 ->paginate(10);
    }  else {
        $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('ketKategorik(kategori) as kat'))->paginate(10);
        return view('v_kategori.index',compact('rsetKategori'));
    }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = array('blank'=>'Pilih Kategori',
                            'A'=>'Alat',
                            'B'=>'Bahan',
                            );
        return view('v_kategori.create',compact('akategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate( [
            'deskripsi'              => 'required',
            'kategori'              => 'required',
        ]);


        //create post
        Kategori::create([
            'deskripsi'          => $request->deskripsi,
            'kategori'          => $request->kategori,
        ]);

        //redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);
        

        return view('v_kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akategori = array('blank'=>'Pilih Kategori',
                            'A'=>'Alat',
                            'B'=>'Bahan',
                            );

        $rsetKategori = Kategori::find($id);
        //return $rsetKategori;
        return view('v_kategori.edit', compact('rsetKategori','akategori'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate( [
        'deskripsi' => 'required',
        'kategori' => 'required',
    ]);

    $rsetKategori = Kategori::find($id);
    $rsetKategori->update($request->all());

    return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()){ 
            return redirect()->route('kategori.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rseKategori = Kategori::find($id);
            $rseKategori->delete();
            return redirect()->route('kategori.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }
}
