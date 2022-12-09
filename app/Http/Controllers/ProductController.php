<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Product;
//use Illuminate\Http\Client\Request;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function showProduct()
    {
        $success = Product::all();
        $output['success'] = true;
        $output['message'] = 'Daftar semua produk';
        $output['data'] = Product::all();
        if($success) {
            return (new Response($output))
            ->header('Content-type', 'application/json');
        }
        else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien.'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }

    public function showProductType($type)
    {
        $success = Product::where($type);
        $output['success'] = true;
        $output['message'] = 'Daftar produk per tipe';
        $output['data'] = Product::where($type);
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Informasi produk tertentu',
                'data' => [
                   'product' => Product::select('id','productName','image','price', 'type')->where('type','=',$type)->get() ]
            ], 200);
        } else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien. Coba lagi!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }

    public function takeProduct($id)
    {
        $success = Product::find($id);
        $output['success'] = true;
        $output['message'] = 'Informasi produk tertentu';
        $output['data'] = Product::find($id);
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Informasi produk tertentu',
                'data' => [
                   'product' => Product::select('id','productName','image','price','description','stock','type')->where('id','=',$id)->get() ]
            ], 200);
        } else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien. Coba lagi!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }

    public function insertProduct(Request $request)
    {
        $this->validate($request, [
            'productName' => 'required',
            'image' => 'required',
            'price' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'type' => 'required'
        ]);
        $success = Product::create($request->all());
        $output['success'] = true;
        $output['message'] = 'Produk berhasil ditambahkan.';
        $output['data'] = Product::select('productName','image','price','description','stock', 'type')->get();
        if($success) {
            return (new Response($output))
            ->header('Content-type', 'application/json');
        } else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien. Coba lagi!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $success = Product::find($id);
        $output['success'] = $success->update($request->all());
        $output['data'] = Product::where('id','=',$id)->get();
        
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Update Berhasil!',
                'data' => [
                    'product' => Product::select('id','productName','image','price','description','stock', 'type')->where('id','=',$id)->get()]
            ], 200);
        } else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien. Coba lagi!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }

    }

    public function deleteProduct(Request $request, $id)
    {
        $this->authorize('create', Product::class);
        $success = Product::find($id);
        $output['success'] = $success->delete();
        $output['data'] = Product::all();
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!',
            ], 200);
        } else if(!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dari klien. Coba lagi!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
        return 'Gagal menghapus data.';
    }
}
