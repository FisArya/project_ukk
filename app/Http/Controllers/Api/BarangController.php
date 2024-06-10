<?php

namespace App\Http\Controllers\Api;

//import model Barang
use App\Models\Barang;

use App\Http\Controllers\Controller;

//import resource BarangResource
use App\Http\Resources\BarangResource;

//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all Barangs
        $Barangs = Barang::latest()->paginate(5);

        //return collection of Barangs as a resource
        return new BarangResource(true, 'List Data Barangs', $Barangs);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/Barangs', $image->hashName());

        //create Barang
        $Barang = Barang::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        //return response
        return new BarangResource(true, 'Data Barang Berhasil Ditambahkan!', $Barang);
    }
}