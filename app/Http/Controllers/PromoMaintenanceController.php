<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class PromoMaintenanceController extends Controller
{
    //

    public function promoMaintenance(){

        $date = now()->timezone('Asia/Manila')->format('Y-m-d'); // GETTING THE TIME ZONE IN PH

        $data = DB::table('userBranchMaintenance')
                    ->select('id', 'userID', 'created_date', 'date_end')
                    ->get();

        foreach ($data as $item) {
            $endDate = Carbon::parse($item->date_end)->format('Y-m-d');

            if ($date === $endDate) {
                DB::table('userBranchMaintenance')
                    ->where('id', $item->id)
                    ->update(['status' => 'Deactivated']);

                DB::table('users')
                    ->where('id', $item->userID)
                    ->update(['status' => 'Inactive']);
            }
        }
    }
}
