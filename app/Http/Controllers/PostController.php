<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PullOutModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModel;
use App\Models\PullOutBranchModelNBFI;
use App\Models\PullOutItemModelNBFI;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use PdfReport;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use App\Models\TransactionModel;
use App\Models\EpcBranchModel;
use App\Models\NbfiBranchModel;
use App\Models\EpcBrandModel;
use App\Models\NbfiBrandModel;
use App\Models\EpcDriverModel;
use App\Models\EpcReasonModel;
use App\Models\ImageBranchModel;
use App\Models\UserBranchModel;
use App\Models\RemarksModel;
use App\Models\pullOutLetterDates;
use App\Http\Controllers\BrevoSMService;
use Image;

class PostController extends Controller
{

    public function checkAccStatus(Request $request){

        // $status = DB::table('users as a')
        // ->join('companyTbl as b', 'a.company', '=', 'b.id')
        // ->select('b.shortName as company','a.status')
        // ->where('a.email', $request->email)
        // ->get();
        $status = DB::table('users as a')
                    ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('b.shortName as company', 'a.status')
                    ->where('a.email', $request->email)
                    ->get();

        // $status = DB::table('users as a')
        //             ->select('a.status')
        //             ->where('a.email', $request->email)
        //             ->get();

        return response()->json($status);
    }

    public function postPullOutRequest(Request $request){

        //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
        $price = DB::table('epc_items')
                    ->select('EffectivePrice')
                    ->where('ItemNo', '=', $request->itemCode)
                    ->first();

        //COMPUTATION TOTAL AMOUNT
        $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

        //CONDITION WITH THE COMPANY
        if ($request->chainCode == "RDS"){
            $company = 5; //AHLC
        }else{
            $company = 4; //EPC
        }

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        //PUTTING IT INTO THE OBJECT FOR THE MODEL
        $input = new PullOutModel();
        $input->plID = $request->id;
        $input->chainCode = $request->chainCode;
        $input->company = $company;
        $input->branchName = $request->branchName;
        $input->brand = $request->brand;
        $input->transactionType = $request->transactionType;
        $input->boxNumber = $request->boxNumber;
        $input->boxLabel = $request->boxLabel;
        $input->itemCode = $request->itemCode;
        $input->quantity = $request->quantity;
        $input->amount = $amount;
        $input->status = 'unprocessed';
        $input->dateTime = $date;

        //SAVING
        $input->save();
        return response()->json($input);

    }

    public function generatePDF(Request $request){

        //GET THE ID
        $id = $request->input('plID');

        if($request->company == "EPC"){
            //GET THE ONLY NEEDED SINGLE DATA AND WILL BE USE THE QUANTITY AND TOTAL AMOUNT
            $tempdata = DB::table('pullOutBranchTbl as a')
                            ->join('pullOutItemsTbl as b', 'a.id', '=', 'b.plID')
                            ->join('epc_items as c', 'b.itemCode', '=', 'c.ItemNo')
                            ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                            DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                            'b.amount', 'b.status', 'b.boxLabel',
                            'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand', 'a.company')
                            ->where('a.id', $id)
                            ->get();

            $emaildata = DB::table('pullOutBranchTbl as a')
                        ->join('pullOutItemsTbl as b', 'a.id', '=', 'b.plID')
                        ->join('epc_items as c', 'b.itemCode', '=', 'c.ItemNo')
                        ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                        DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                        'b.amount', 'b.status', 'b.boxLabel',
                        'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand')
                        ->where('a.id', $id)
                        ->where('b.status', 'edited')
                        ->get();

            //GETTING THE ONLY BOX LABEL GROUP AND THE SUB TOTAL OF QUANTITY AND AMOUNT
            $data = DB::table('pullOutItemsTbl as a')
                            ->select('a.brand',
                                DB::raw('SUM(a.quantity) as quantity_total'),
                                DB::raw('SUM(a.amount) as amount_total')
                            )
                            ->where('a.plID', $id)
                            ->groupBy('a.brand')
                            ->get();

            //GETTING THE BOX COUNT
            $box = DB::table('pullOutItemsTbl')
                    ->select('boxLabel')
                    ->where('plID', $id)
                    ->groupBy('boxLabel')
                    ->get();
        } else if($request->company == "NBFI"){
            //GET THE ONLY NEEDED SINGLE DATA AND WILL BE USE THE QUANTITY AND TOTAL AMOUNT
            $tempdata = DB::table('pullOutBranchTblNBFI as a')
                            ->join('pullOutItemsTblNBFI as b', 'a.id', '=', 'b.plID')
                            ->join('nbfi_items as c', 'b.itemCode', '=', 'c.ItemNo')
                            ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                            DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                            'b.amount', 'b.status', 'b.boxLabel',
                            'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand', 'a.company')
                            ->where('a.id', $id)
                            ->get();

            $emaildata = DB::table('pullOutBranchTblNBFI as a')
                            ->join('pullOutItemsTblNBFI as b', 'a.id', '=', 'b.plID')
                            ->join('nbfi_items as c', 'b.itemCode', '=', 'c.ItemNo')
                            ->select('a.chainCode', 'a.branchName', 'a.transactionType',
                            DB::raw('CAST(a.dateTime AS DATE) as date'), 'b.quantity',
                            'b.amount', 'b.status', 'b.boxLabel',
                            'c.ItemDescription as itemDescription', 'b.itemCode', 'b.brand')
                            ->where('a.id', $id)
                            ->where('b.status', 'edited')
                            ->get();

            //GETTING THE ONLY BOX LABEL GROUP AND THE SUB TOTAL OF QUANTITY AND AMOUNT
            $data = DB::table('pullOutItemsTblNBFI as a')
                            ->select('a.brand',
                                DB::raw('SUM(a.quantity) as quantity_total'),
                                DB::raw('SUM(a.amount) as amount_total')
                            )
                            ->where('a.plID', $id)
                            ->groupBy('a.brand')
                            ->get();
            //GETTING THE BOX COUNT
            $box = DB::table('pullOutItemsTblNBFI')
                    ->select('boxLabel')
                    ->where('plID', $id)
                    ->groupBy('boxLabel')
                    ->get();
        }

        //GETTING THE DRIVERS
        $drivers = DB::table('driverMaintenance')
                    ->select('name', 'position')
                    ->where('status', 'Active')
                    ->get();



        // var_dump($box);
        $boxCount = $box->count(); //BOX COUNT

        $length = $tempdata->count(); // LENGTH OF THE DATA

        $totalQuantity = 0;
        $totalAmount = 0;

        $statusCount = false;
        //COMPUTATION FOR THE TOTAL OF QUANTITY AND AMOUNT
        for ($i = 0; $i < $length; $i++){
            $totalQuantity = $totalQuantity + $tempdata[$i]->quantity;
            $totalAmount = $totalAmount + $tempdata[$i]->amount;

            if($tempdata[$i]->status == "edited"){
                $statusCount = true;
            }

        }

        $tempdate = $tempdata[0]->date;
        //FORMATTING DATE
        $formattedDateStart = date("F j, Y", strtotime($request->dateStart));
        $formattedDateEnd = date("F j, Y", strtotime($request->dateEnd));
        $formattedDate = date("F j, Y", strtotime($tempdate));

        //FORMATTING AMOUNT
        $formattedAmount = number_format($totalAmount, 2, '.', ',');
        // var_dump($boxCount);
        if($request->regenerate == "regenerate"){
            //TRANSFERRING INTO ARRAY TO BE EASY ACCESS ON SINGLE DATA
            $info = [
                'name' => $request->name,
                'boxCount' => $boxCount,
                'totalQuantity' => $totalQuantity,
                'totalAmount' => $formattedAmount,
                'date' => $formattedDate,
                'branchName' => $tempdata[0]->branchName,
                'chainCode' => $tempdata[0]->chainCode,
                'dateStart' => $formattedDateStart,
                'dateEnd' => $formattedDateEnd,
                'company' => $tempdata[0]->company
            ];
        }else{
            //TRANSFERRING INTO ARRAY TO BE EASY ACCESS ON SINGLE DATA
            $info = [
                'name' => $request->name,
                'boxCount' => $boxCount,
                'totalQuantity' => $totalQuantity,
                'totalAmount' => $formattedAmount,
                'date' => $formattedDate,
                'branchName' => $tempdata[0]->branchName,
                'chainCode' => $tempdata[0]->chainCode,
                'dateStart' => $request->dateStart,
                'dateEnd' => $request->dateEnd,
                'company' => $tempdata[0]->company
            ];
        }


        //CONVERTING IT INTO STRING FOR THE FILE NAME
        $string = strval($tempdata[0]->branchName);
        $today = date('Y-m-d');

        //CONDITION OF PDF TO BE SHOW
        //CPO - BranchDisposal
        if($tempdata[0]->transactionType == "CPO Item for Disposal in the Store c/o Supervisor"){
            $file = "itemDisposal";
        }
        //CPO - Store
        else if($tempdata[0]->transactionType == "CPO for Transfer to Another Store"){
            $file = "pullOutLetter";
        }
        //CPO - Warehouse(DC)
        else{
            $file = "directPullOut";
        }

        //LOADING IT INTO THE PDF
        $pdf = PDF::loadView($file, array('data' => $data, 'drivers' => $drivers),  $info)->setPaper('legal','portrait');

        if($statusCount == true){
            $viewToEmail = "EmailApprovedwithChanges";
            $tempdata = $emaildata;
        }else{
            $viewToEmail = "EmailApproved";
        }

        $boxLabels = '';
        $itemCodes = '';
        $itemDescriptions = '';
        $brands = '';
        $quantities = '';

        foreach ($tempdata as $data) {
            $boxLabels .= $data->boxLabel . ',';
            $itemCodes .= $data->itemCode . ',';
            $itemDescriptions .= $data->itemDescription . ',';
            $brands .= $data->brand . ',';
            $quantities .= $data->quantity . ',';
        }

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        $promoName = DB::table('users')
                        ->select('name')
                        ->where('email', $request->email)
                        ->first()->name;


        //SEND EMAIL IF APPROVED
        $mail = array(
            'transactionID' => $request->plID,
            'status' => $request->status,
            'email' => $request->email,
            'name' => $promoName,
            'viewToEmail' => $viewToEmail,
            'adminName' => $name,
            'viewEmail' => $viewToEmail,
            'chainCode' => $tempdata[0]->chainCode,
            'branchName' => $tempdata[0]->branchName,
            'transactionType' => $tempdata[0]->transactionType,
            'boxLabels' => $boxLabels,
            'itemCodes'  => $itemCodes,
            'itemDescriptions' => $itemDescriptions,
            'brands' => $brands,
            'quantities' => $quantities,
        );

        if($request->regenerate == "generate")
            Mail::to($request->email)->send(new MailNotify($mail));

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'Generate PDF';
        $log->table_affected = 'generatePDF';
        $log->new_data = json_encode($request->all());
        $log->save();

        return $pdf->stream($today.' '.$string.'.pdf');
    }

    public function sendDeniedBranch(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        if($request->company == "EPC"){
            $denied = DB::select('UPDATE pullOutBranchTbl
                            SET editedBy = \''.$name.'\',
                            updated_at = \''.$date.'\',
                            status = "denied"
                            WHERE id = \''.$request->id.'\' ');
            $table_affected = "EPC";
            $old_data = PullOutBranchModel::find($request->id);
        } else if($request->company == "NBFI"){
            $denied = DB::select('UPDATE pullOutBranchTblNBFI
                            SET editedBy = \''.$name.'\',
                            updated_at = \''.$date.'\',
                            status = "denied"
                            WHERE id = \''.$request->id.'\' ');
            $table_affected = "NBFI";
            $old_data = PullOutBranchModelNBFI::find($request->id);

        }

        $reasons = DB::table('reasonMaintenance')
                    ->select('reasonLabel')
                    ->get();

        $flag = true;
        for ($ctr = 0; $ctr < $reasons->count(); $ctr++){
            if(strtolower($reasons[$ctr]->reasonLabel) == strtolower($request->reason)){
                $flag = false;
                break;
            }
        }
        if($flag){

            $input = new EpcReasonModel();
            $input->reasonLabel = ucfirst($request->reason);
            $input->dateTime = $date;
            $input->company = $request->company;
            $input->status = 'Active';

            $input->save();

            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $request->userID;
            $log->action_type = 'insert';
            $log->table_affected = 'reasonmaintenance';
            $log->new_data = json_encode($request->all());
            $log->save();

        }


        $name = DB::table('users')
                    ->select('name')
                    ->where('id', $request->userID)
                    ->first()->name;

        $oldData = $old_data->toArray(); // Retrieve the old data before the update

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'denied';
        $log->table_affected = $table_affected;
        $log->old_data = json_encode($oldData);
        $log->new_data = json_encode($request->all());
        $log->save();

        $data = array(
            'transactionID' => $request->id,
            'name' => $request->promoName,
            'status' => 'Denied',
            'reason' => $request->reason,
            'adminName' => $name
        );

        $res = Mail::to($request->email)->send(new MailNotify($data));

        return response()->json($reasons);

    }

    public function savePullOutBranchRequest(Request $request){

        //CONDITION WITH THE COMPANY
        // if ($request->chainCode == "RDS"){
        //     $company = 5; //AHLC
        // }else{
        //     $company = 4; //EPC
        // }

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        if($request->companyType == 'EPC' || $request->companyType == 'AHLC')
            $branch = new PullOutBranchModel();
        else if($request->companyType == 'NBFI' || $request->companyType == 'ASC' || $request->companyType == 'CMC')
            $branch = new PullOutBranchModelNBFI();

        $branch->chainCode = $request->chainCode;
        $branch->company = $request->companyType;
        $branch->branchName = $request->branchName;
        $branch->transactionType = $request->transactionType;
        $branch->status = $request->status;
        $branch->dateTime = $date;
        $branch->promoEmail = $request->email;
        $branch->SLA_count = "15";
        $branch->SLA_status = "In Time";
        //SAVING
        $branch->save();

        $dataID = DB::table('users')
                    ->select('id')
                    ->where('email', $request->email)
                    ->first();

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $dataID->id;
        $log->action_type = 'insert';
        $log->table_affected = 'pullOutBranchTbl';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($branch);

    }

    public function savePullOutItemRequest(Request $request){

            if($request->quantity != 0 || $request->status == 'draft'){
                //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            if($request->companyType == 'EPC' || $request->companyType == 'AHLC'){
                $item = new PullOutItemModel();
                $price = DB::table('epc_items')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $request->itemCode)
                            ->first();
                $table_affected = 'pullOutItemsTbl';
            }
            else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC'){
                $item = new PullOutItemModelNBFI();
                $price = DB::table('nbfi_items')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $request->itemCode)
                            ->first();
                $table_affected = 'pullOutItemsTblNBFI';
            }

            //COMPUTATION TOTAL AMOUNT
            $amount = floatval($price->EffectivePrice) * floatval($request->quantity);

            $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

            $item->plID = $request->plID;
            $item->brand = $request->brand;
            $item->boxNumber = $request->boxNumber;
            $item->boxLabel = $request->boxLabel;
            $item->itemCode = $request->itemCode;
            $item->quantity = $request->quantity;
            $item->amount = $amount;
            $item->status = $request->status;
            $item->dateTime = $date;

            //SAVING
            $item->save();

            $dataID = DB::table('users')
                        ->select('id')
                        ->where('email', $request->email)
                        ->first();



            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $dataID->id;
            $log->action_type = 'insert';
            $log->table_affected = $table_affected;
            $log->new_data = json_encode($request->all());
            $log->save();
        }else{
            $item = [];
        }

        return response()->json($item);

    }

    public function addNewBranch(Request $request){

        if($request->company == "EPC")
        $input = new EpcBranchModel();
        else
        $input = new NbfiBranchModel();

        $input->chainCode = $request->chainCode;
        $input->branchCode = strtoupper($request->branchCode);
        $input->branchName = strtoupper($request->branchName);
        $input->company = $request->companyType;
        $input->status = 'Active';

        $input->save();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'epcbrandsmaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewBrand(Request $request){

        if($request->company == "EPC"){
            $data = EpcBrandModel::orderBy('id', 'desc')->first()->id;
            $input = new EpcBrandModel();
            $table_affected = 'epcbrandsmaintenance';

        }else if($request->company == "NBFI"){
            $data = NbfiBrandModel::orderBy('id', 'desc')->first()->id;
            $input = new NbfiBrandModel();
            $table_affected = 'nbfibrandsmaintenance';

        }

        $id = $data + 1;

        $input->id = $id;
        $input->brandNames = strtoupper($request->brandName);
        $input->status = 'Y';

        $input->save();

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = $table_affected;
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewDriver(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $input = new EpcDriverModel();
        $input->name = ucwords($request->name);
        $input->position = $request->position;
        $input->dateTime = $date;
        $input->status = 'Active';

        $input->save();

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'drivermaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }

    public function addNewReason(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $input = new EpcReasonModel();
        $input->reasonLabel = ucfirst($request->reasonLabel);
        $input->dateTime = $date;
        $input->status = 'Active';

        $input->save();

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'reasonmaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($input);
    }



    public function generateReport(Request $request){

        $name = $request->name;
        $company = $request->company;
        $date = $request->date;

        $title = 'Pull Out Letter';

        $meta = [
            'Name' => $name,
            'Company' => $company,
            'Date' => $date
        ];

        $queryBuilder = DB::table('pullOutTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.id')
                        ->select(DB::raw(('(SELECT shortName FROM companyTbl WHERE id = a.company) as company')),'a.plID', 'a.chainCode', 'a.branchName', 'a.amount',
                        'a.brand', 'a.transactionType', DB::raw('CAST(a.dateTime AS DATE) as date'),
                        DB::raw('TIME(dateTime) as time'))
                        ->distinct()
                        ->get();

        $columns = [
            'Company' => 'company',
            'Chain Code' => 'chainCode',
            'Branch Name' => 'branchName',
            'Amount' => 'amount'

        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->stream();

    }

    public function deleteDraft(Request $request){

        if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){
            $draft = DB::select('DELETE FROM pullOutBranchTblNBFI WHERE id = \''.$request->id.'\'');
        }else if($request->company == "EPC" || $request->company == "AHLC"){
            $draft = DB::select('DELETE FROM pullOutBranchTbl WHERE id = \''.$request->id.'\'');
        }

        return response()->json(['message'=>'Success'], 200);
    }

    public function deleteUserBranch(Request $request){
        if($request->req == 'additional'){
            DB::table('userBranchMaintenance')->where('userID', $request->userID)->where('request', 'additional')
            ->where(function ($query) use ($request) {
                $query->where('company', $request->company)
                    ->where('chainCode', $request->chainCode)
                    ->where('branchName', $request->branchName);
            })->delete();
        } else if($request->req == 'remove') {
            DB::select("UPDATE userBranchMaintenance SET request = null WHERE userID = \"".$request->userID."\" AND company = \"".$request->company."\" AND chainCode = \"".$request->chainCode."\" AND branchName = \"".$request->branchName."\"");
        }
    }

    public function postUserBranch(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        // $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnd'))
        //             ->addDay(); // Add one day to the dateEnd

        // $dateEnd = Carbon::parse($request->input('dateEnd'))->format('Y-m-d');

        $promo = new UserBranchModel();

        $promo->userID = $request->userID;
        $promo->company = $request->company;
        $promo->chainCode = $request->chainCode;
        $promo->branchName = $request->branchName;
        $promo->created_date = $date;
        // $promo->date_end = $dateEnd;
        $promo->status = 'Activated';
        $promo->permanent = true;


        $promo->save();

        switch ($request->company) {
                case "NBFI":
                $company = 1;
                break;
                case "ASC":
                $company = 2;
                break;
                case "CMC":
                $company = 3;
                break;
                case "EPC":
                $company = 4;
                break;
                case "AHLC":
                $company = 5;
                break;

                default:
                break;
            }
        DB::select("UPDATE users SET status = 'Active', company = \"".$company."\" WHERE id = \"".$request->userID."\"");

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->user;
        $log->action_type = 'insert';
        $log->table_affected = 'userBranchMaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();

        return response()->json($promo);
    }

    public function upload(Request $request)
    {


        $branchName = str_replace(' ', '-', $request->branchName);

        if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){
            if ($request->image) {
                $image = $request->image;
                $imageName = $request->transactionID . '-' . $branchName . '-' . time() . '.' . $image->getClientOriginalExtension();

                $filePath = public_path('uploads/NBFI');

                $img = Image::make($image->path());
                $img->resize(1280, 720, function ($const) {
                    $const->aspectRatio();
                })->save($filePath.'/'.$imageName);

                // $image->move($filePath, $imageName);

                $docImage = new ImageBranchModel();
                $docImage->transactionID = $request->transactionID;
                $docImage->company = "NBFI";
                $docImage->path = $imageName;

                $docImage->save();

                return $imageName;
            }
        }else if ($request->company == "EPC" || $request->company == "AHLC"){
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $request->transactionID . '-' . $branchName . '-' . time() . '.' . $image->getClientOriginalExtension();


                $filePath = public_path('uploads/EPC');

                $img = Image::make($image->path());
                $img->resize(1280, 720, function ($const) {
                    $const->aspectRatio();
                })->save($filePath.'/'.$imageName);

                // $image->move(public_path('uploads/EPC'), $imageName);

                $docImage = new ImageBranchModel();
                $docImage->transactionID = $request->transactionID;
                $docImage->company = "EPC";
                $docImage->path = $imageName;

                $docImage->save();

                return $imageName;
            }
        }
        return "No Image found";
    }

    public function postRemarks(Request $request){

        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

        $remarks = new RemarksModel();

        $remarks->remarksName = $request->remarksName;
        $remarks->date = $date;

        $remarks->save();

        return response()->json($remarks);
    }

    public function postPromoUserBranch(Request $request){
        // print_r($request->all());
        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        if($request->req === "remove")
            DB::select("UPDATE userBranchMaintenance SET request = 'remove' WHERE id = \"".$request->id."\"");

        else if ($request->req === "additional"){
            $promoBranch = new UserBranchModel();
            $promoBranch->userID = $request->userID;
            $promoBranch->company = $request->company;
            $promoBranch->chainCode = $request->chainCode;
            $promoBranch->branchName = $request->branchName;
            $promoBranch->created_date = $date;
            $promoBranch->request = $request->req;
            $promoBranch->status = 'Activated';
            $promoBranch->permanent = false;

            $promoBranch->save();
        }

        $log = new TransactionModel();
        $log->dateTime = $date;
        $log->userID = $request->userID;
        $log->action_type = 'insert';
        $log->table_affected = 'userBranchMaintenance';
        $log->new_data = json_encode($request->all());
        $log->save();


    }

    public function postDatesLetter(Request $request){

        $dateStart = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateStarted'))
                    ->addDay(); // Add one day to the dateStart

        $dateEnd = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('dateEnded'))
                    ->addDay(); // Add one day to the dateEnd

        $letterDate = new pullOutLetterDates();
        $letterDate->plID = $request->id;
        $letterDate->company = $request->company;
        $letterDate->authorizedPersonnel = $request->authorizedPersonnel;
        $letterDate->dateStart = $dateStart;
        $letterDate->dateEnd = $dateEnd;

        $letterDate->save();

        return response()->json($dateEnd);

    }

}
