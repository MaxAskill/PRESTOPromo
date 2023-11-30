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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{

    public function savePullOutBranchRequest(Request $request){

            $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH

            //SELECTING MODEL AND TABLE BY COMPANY
            if($request->companyType == 'EPC' || $request->companyType == 'AHLC'){
                $branch = new PullOutBranchModel();
                $tbItem = 'epc_items_barcode';
                $table_affected = 'pullOutBranchTbl';
            }
            else if($request->companyType == 'NBFI' || $request->companyType == 'ASC' || $request->companyType == 'CMC'){
                $branch = new PullOutBranchModelNBFI();
                $tbItem = 'nbfi_items_barcode';
                $table_affected = 'pullOutBranchTblNBFI';
            }

            //FIELDS FOR SAVING ON DB
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

            //FILTERING FOR SAVING ON LOGS
            $new_data = array(
                'id' => $branch->id,
                'branchName' => $request->branchName,
                'promoEmail' => $request->email,
                'status' => $request->status
            );
            //GETTING THE ID OF THE USER
            $dataID = DB::table('users')
                        ->select('id')
                        ->where('email', $request->email)
                        ->first();

            //FIELDS ON TRANSACTION LOGS
            $log = new TransactionModel();
            $log->dateTime = $date;
            $log->userID = $dataID->id;
            $log->action_type = 'insert';
            $log->table_affected = $table_affected;
            $log->new_data = json_encode($new_data);

            //SAVING
            $log->save();

            //TRANSFERING THE OBJECT
            $items = $request->items;
            $boxes = $request->boxes;

            foreach($items as $item){ //LOOPING BY ITEMS
                foreach($boxes as $box){ //LOOPING BY BOX
                    if($box['id'] == $item['boxNumber']){ //CONDITION FOR BOX LABEL

                        $price = DB::table($tbItem)
                                    ->select('EffectivePrice')
                                    ->where('ItemNo', '=', $item['code'])
                                    ->first(); //GETTING THE PRICE PER CODE

                        //INITIALIZE FOR THE MODEL OF ITEM
                        $itemSave = $request->companyType == 'EPC' || $request->companyType == 'AHLC' ? new PullOutItemModel() : new PullOutItemModelNBFI();

                        //COMPUTATION TOTAL AMOUNT
                        $amount = floatval($price->EffectivePrice) * floatval($item['quantity']);

                        //FIELDS FOR ITEMS
                        $itemSave->plID = $branch->id;
                        $itemSave->brand = $item['categorybrand'];
                        $itemSave->boxNumber = $item['boxNumber'];
                        $itemSave->boxLabel = $box['boxLabel'];
                        $itemSave->itemCode = $item['code'];
                        $itemSave->quantity = $item['quantity'];
                        $itemSave->amount = $amount;
                        $itemSave->status = $request->status;
                        $itemSave->dateTime = $date;

                        //SAVING
                        $itemSave->save();

                        //TABLE AFFECTED FOR LOGS
                        $table_affected = $request->companyType == 'EPC' || $request->companyType == 'AHLC' ? 'pullOutBranchTbl' : 'pullOutBranchTblNBFi';

                        //FIELDS FOR NEW DATA FOR LOGS
                        $new_data = array(
                            'ItemCode' => $item['code'],
                            'BoxNumber' => $box['boxLabel'],
                            'Quantity' => $item['quantity'],
                            'Amount' => $amount
                        );

                        //FIELDS FOR TRANSACTION LOGS
                        $log = new TransactionModel();
                        $log->dateTime = $date;
                        $log->userID = $dataID->id;
                        $log->action_type = 'insert';
                        $log->table_affected = $table_affected;
                        $log->new_data = json_encode($new_data);

                        //SAVING
                        $log->save();
                    }
                }
            }


            return response()->json($branch->id);


    }

    public function savePullOutItemRequest(Request $request){

            if($request->quantity != 0 || $request->status == 'draft'){
                //GETTING THE EFFECTIVE PRICE OF THE SPECIFIC ITEM
            if($request->companyType == 'EPC' || $request->companyType == 'AHLC'){
                $item = new PullOutItemModel();
                $price = DB::table('epc_items_barcode')
                            ->select('EffectivePrice')
                            ->where('ItemNo', '=', $request->itemCode)
                            ->first();
                $table_affected = 'pullOutItemsTbl';
            }
            else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC'){
                $item = new PullOutItemModelNBFI();
                $price = DB::table('nbfi_items_barcode')
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
            if ($request->hasFile('image')) {
                $image = $request->image;
                $imageName = $request->transactionID . '-' . $branchName . '-' . $request->uID .'-'. time() . '.' . $image->getClientOriginalExtension();

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
                // $image = $request->file('image');
                $image = $request->image;
                $imageName = $request->transactionID . '-' . $branchName . '-' . $request->uID .'-'. time() . '.webp';


                $filePath = public_path('uploads/EPC');

                $img = Image::make($image->path());
                $img->resize(1280, 720, function ($const) {
                    $const->aspectRatio();
                })->save($filePath.'/'.$imageName, 80, 'webp');

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
        $date = now()->timezone('Asia/Manila'); // GETTING THE TIME ZONE IN PH
        if($request->req === "remove")
            DB::select("UPDATE userBranchMaintenance SET request = 'remove' WHERE id = \"".$request->id."\"");

        else if ($request->req === "additional"){
            // $date_start = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('date_start'));
            // $date_end = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('date_end'));

            $promoBranch = new UserBranchModel();
            $promoBranch->userID = $request->userID;
            $promoBranch->company = $request->company;
            $promoBranch->chainCode = $request->chainCode;
            $promoBranch->branchName = $request->branchName;
            $promoBranch->date_start = $request->date_start;
            $promoBranch->date_end = $request->date_end;
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
    public function deleteImage(Request $request){

        $company = $request->company;


        if ($company == "ASC" || $company == "CMC" || $company == "NBFI") {
            $fileToDelete = public_path('uploads/NBFI/' . $request->path);
            $company = "NBFI";
        } else {
            $fileToDelete = public_path('uploads/EPC/' . $request->path);
            $company = "EPC";
        }

        if (File::exists($fileToDelete)) {
            File::delete($fileToDelete);
            // Optionally, delete the associated database record.
            // Return a success response.
            DB::table('imageBranchDocTbl')
            ->where('path', $request->path)
            ->where('company', $company) // Add this line to filter by company
            ->delete();
            return response()->json('Success Delete');
        } else {
            // File doesn't exist, return an error response.
            return response()->json($fileToDelete); // File doesn't exist, return a 404 status.
        // }
        }

        // if (Storage::disk('public')->exists($fileToDelete)) {
        //     Storage::disk('public')->delete($fileToDelete);

        //     // Optionally, you can also delete the associated database record if needed.
        //     // Example:
        //     // DB::table('imageBranchDocTbl')->where('transactionID', $request->id)->delete();

        //     return response()->json('Success Delete');
        // } else {
        //     return response()->json($fileToDelete); // File doesn't exist, return a 404 status.
        // }

    }

}
