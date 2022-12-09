<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //

    public function showCart(Request $request, $id)
    {
        $success = Product::find($id);
        $output['success'] = true;
        // $output['message'] = 'Berikut informasi produk : ';
        $output['data'] = Product::find($id);
        if($success) {
            return response()->json([
                'success' => true,
                // 'message' => 'Berikut informasi produk : ',
                'data' => [
                   'product' => Product::select('id','productName','image','price','size')->where('id','=',$id)->get() ]
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

    public function updateCart(Request $request, $id)
    {
        // $token = $request->header('token');
        // if (!$token) {
        //     return new Response('Token required!', 401);
        // } else {
            // if($request->user->role != 'seller') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized!'
            //     ], 403);
            // }
        $success = Product::find($id);
        $output['success'] = $success->update($request->all());
        $output['data'] = Product::where('id','=',$id)->get();
        
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Update Berhasil!',
                'data' => [
                    'product' => Product::select('id','productName','image','price','description','size','stock', 'type')->where('id','=',$id)->get()]
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

    public function deleteCart(Request $request, $id)
    {
        // $token = $request->header('token');
        // if (!$token) {
        //     return new Response('Token required!', 401);
        // } else {
            // if($request->user->role != 'seller'){
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized!'
            //     ], 403);
            // }
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
        return 'Failed to delete data :(';
    }
}
