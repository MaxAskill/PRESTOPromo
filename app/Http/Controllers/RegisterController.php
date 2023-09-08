<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    //

    public function sendVerificationCode(Request $request){

        $request->validate([
            'email' => 'required|string|email|max:255|unique:'.User::class,
        ]);

        $code = mt_rand(100000, 999999);

        $data = array(
            'email' => $request->email,
            'code' => $code,
            'status' => 'verifyEmail'
        );

        $hashCode = Hash::make($code);
        Mail::to($request->email)->send(new MailNotify ($data));

        return response()->json($hashCode);


    }

    public function confirmCode(Request $request){

        if (Hash::check($request->verCode, $request->hashCode)){
            return response()->json(true);
        } else{
            return response()->json(['error' => 'Error, Code doesn\'t match.' ], 422);
        }
    }

    public function sendForgotVerificationCode(Request $request){

        $emailRequest = DB::table('users')
                            ->select('email')
                            ->where('email', $request->email)
                            ->get();

        if($emailRequest->count() != 0){
            $code = mt_rand(100000, 999999);

            $data = array(
                'email' => $request->email,
                'code' => $code,
                'status' => 'verifyEmail'
            );

            $hashCode = Hash::make($code);
            Mail::to($request->email)->send(new MailNotify ($data));

            return response()->json($hashCode);
        }

        return 1;




    }

    public function updatePasswordUser(Request $request){

        $password = Hash::make($request->password);
        DB::select('UPDATE users set password = \''.$password.'\' WHERE email= \''.$request->email.'\'');

        return response()->json(['message' => 'Success Update Password'], 200);
    }
}
