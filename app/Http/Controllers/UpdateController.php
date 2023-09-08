<?php

namespace App\Http\Controllers;

use App\Models\PullOutModel;
use App\Models\PullOutItemModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModelNBFI;
use App\Models\PullOutBranchModelNBFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TransactionModel;

class UpdateController extends Controller
{
    //

    public function updateItemQuantity(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        if($request->company == "EPC"){
            $old_data = PullOutItemModel::find($request->id);

            if($old_data->quantity == $request->quantity && $old_data->boxLabel == $request->boxLabel){
                    //setting the name who edit the item
                    $editBy = DB::select('UPDATE pullOutItemsTbl SET editedBy = \''.$name.'\',
                    updated_at = \''.$date.'\', boxNumber = \''.$request->boxNumber.'\',
                    boxLabel = \''.$request->boxLabel.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');
            }


            $itemCode = DB::table('pullOutItemsTbl')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('epc_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $itemCode->itemCode)
                        ->first();

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $data = DB::select('UPDATE pullOutItemsTbl SET quantity = \''.$request->quantity.'\', amount = \''.$amount.'\'  WHERE id = \''.$request->id.'\' ');

            $table_affected = 'pullOutItemsTbl';
        } else if($request->company == "NBFI"){
            $old_data = PullOutItemModelNBFI::find($request->id);

            if($old_data->quantity == $request->quantity && $old_data->boxLabel == $request->boxLabel){

                    //setting the name who edit the item
                    $editBy = DB::select('UPDATE pullOutItemsTblNBFI SET editedBy = \''.$name.'\',
                    updated_at = \''.$date.'\', boxNumber = \''.$request->boxNumber.'\',
                    boxLabel = \''.$request->boxLabel.'\', status = "edited"  WHERE id = \''.$request->id.'\' ');

            }
            $itemCode = DB::table('pullOutItemsTblNBFI')
                        ->select('itemCode')
                        ->where('id', $request->id)
                        ->first();

            //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            $price = DB::table('nbfi_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', '=', $itemCode->itemCode)
                        ->first();

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $data = DB::select('UPDATE pullOutItemsTblNBFI SET quantity = \''.$request->quantity.'\', amount = \''.$amount.'\'  WHERE id = \''.$request->id.'\' ');

            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($request->boxLabel);
    }

    public function updateStatus(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;
        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTbl SET editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');

            //setting the status per item
            $data = DB::select('UPDATE pullOutItemsTbl SET status = \''.$request->status.'\'  WHERE id = \''.$request->id.'\' ');

            $old_data = PullOutItemModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';
        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutItemsTblNBFI SET editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');

            //setting the status per item
            $data = DB::select('UPDATE pullOutItemsTblNBFI SET status = \''.$request->status.'\'  WHERE id = \''.$request->id.'\' ');

            $old_data = PullOutItemModelNBFI::find($request->id);
            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'delete';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function updateBranchStatus(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTbl SET status = \''.$request->status.'\', editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';

        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTblNBFI SET status = \''.$request->status.'\', editedBy = \''.$name.'\', updated_at = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModelNBFI::find($request->id);
            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function updateBranchStatusApprover(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        if($request->company == "EPC"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTbl SET status = \''.$request->status.'\', approvedBy = \''.$name.'\', approvedDate = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModel::find($request->id);
            $table_affected = 'pullOutItemsTbl';

        } else if($request->company == "NBFI"){
            //setting the name who edit the item
            $editBy = DB::select('UPDATE pullOutBranchTblNBFI SET status = \''.$request->status.'\', approvedBy = \''.$name.'\', approvedDate = \''.$date.'\'  WHERE id = \''.$request->id.'\' ');
            $old_data = PullOutBranchModelNBFI::find($request->id);
            $table_affected = 'pullOutItemsTblNBFI';
        }

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'update';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function updatePullOutBranchRequest(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){
            $data = DB::select('UPDATE pullOutBranchTblNBFI
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\', dateTime = \''.$date.'\' WHERE id = \''.$request->id.'\'');

        //  DB::select('DELETE FROM pullOutItemsTblNBFI WHERE plID = \''.$request->id.'\'');

        } else if($request->companyType == "EPC" || $request->companyType == "AHLC"){
            $data = DB::select('UPDATE pullOutBranchTbl
                                SET chainCode = \''.$request->chainCode.'\',
                                company = \''.$request->companyType.'\',
                                branchName = \''.$request->branchName.'\',
                                transactionType = \''.$request->transactionType.'\',
                                status = \''.$request->status.'\', dateTime = \''.$date.'\' WHERE id = \''.$request->id.'\'');

            // DB::select('DELETE FROM pullOutItemsTbl WHERE plID = \''.$request->id.'\'');
        }

        return response()->json($data);
    }

    public function testing(Request $request){
        $old_data = DB::table('pullOutItemsTbl')
                ->select('quantity', 'boxLabel')
                ->where('id', $request->id)
                ->get()
                ->first();
        print_r($old_data->quantity);
    }

    public function updatePullOutItemRequest(Request $request){

        $status = $request->status;

        foreach ($request->items as $value) {
            if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC"){

                // $old_data = PullOutItemModelNBFI::find($request->id);
                if($value['id'])
                {
                    $old_data = DB::table('pullOutItemsTblNBFI')
                                    ->select('quantity', 'boxLabel')
                                    ->where('id', $value['id'])
                                    ->get()
                                    ->first();

                    if($request->status != "draft"){
                        if($old_data->quantity != $value['quantity'] || $old_data->boxLabel != $value['boxLabel'])
                            $status = "edited";
                    }
                }


                $data = DB::select('DELETE FROM pullOutItemsTblNBFI
                                    WHERE id = \''.$value['id'].'\'');

                $item = new PullOutItemModelNBFI();
                $price = DB::table('nbfi_items')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $value['code'])
                            ->first();



            }else if ($request->companyType == "EPC" || $request->companyType == "AHLC"){

                // $old_data = PullOutItemModel::find($request->id);
                if($value['id'])
                {
                    $old_data = DB::table('pullOutItemsTbl')
                                    ->select('quantity', 'boxLabel')
                                    ->where('id', $value['id'])
                                    ->get()
                                    ->first();

                    if($request->status != "draft"){
                        if($old_data->quantity != $value['quantity'] || $old_data->boxLabel != $value['boxLabel'])
                            $status = "edited";
                    }
                }
                $data = DB::select('DELETE FROM pullOutItemsTbl
                                    WHERE id = \''.$value['id'].'\'');

                $item = new PullOutItemModel();
                $price = DB::table('epc_items')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $value['code'])
                            ->first();
            }

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($value['quantity']);

            $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

            $item->plID = $request->plID;
            $item->brand = $value['categorybrand'];
            $item->boxNumber = $value['boxNumber'];
            $item->boxLabel = $value['boxLabel'];
            $item->itemCode = $value['code'];
            $item->quantity = $value['quantity'];
            $item->amount = $amount;
            $item->status = $status;
            $item->dateTime = $date;

            //SAVING
            $item->save();
        }

        if($request->companyType == "NBFI" || $request->companyType == "CMC" || $request->companyType == "ASC")
            DB::table('pullOutItemsTblNBFI')
                ->whereIn('id', $request->removedItems)
                ->delete();
        else 
            DB::table('pullOutItemsTbl')
                ->whereIn('id', $request->removedItems)
                ->delete();
        // return response()->json($item);
    }

    public function updateUserBranch(Request $request){
        if($request->status == "Deactivated")
            DB::select("UPDATE users SET status = 'Inactive' WHERE id = \"".$request->userID."\"");
        else
            DB::select("UPDATE users SET status = 'Active' WHERE id = \"".$request->userID."\"");

        DB::select("UPDATE userBranchMaintenance SET status = \"".$request->status."\" WHERE userID = \"".$request->userID."\"");
    }

    public function updateUserBranchByRequest(Request $request){
        // $date = now()->timezone('Asia/Manila');
        $dateStart = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateStart'))
                    ->addDay();
        $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnd'))
                    ->addDay();
        DB::table('userBranchMaintenance')->where('id', $request->id)->where('request', 'remove')->delete();
        DB::select("UPDATE userBranchMaintenance SET request = null, date_start = \"".$dateStart."\", date_end = \"".$dateEnd."\" WHERE id = \"".$request->id."\" AND request = 'additional'");
    }
}
