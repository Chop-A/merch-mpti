<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{
    use SoftDeletes;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function sellerUser(Request $request){
        $token = $request->header('token');
        //if($request->user->role == 'admin') {
        if (!$token) {
            return new Response('Token required!', 401);
        } else {
        $success = User::all();
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Berikut informasi user :  ',
                'data' => [
                   'user' => User::select('id','name','username','email')->get() ]
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
}

    public function showUser(Request $request, $id)
    {
        $token = $request->header('token');
        if (!$token) {
            return new Response('Token required!', 401);
        } else {
            if($request->user->id == $id) {
        $success = User::all();
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Berikut data user dengan id '.$id,
                'data' => [
                   'user' => User::select('id','name','username','email')->where('id','=',$id)->get() ]
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
    }
}

    public function updateUser(Request $request, $id)
    {
        if($request->user->role == 'user')  {
        $user = User::where('id', $id)->first();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if($user) {
            return response()->json([
                'success' => true,
                'message' => 'Update Success!',
                'data' => [
                   'user' => User::select('id','name','username','email')->where('id','=',$id)->get() ]
            ], 200);
        } else if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Update Failed!'
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }
}

    public function deleteUser(Request $request, $id)
    {   
        if($request->user->id != $id){
            return response()->json([
               'success' => false,
               'message' => 'Failed!',
           ], 403);
       } else {
        $success = User::find($id);
        $success->delete();
        if($success) {
            return response()->json([
                'success' => true,
                'message' => 'Delete Success!'
            ], 200);
        }
        // } else if(!$success) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Delete Failed!'
        //     ], 404);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Terjadi kesalahan pada server.'
        //     ], 504);
        // }
        // return 'Failed to delete data :(';
    // }
}
    }
}