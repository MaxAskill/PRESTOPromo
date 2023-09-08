<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\MailNotify;

class AgingPullOutController extends Controller
{
    //
    public function agingControllerNBFI(){

        $data = DB::table('pullOutBranchTblNBFI')
                ->select('id', 'dateTime', 'status')
                ->where('status', 'unprocessed')
                ->get();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $users = DB::table('users')
            ->select('email', 'name', 'position')
            ->where(function ($query) {
                $query->where('position', 'Agent')
                      ->orWhere('position', 'Admin');
            })
            ->where('email', 'LIKE', '%@barbizonfashion.com')
            ->get();


        foreach ($data as $item => $value){
            $dateTime = Carbon::parse($value->dateTime);

            // Compare the date with the current date and check if it is 2 days or more in the past
            if ($dateTime->diffInDays($date) >= 2) {
                // The date is 2 days or more in the past


                foreach($users as $user => $key){
                    if($key->position == "Admin"){
                        $name = "MIS";
                    }else{
                        $name = $key->name;
                    }
                    $mailInfo = array(
                        'transactionID' => $value->id,
                        'name' => $name,
                        'status' => 'Unprocessed'
                    );
                    Mail::to($key->email)->send(new MailNotify ($mailInfo));
                }
                // Mail::to("perezmelvin74@gmail.com")->send(new MailNotify ($mailInfo));
            }

        }


        // return response()->json($data);

    }

    public function agingControllerEPC(){

        $data = DB::table('pullOutBranchTbl')
                ->select('id', 'dateTime', 'status')
                ->where('status', 'unprocessed')
                ->get();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $users = DB::table('users')
            ->select('email', 'name', 'position')
            ->where(function ($query) {
                $query->where('position', 'Agent')
                      ->orWhere('position', 'Admin');
            })
            ->where('email', 'LIKE', '%@everydayproductscorp.com')
            ->get();


        foreach ($data as $item => $value){
            $dateTime = Carbon::parse($value->dateTime);

            // Compare the date with the current date and check if it is 2 days or more in the past
            if ($dateTime->diffInDays($date) >= 2) {
                // The date is 2 days or more in the past


                foreach($users as $user => $key){
                    if($key->position == "Admin"){
                        $name = "MIS";
                    }else{
                        $name = $key->name;
                    }
                    $mailInfo = array(
                        'transactionID' => $value->id,
                        'name' => $name,
                        'status' => 'Unprocessed'
                    );
                    Mail::to($key->email)->send(new MailNotify ($mailInfo));
                }
                // Mail::to("perezmelvin74@gmail.com")->send(new MailNotify ($mailInfo));
            }

        }


        // return response()->json($data);

    }

    public function SLACountNBFI(){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH


        $data = DB::table('pullOutBranchTblNBFI')
            ->select('id', 'dateTime', 'SLA_count')
            ->whereIn('status', ['unprocessed', 'endorsement'])
            ->get();

        foreach($data as $item){
            $dateTime = Carbon::parse($item->dateTime);

            $days_diff = $date->diffInDays($dateTime);

            $daysCount = (int)$item->SLA_count - 1;
            DB::select("UPDATE pullOutBranchTblNBFI SET SLA_count = \"".$daysCount."\" WHERE id = \"".$item->id."\"");

            if($daysCount < 0){
                DB::select("UPDATE pullOutBranchTblNBFI SET SLA_status = 'Delayed' WHERE id = \"".$item->id."\"");
            }

        }
        // return response()->json($data);

    }

    public function SLACountEPC(){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH


        $data = DB::table('pullOutBranchTbl')
            ->select('id', 'dateTime', 'SLA_count')
            ->whereIn('status', ['unprocessed', 'endorsement'])
            ->get();

        foreach($data as $item){
            $dateTime = Carbon::parse($item->dateTime);

            $days_diff = $date->diffInDays($dateTime);

            $daysCount = (int)$item->SLA_count - 1;
            DB::select("UPDATE pullOutBranchTbl SET SLA_count = \"".$daysCount."\" WHERE id = \"".$item->id."\"");

            if($daysCount < 0){
                DB::select("UPDATE pullOutBranchTbl SET SLA_status = 'Delayed' WHERE id = \"".$item->id."\"");
            }

        }
        // return response()->json($data);

    }
}
