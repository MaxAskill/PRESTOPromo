<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FetchDashboardController extends Controller
{
    //
    public function fetchDashboardToday(Request $request){

        if ($request->company == "NBFI") {
            $data = DB::table("pullOutBranchTblNBFI")
                ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                ->where('status', 'unprocessed')
                ->whereDate('dateTime', now()->toDateString())
                ->get();
        } else if ($request->company == "EPC") {
            $data = DB::table("pullOutBranchTbl")
                ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                ->where('status', 'unprocessed')
                ->whereDate('dateTime', now()->toDateString())
                ->get();
        }

        return response()->json($data);
    }

    public function fetchDashboardUnprocessed(Request $request){
        if($request->company == "NBFI"){
            $data = DB::table("pullOutBranchTblNBFI")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'unprocessed')
                        ->get();


        }else if($request->company == "EPC"){
            $data = DB::table("pullOutBranchTbl")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'unprocessed')
                        ->get();
        }

        return response()->json($data);
    }

    public function fetchDashboardApproved(Request $request){
        if($request->company == "NBFI"){
            $data = DB::table("pullOutBranchTblNBFI")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'approved')
                        ->get();


        }else if($request->company == "EPC"){
            $data = DB::table("pullOutBranchTbl")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'approved')
                        ->get();
        }

        return response()->json($data);
    }

    public function fetchDashboardDenied(Request $request){
        if($request->company == "NBFI"){
            $data = DB::table("pullOutBranchTblNBFI")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'denied')
                        ->get();


        }else if($request->company == "EPC"){
            $data = DB::table("pullOutBranchTbl")
                        ->select('id', 'chainCode', 'company', 'branchName', 'transactionType', 'status')
                        ->where('status', 'denied')
                        ->get();
        }

        return response()->json($data);
    }

}
