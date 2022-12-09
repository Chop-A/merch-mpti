<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Carbon\Translator;

class TransactionController extends Controller
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

    public function transaction(Request $request) {
        $this->validate($request, [
            'product_id' => 'required'
        ]);
        $user_id = Transaction::with('User')->where($request->user->id)->get();
        $success = Transaction::create([
            'user_id' => $user_id,
            'product_id' => $request
            
        ]);
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar transaksi',
                'data' => [
                    'transaksi' => [
                        Transaction::select('id')->get(),
                    'user' => User::select('name','username','email')->get(),
                    'product' => Product::select('productName', 'price')->get(),
                        Transaction::select('created_at','updated_at')->get()
                    ]
                ]
            ], 200);
        }
    }

    public function showTransaction(Request $request)
    {
        $success = Transaction::all();
        $output['success'] = true;
        $output['message'] = 'Berikut adalah daftar transaksi : ';
        $output['data'] = Transaction::all();
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Berikut adalah daftar transaksi : ',
                'data' => [
                    'transaksi' => [
                        Transaction::select('id')->get(),
                    'user' => User::select('name','username','email')->get(),
                    'product' => Product::select('productName', 'price')->get(),
                        Transaction::select('created_at','updated_at')->get()
                    ]
                ]
            ], 200);
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

    public function takeTransaction(Request $request, $id)
    {
        $success = Transaction::find($id);
        $output['success'] = true;
        $output['message'] = 'Info transaksi';
        $output['data'] = Transaction::find($id);
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Info transaksi',
                'data' => [
                    'transaksi' => [
                        Transaction::select('id')->where('id','=',$id)->get(),
                    'user' => User::select('name','username','email')->get(),
                    'product' => Product::select('productName', 'price')->get(),
                        Transaction::select('created_at','updated_at')->get()
                    ]
                ]
            ], 200);
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

    public function updateTransaction(Request $request, $id)
    {
        $success = Transaction::find($id);
        $output['success'] = $success->update($request->all());
        $output['data'] = Transaction::where('id','=',$id)->get();

        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar transaksi',
                'data' => [
                    'transaksi' => [
                        Transaction::select('id'),
                    'user' => User::select('name','username','email')->get(),
                    'product' => Product::select('productName', 'price')->get(),
                        Transaction::select('created_at','updated_at')->get()
                    ]
                ]
            ], 200);
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
}