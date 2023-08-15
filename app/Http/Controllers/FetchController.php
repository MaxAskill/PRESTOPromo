<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PullOutModel;
use App\Models\PullOutBranchModel;
use App\Models\PullOutItemModel;
use Illuminate\Support\Facades\Storage;

class FetchController extends Controller
{
    //

    //GETTING THE LAST ID IN THE PULL OUT TBL
    public function fetchLastID(){
        $data = PullOutModel::orderBy('plID', 'desc')->first()->plID;

        return response()->json($data);
    }

    public function fetchLastIDBranch(){
        $data = PullOutBranchModel::orderBy('id', 'desc')->first()->id;

        return response()->json($data);
    }

    public function fetchChain(Request $request){

        $company = $request->company;
        // $data = DB::table('epcbranchmaintenance')
        //             ->select('chainCode')
        //             ->distinct()
        //             ->orderby('chainCode')
        //             ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'EPC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'AHLC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "ASC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'ASC')
                    ->orWhere('chainCode', 'SM DEPT. STORE')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "CMC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'CMC')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', 'NBFI')
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('chainCode')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainByUser(Request $request){

        $company = $request->company;
        // $data = DB::table('epcbranchmaintenance')
        //             ->select('chainCode')
        //             ->distinct()
        //             ->orderby('chainCode')
        //             ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('b.userID', $request->userID)
                    ->where('a.company', 'EPC')
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.chainCode')
                    ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('b.userID', $request->userID)
                    ->where('a.company', 'AHLC')
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.chainCode')
                    ->get();
        }else if($company == "ASC"){
            $data = DB::table('nbfibranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('b.userID', $request->userID)
                    ->where('a.company', 'ASC')
                    // ->orWhere('a.chainCode', 'SM DEPT. STORE')
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.chainCode')
                    ->get();
        }else if($company == "CMC"){
            $data = DB::table('nbfibranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('b.userID', $request->userID)
                    ->where('a.company', 'CMC')
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.chainCode')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('nbfibranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('b.userID', $request->userID)
                    ->where('a.company', 'NBFI')
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.chainCode')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainName(Request $request){

        $company = $request->company;

        // $data = DB::table('epcbranchmaintenance')
        //         ->select('branchName')
        //         ->where('chainCode', $request->chainCode)
        //         ->where('status', 'Active')
        //         ->distinct()
        //         ->orderby('branchName')
        //         ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance')
                ->select('branchName')
                ->where('chainCode', $request->chainCode)
                ->where('status', 'Active')
                ->distinct()
                ->orderby('branchName')
                ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance')
                ->select('branchName')
                ->where('chainCode', $request->chainCode)
                ->where('status', 'Active')
                ->distinct()
                ->orderby('branchName')
                ->get();
        }else if($company == "ASC"){

            // $arrayChainCode = [$request->chainCode, "SM DEPT. STORE"];
            $data1 = DB::table('nbfibranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();

            $data2 = DB::table('nbfibranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
            $data = $data1->union($data2);
        }else if($company == "CMC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('branchName')
                    ->where('chainCode', $request->chainCode)
                    ->where('status', 'Active')
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainNameByUser(Request $request){

        $company = $request->company;

        $data = DB::table('epcbranchmaintenance as a')
                ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                ->select('a.branchName')
                ->where('b.userID', $request->userID)
                ->where('a.chainCode', $request->chainCode)
                ->where(function ($query) {
                    $query->where('request', null)
                        ->orWhere('request', 'remove');
                })
                ->where('a.status', 'Active')
                ->distinct()
                ->orderby('a.branchName')
                ->get();
        if($company == "EPC"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                ->select('a.branchName')
                ->where('b.userID', $request->userID)
                ->where('a.chainCode', $request->chainCode)
                ->where(function ($query) {
                    $query->where('request', null)
                        ->orWhere('request', 'remove');
                })
                ->where('a.status', 'Active')
                ->distinct()
                ->orderby('a.branchName')
                ->get();
        }else if($company == "AHLC"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                ->select('a.branchName')
                ->where('b.userID', $request->userID)
                ->where('a.chainCode', $request->chainCode)
                ->where(function ($query) {
                    $query->where('request', null)
                        ->orWhere('request', 'remove');
                })
                ->where('a.status', 'Active')
                ->distinct()
                ->orderby('a.branchName')
                ->get();
        }else if($company == "ASC"){
            $data1 = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                    ->select('a.branchName')
                    ->where('b.userID', $request->userID)
                    ->where('a.chainCode', $request->chainCode)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.branchName')
                    ->get();

            $data2 = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                    ->select('a.branchName')
                    ->where('b.userID', $request->userID)
                    ->where('a.chainCode', $request->chainCode)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.branchName')
                    ->get();
            $data = $data2->union($data1);
        }else if($company == "CMC"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                    ->select('a.branchName')
                    ->where('b.userID', $request->userID)
                    ->where('a.chainCode', $request->chainCode)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.branchName')
                    ->get();
        }else if($company == "NBFI"){
            $data = DB::table('epcbranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.branchName', '=', 'a.branchName')
                    ->select('a.branchName')
                    ->where('b.userID', $request->userID)
                    ->where('a.chainCode', $request->chainCode)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->where('a.status', 'Active')
                    ->distinct()
                    ->orderby('a.branchName')
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchBrands(Request $request){

        // $chainCode = request('chainCode');
        if($request->companyType == 'EPC' || $request->companyType == 'AHLC')
            $data = DB::table('epcbrandsmaintenance')
                    ->select('brandNames')
                    ->where('id', $request->brandCode)
                    ->where('status', 'Y')
                    ->distinct()
                    ->orderby('brandNames')
                    ->get();
        else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC')
            $data = DB::table('nbfibrandsmaintenance')
                    ->select('brandNames')
                    ->where('id', $request->brandCode)
                    ->where('status', 'Y')
                    ->distinct()
                    ->orderby('brandNames')
                    ->get();

        return response()->json($data);
    }

    public function fetchCategory(Request $request){

        $data = DB::table('epcbrandscategory')
                ->select('categoryName')
                ->where('brandName', $request->brandName)
                ->distinct()
                ->orderby('categoryName')
                ->get();

        return response()->json($data);
    }

    public function fetchBranch(){
        $data = DB::table('branch_maintenance')
                ->select('branchCode', 'branchName')
                ->get();

        return response()->json($data);
    }

    public function fetchItems(Request $request){
        if($request->barcode == "item_16"){
            $data1 = DB::table('epc_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', 'LIKE', '%'.$request->ItemNo)
                ->get();

            $data2 = DB::table('epc_items')
                    ->select('ItemNo', 'ItemDescription')
                    ->where('ItemNo', 'LIKE', $request->ItemNo.'%')
                    ->get();
        }else{
            $data1 = DB::table('epc_items_barcode')
                ->select('Barcode','ItemNo', 'ItemDescription')
                ->where('Barcode', 'LIKE', '%'.$request->ItemNo)
                ->get();

            $data2 = DB::table('epc_items_barcode')
                    ->select('Barcode','ItemNo', 'ItemDescription')
                    ->where('Barcode', 'LIKE', $request->ItemNo.'%')
                    ->get();
        }

        $data = $data1->union($data2);

        return response()->json($data);
    }

    public function compareItemCode(Request $request){
        if($request->companyType == 'EPC' || $request->companyType == 'AHLC')
            $data = DB::table('epc_items')
                    ->select('ItemNo', 'ItemDescription', 'StyleCode')
                    ->where('ItemNo', '=' ,$request->ItemNo)
                    ->get();

        else if($request->companyType == 'NBFI' || $request->companyType == 'CMC' || $request->companyType == 'ASC')
            $data = DB::table('nbfi_items')
                    ->select('ItemNo', 'ItemDescription', 'StyleCode')
                    ->where('ItemNo', '=' ,$request->ItemNo)
                    ->get();

        return response()->json($data);
    }

    public function fetchItemsNBFI(Request $request){

        if($request->barcode == "item_16"){
            $data1 = DB::table('nbfi_items')
                    ->select('ItemNo', 'ItemDescription')
                    ->where('ItemNo', 'LIKE', '%'.$request->ItemNo)
                    ->get();

            $data2 = DB::table('nbfi_items')
                    ->select('ItemNo', 'ItemDescription')
                    ->where('ItemNo', 'LIKE', $request->ItemNo.'%')
                    ->get();
        }else{
            $data1 = DB::table('nbfi_items_barcode')
                    ->select('Barcode as ItemNo', 'ItemDescription')
                    ->where('Barcode', 'LIKE', '%'.$request->ItemNo)
                    ->get();

            $data2 = DB::table('nbfi_items_barcode')
                    ->select('Barcode as ItemNo', 'ItemDescription')
                    ->where('Barcode', 'LIKE', $request->ItemNo.'%')
                    ->get();
        }


        $data = $data1->union($data2);

        return response()->json($data);
    }

    public function fetchItemsBarcode(Request $request){

        if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){
            $data = DB::table('nbfi_items_barcode')
                        ->select('ItemNo', 'ItemDescription')
                        ->where('Barcode', $request->ItemNo)
                        ->get();
        }else {
            $data = DB::table('epc_items_barcode')
                        ->select('ItemNo', 'ItemDescription')
                        ->where('Barcode', $request->ItemNo)
                        ->get();
        }


        return response()->json($data);
    }

    public function fetchPullOutRequest(){

        // $data = DB::table('pullOutTbl')
        //         ->select('plID', 'chainCode', 'branchName',
        //         'brand', 'transactionType', 'dateTime')
        //         ->distinct()
        //         ->get();
        // $data = DB::table('pullOutTbl as a')
        //         ->join('companyTbl as b', 'a.company', '=', 'b.id')
        //         ->select(DB::raw(('(SELECT shortName FROM companyTbl WHERE id = a.company) as company')),'a.plID', 'a.chainCode', 'a.branchName', 'a.amount',
        //         'a.brand', 'a.transactionType', DB::raw('CAST(a.dateTime AS DATE) as date'),
        //         DB::raw('TIME(dateTime) as time'))
        //         ->distinct()
        //         ->get();

        $data = DB::table('pullOutTbl as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.id')
                ->select('a.plID','a.branchName', 'a.transactionType',
                DB::raw('CAST(a.dateTime AS DATE) as date'),
                DB::raw('TIME(dateTime) as time'))
                ->distinct()
                ->where('status', '!=', 'deleted')
                ->orderBy('a.dateTime', 'desc')
                ->get();

        return response()->json($data);
    }

    public function fetchPullOutRequestUnprocessed(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){

          $data =  DB::table('pullOutBranchTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'SLA_count','SLA_status', 'b.shortName')
                            ->distinct()
                                ->where('a.status', 'unprocessed')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();
            // $data = DB::table('pullOutBranchTbl as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->join('companyTbl as c', 'a.company', '=', 'c.shortName')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(c.name, " (", c.shortName,")") as company'),
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail', 'c.shortName')
            //     ->distinct()
            //     ->where('a.status', 'unprocessed')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }else if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){

            $data =  DB::table('pullOutBranchTblNBFI as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'SLA_count','SLA_status', 'b.shortName')
                            ->distinct()
                                ->where('a.status', 'unprocessed')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();
            // $data = DB::table('pullOutBranchTblNBFI as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->join('companyTbl as c', 'a.company', '=', 'c.shortName')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(c.name, " (", c.shortName ,")") as company'),
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail', 'c.shortName')
            //     ->distinct()
            //     ->where('a.status', 'unprocessed')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }

        foreach ($data as $item){

            $item->status = "For Review";

            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }

        return response()->json($data);
    }

    public function fetchPullOutRequestEndorsement(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){

            $data =  DB::table('pullOutBranchTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'SLA_count','SLA_status', 'b.shortName')
                            ->distinct()
                                ->where('a.status', 'endorsement')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTbl as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->join('companyTbl as c', 'a.company', '=', 'c.shortName')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(c.name, " (", c.shortName,")") as company'),
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail', 'a.editedBy', 'c.shortName')
            //     ->distinct()
            //     ->where('a.status', 'endorsement')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }else if($request->company == "NBFI" || $request->company == "ASC" || $request->company == "CMC"){

            $data =  DB::table('pullOutBranchTblNBFI as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'SLA_count','SLA_status', 'b.shortName')
                            ->distinct()
                                ->where('a.status', 'endorsement')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTblNBFI as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->join('companyTbl as c', 'a.company', '=', 'c.shortName')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(c.name, " (", c.shortName ,")") as company'),
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail', 'a.editedBy', 'c.shortName')
            //     ->distinct()
            //     ->where('a.status', 'endorsement')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }

        foreach ($data as $item){

            $item->status = "For Approval";

            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }

        return response()->json($data);
    }

    public function fetchPullOutRequestUnprocessed2(){

        $data = DB::table('pullOutBranchTbl as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.id')
                ->select('a.id as plID','a.branchName', 'a.transactionType',
                DB::raw('CAST(a.dateTime AS DATE) as date'),
                DB::raw('TIME(dateTime) as time'))
                ->distinct()
                ->where('status', 'unprocessed')
                ->orderBy('a.dateTime', 'desc')
                ->get();

        return response()->json($data);
    }

    public function fetchPullOutRequestItem(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutItemsTbl')
                    ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity', 'editedBy', 'amount')
                    ->where('status', '!=', 'deleted')
                    ->where('plID', '=', $request->plID)
                    ->orderBy('boxLabel')
                    ->get();

            //GETTING THE BOX COUNT
            $box = DB::table('pullOutItemsTbl')
                    ->select('boxLabel')
                    ->where('plID', $request->plID)
                    ->groupBy('boxLabel')
                    ->get();

            $boxCount = $box->count();
            $totalItems = $data->count();
            $totalAmount = 0;
            foreach ($data as $item){
                $item->amount = number_format($item->amount, 2, '.', ',');
                $totalAmount = $totalAmount + floatval($item->amount);
            }

            $totalAmount = number_format($totalAmount, 2, '.', ',');

            $singleData = ['totalAmount' => $totalAmount, 'boxCount' => $boxCount, 'totalItems' => $totalItems];
            return response()->json([$data, $singleData]);

        }else if ($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC") {
            $data = DB::table('pullOutItemsTblNBFI')
                    ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity', 'editedBy', 'amount')
                    ->where('status', '!=', 'deleted')
                    ->where('plID', '=', $request->plID)
                    ->orderBy('boxLabel')
                    ->get();

            //GETTING THE BOX COUNT
            $box = DB::table('pullOutItemsTblNBFI')
                    ->select('boxLabel')
                    ->where('plID', $request->plID)
                    ->groupBy('boxLabel')
                    ->get();

            $boxCount = $box->count();
            $totalItems = $data->count();
            $totalAmount = 0;
            foreach ($data as $item){
                $item->amount = number_format($item->amount, 2, '.', ',');
                $totalAmount = $totalAmount + floatval($item->amount);
            }
            $totalAmount = number_format($totalAmount, 2, '.', ',');
            $singleData = ['totalAmount' => $totalAmount, 'boxCount' => $boxCount, 'totalItems' => $totalItems];
            return response()->json([$data, $singleData]);

        }

    }

    public function fetchPullOutRequestApproved(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){

            $data =  DB::table('pullOutBranchTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->join('pullOutLetterDates as d', 'a.id', '=', 'd.plID')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'd.authorizedPersonnel', 'd.dateStart', 'd.dateEnd')
                            ->distinct()
                                ->where('a.status', 'approved')
                                ->where('d.company', 'EPC')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTbl as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
            //     ->distinct()
            //     ->where('a.status', 'approved')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){

            $data =  DB::table('pullOutBranchTblNBFI as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->join('pullOutLetterDates as d', 'a.id', '=', 'd.plID')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy', 'a.promoEmail',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy',
                            'd.authorizedPersonnel', 'd.dateStart', 'd.dateEnd')
                            ->distinct()
                                ->where('a.status', 'approved')
                                ->where('d.company', 'NBFI')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTblNBFI as a')
            //     ->join('users as b', 'a.promoEmail', '=', 'b.email')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'b.name', 'a.promoEmail')
            //     ->distinct()
            //     ->where('a.status', 'approved')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }

        foreach ($data as $item){
            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }
        return response()->json($data);
    }

    public function fetchPullOutRequestDenied(Request $request){

        if($request->company == "EPC" || $request->company == "AHLC"){

            $data =  DB::table('pullOutBranchTbl as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy')
                            ->distinct()
                                ->where('a.status', 'denied')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTbl as a')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'company')
            //     ->where('status', 'denied')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){

            $data =  DB::table('pullOutBranchTblNBFI as a')
                        ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                        ->join('users as c', 'a.promoEmail', '=', 'c.email')
                        ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                            DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                            DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                            DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                            'dateTime', 'editedBy as reviewedBy', 'approvedBy',
                            DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy')
                            ->distinct()
                                ->where('a.status', 'denied')
                                ->orderBy('a.dateTime', 'desc')
                                ->get();

            // $data = DB::table('pullOutBranchTblNBFI as a')
            //     ->select('a.id as plID','a.branchName', 'a.transactionType',
            //     DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
            //     DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'company')
            //     ->where('status', 'denied')
            //     ->orderBy('a.dateTime', 'desc')
            //     ->get();
        }
        foreach ($data as $item){
            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }
        return response()->json($data);
    }
    public function fetchItemsRequest(Request $request){

        $data = DB::table('pullOutTbl')
                ->select('id','plID', 'boxNumber', 'boxLabel', 'brand', 'itemCode', 'quantity')
                ->where('status', '!=', 'deleted')
                ->where('plID', '=', $request->plID)
                ->orderBy('boxLabel')
                ->get();

        return response()->json($data);
    }
    public function fetchAllItemsRequest(Request $request){

        $id = $request->input('plID');

        if($request->company == "EPC" || $request->company == "AHLC"){
            $data = DB::table('pullOutBranchTbl as a')
                ->join('pullOutItemsTbl as b', 'a.id', '=', 'b.plID')
                ->select('a.branchName', 'b.brand', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'),'b.boxLabel', 'b.itemCode', 'b.quantity',
                        'a.status')
                ->whereIn('a.id', $id)
                ->get();

        foreach ($data as $item){
            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }
        return response()->json($data);
        }else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $data = DB::table('pullOutBranchTblNBFI as a')
                ->join('pullOutItemsTblNBFI as b', 'a.id', '=', 'b.plID')
                ->select('a.branchName', 'b.brand', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'),'b.boxLabel', 'b.itemCode', 'b.quantity',
                        'a.status')
                ->whereIn('a.id', $id)
                ->get();

        foreach ($data as $item){
            if($item->transactionType == 'CPO Item for Disposal in the Store c/o Supervisor'){
                $item->transactionType = 'CPO - BranchDisposal';
            }else if($item->transactionType == 'CPO for Transfer to Another Store'){
                $item->transactionType = 'CPO - Store';
            }else if($item->transactionType == 'CPO Back to WH via In-House Service'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via Chain Distribution Center'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH via 3rd Party Trucking'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'CPO Back to WH c/o Supervisor'){
                $item->transactionType = 'CPO - Warehouse';
            }else if($item->transactionType == 'Concess Direct Pull-Out'){
                $item->transactionType == 'CPO - Warehouse(DC)';
            }

        }
        return response()->json($data);
        // return response()->json($data);
        }


    }

    public function users(Request $request){

        $user = DB::table('users')
                ->select('id', 'position', 'name')
                ->where('email', $request->email)
                ->get();

        return response()->json($user);
    }

    public function getPromoName(Request $request){

        $email = "";
        if ($request->company == "EPC" || $request->company == "AHLC"){
            $email = DB::table('pullOutBranchTbl')
                        ->select('promoEmail')
                        ->where('id', $request->id)
                        ->first();
        } else if($request->company == "NBFI" || $request->company == "CMC" || $request->company == "ASC"){
            $email = DB::table('pullOutBranchTblNBFI')
                        ->select('promoEmail')
                        ->where('id', $request->id)
                        ->first();
        }


        $name = DB::table('users')
                ->select('name', 'email')
                ->where('email', $email->promoEmail)
                ->first();

        return response()->json($name);
    }

    public function getBranchStatus(Request $request){

        $data = DB::table('pullOutBranchTbl')
                ->select('status')
                ->where('id', $request->id)
                ->get();

        return response()->json($data);
    }

    public function getReasons(Request $request){

        if($request->company == "NBFI"){
            $data = DB::table('reasonMaintenance')
                        ->select('id', 'reasonLabel')
                        ->where('status', 'Active')
                        ->where('company', 'NBFI')
                        ->get();
        }else{
            $data = DB::table('reasonMaintenance')
                        ->select('id', 'reasonLabel')
                        ->where('status', 'Active')
                        ->where('company', 'EPC')
                        ->get();
        }


        return response()->json($data);
    }

    public function fetchCompany(Request $request){

        $company = DB::table('companyTbl')
                    ->select('id', 'shortName', 'name')
                    ->get();

        return response()->json($company);
    }
    public function fetchCompanyByUser(Request $request){

        $company = DB::table('companyTbl as a')
                    ->join('userBranchMaintenance as b', 'b.company', '=', 'a.shortName')
                    ->where('userID', $request->userID)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->select('a.id', 'a.shortName', 'a.name')
                    ->distinct()
                    ->get();

        return response()->json($company);
    }

    public function fetchUserRequestDraft(Request $request){

        $company = $request->company;

        $branchName = DB::table('userBranchMaintenance')
                        ->select('branchName')
                        ->where('userID', $request->userID)
                        ->first()->branchName;

        $data1 = DB::table('pullOutBranchTblNBFI as a')
                    ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'company', 'a.transactionType',
                        DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'status',
                        'dateTime')
                    ->where('status', 'draft')
                    ->where('branchName', $branchName);

        $data2 = DB::table('pullOutBranchTbl as a')
                ->select('a.id as plID', 'a.chainCode', 'a.branchName', 'company', 'a.transactionType',
                    DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                    DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'status',
                    'dateTime')
                ->where('status', 'draft')
                ->where('branchName', $branchName);

        $data = $data1->union($data2)
                    ->orderBy('dateTime', 'desc') // Order by the dummy column
                    ->get();

        return response()->json($data);
    }

    public function fetchUserRequestTransactionList(Request $request){

        $company = $request->company;

        $data1 = DB::table('pullOutBranchTblNBFI as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                ->join('users as c', 'a.promoEmail', '=', 'c.email')
                ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                    DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                    DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                    DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                    'dateTime', 'editedBy as reviewedBy', 'approvedBy',
                    DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy')
            ->where('a.status', '!=','draft')
            ->where('promoEmail', $request->promoEmail);

        $data2 = DB::table('pullOutBranchTbl as a')
                ->join('companyTbl as b', 'a.company', '=', 'b.shortName')
                ->join('users as c', 'a.promoEmail', '=', 'c.email')
                ->select('a.id as plID', 'a.chainCode', 'a.branchName', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.transactionType',
                    DB::raw('CONCAT(MONTHNAME(a.dateTime), " ", DATE_FORMAT(a.dateTime, "%d, %Y")) as date'),
                    DB::raw('CONCAT(MONTHNAME(a.updated_at), " ", DATE_FORMAT(a.updated_at, "%d, %Y")) as reviewedDate'),
                    DB::raw('DATE_FORMAT(a.dateTime, "%h:%i %p") as time'), 'a.status',
                    'dateTime', 'editedBy as reviewedBy', 'approvedBy',
                    DB::raw('CONCAT(MONTHNAME(a.approvedDate), " ", DATE_FORMAT(a.approvedDate, "%d, %Y")) as approvedDate'), 'c.name as createdBy')
            ->where('a.status', '!=', 'draft')
            ->where('promoEmail', $request->promoEmail);


        $data = $data1->union($data2)
            ->orderBy('dateTime', 'desc') // Order by the dummy column
            ->get();

        return response()->json($data);
    }

    public function fetchSameItem(Request $request){

        $company = $request->company;
        if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
           if($request->StyleCode === ' '){
            $data = DB::table('nbfi_items')
                    ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'Category')
                    ->where('ItemDescription', $request->ItemDescription)
                    ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'Category')
                    ->get();
           }else{
            $data = DB::table('nbfi_items')
                        ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'Category')
                        ->where('ItemDescription', $request->ItemDescription)
                        ->where('StyleCode', $request->StyleCode)
                        ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'Category')
                        ->get();
           }

        }else {
           if($request->StyleCode === ' '){

            $data = DB::table('epc_items')
                        ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'Category')
                        ->where('ItemDescription', $request->ItemDescription)
                        ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'Category')
                        ->get();
            }else{
            $data = DB::table('epc_items')
                        ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'Category')
                        ->where('ItemDescription', $request->ItemDescription)
                        ->where('StyleCode', $request->StyleCode)
                        ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'Category')
                        ->get();
            }
        }
        return response()->json($data);

    }

    public function fetchChainCode(Request $request){

        $company = $request->company;
        if($company == "ASC"){
            // $arrayChainCode = [$request->chainCode, "SM DEPT. STORE"];
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('chainCode',"SM DEPT. STORE")
                    ->where('status', 'Active')
                    ->orWhere('company', $company)
                    ->distinct()
                    ->orderby('branchName')
                    ->get();
            // $data = DB::table('nbfibranchmaintenance')
            //         ->select('chainCode')
            //         ->where('company', $company)
            //         ->distinct()
            //         ->get();
        }
        else if($company == "NBFI" || $company == "CMC"){
            $data = DB::table('nbfibranchmaintenance')
                    ->select('chainCode')
                    ->where('company', $company)
                    ->distinct()
                    ->get();

        }else {
            $data = DB::table('epcbranchmaintenance')
                    ->select('chainCode')
                    ->where('company', $company)
                    ->distinct()
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainCodeByUser(Request $request){

        $company = $request->company;
        if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
            $data = DB::table('nbfibranchmaintenance as a')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('userID', $request->userID)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->where('a.company', $company)
                    ->distinct()
                    ->get();

        }else {
            $data = DB::table('epcbranchmaintenance')
                    ->join('userBranchMaintenance as b', 'b.chainCode', '=', 'a.chainCode')
                    ->select('a.chainCode')
                    ->where('a.company', $company)
                    ->where('userID', $request->userID)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', 'remove');
                    })
                    ->distinct()
                    ->get();
        }

        return response()->json($data);
    }

    public function fetchChainCodeRegister(Request $request){

        $company = $request->company;
        // if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
            $data1 = DB::table('nbfibranchmaintenance')
                    ->select('chainCode');
                    // ->where('company', $company)
                    // ->distinct()
                    // ->get();

        // }else {
            $data2 = DB::table('epcbranchmaintenance')
                    ->select('chainCode');
                    // ->where('company', $company)
                    // ->distinct()
                    // ->get();
        // }

        $data = $data1->union($data2)->distinct()->get();
        return response()->json($data);
    }

    public function fetchEditDraftBranch(Request $request){
        $company = $request->company;
        if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data = DB::table('pullOutBranchTblNBFI')
                    ->select('id', 'chainCode', 'branchName', 'company', 'transactionType',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'status')
                    ->where('id', $request->plID)
                    ->get();

        } else if($company == 'EPC' || $company == 'AHLC'){
            $data = DB::table('pullOutBranchTbl as a')
                    ->select('id', 'chainCode', 'branchName', 'company', 'transactionType',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'status')
                    ->where('id', $request->plID)
                    ->get();

        }
        return response()->json($data);

    }
    public function fetchEditDraftItem(Request $request){
        $company = $request->company;
        if($company == 'NBFI' || $company == 'ASC' || $company == 'CMC'){
            $data = DB::table('pullOutItemsTblNBFI as a')
                    ->join('nbfi_items as b', 'a.itemCode', '=', 'b.ItemNo')
                    ->select('a.id', 'plID', 'boxNumber', 'boxLabel', 'itemCode as code', DB::raw('CONCAT(b.ItemDescription) AS description'),
                                'b.Size as size', 'b.ChildColor as color','a.brand as categorybrand', 'quantity', 'amount', 'status',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'b.Size')
                    ->where('plID', $request->plID)
                    ->get();
            return response()->json($data);

        } else if($company == 'EPC' || $company == 'AHLC'){
            $data = DB::table('pullOutItemsTbl as a')
                    ->join('epc_items as b', 'a.itemCode', '=', 'b.ItemNo')
                    ->select('a.id', 'plID', 'boxNumber', 'boxLabel', 'a.itemCode as code', DB::raw('CONCAT(b.ItemDescription, "-", b.Size) AS description'), 'b.Category as categorybrand', 'quantity', 'amount', 'status',
                        DB::raw('CONCAT(MONTHNAME(dateTime), " ", DATE_FORMAT(dateTime, "%d, %Y")) as date'),
                        DB::raw('DATE_FORMAT(dateTime, "%h:%i %p") as time'), 'b.Size')
                    ->where('plID', $request->plID)
                    ->get();
            return response()->json($data);

        }

    }

    public function fetchNewAmount(Request $request){

        if($request->company == "NBFI"){
            $amount = DB::table('nbfi_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', $request->itemCode)
                        ->first();

            $totalAmount = floatval($amount->EffectivePrice) * floatval($request->quantity);
        }else{
            $amount = DB::table('epc_items')
                        ->select('EffectivePrice')
                        ->where('ItemNo', $request->itemCode)
                        ->first();
            $totalAmount = floatval($amount->EffectivePrice) * floatval($request->quantity);

        }

        return response()->json(number_format($totalAmount, 2, '.', ','));
    }
    public function usersMaintenance(Request $request){
        $company = 0;
        $data = (object)[];

        if($request->company == "NBFI")
            $company = 1;
        else if($request->company == "ASC")
            $company = 2;
        else if($request->company == "CMC")
            $company = 3;
        else if($request->company == "EPC")
            $company = 4;
        else if($request->company == "AHLC")
            $company = 5;

        if($company == 1 || $company == 2 || $company == 3)
            $data = DB::table('users as a')
                    ->leftJoin('userBranchMaintenance as b', 'a.id', '=', 'b.userID')
                    ->select('a.id', 'a.name', 'email', 'b.status', 'a.company',
                            DB::raw('CONCAT(MONTHNAME(a.created_at), " ", DATE_FORMAT(a.created_at, "%d, %Y")) as date'))
                    ->where('position', 'User')
                    ->where(function ($query) {
                        $query->where('a.company', null)
                              ->orWhere('a.company', 1)
                              ->orWhere('a.company', 2)
                              ->orWhere('a.company', 3);
                    })
                    ->distinct()
                    ->get();

        else if ($company == 4 || $company == 5)
            $data = DB::table('users as a')
                    ->leftJoin('userBranchMaintenance as b', 'a.id', '=', 'b.userID')
                    ->select('a.id', 'a.name', 'email', 'b.status', 'a.company',
                            DB::raw('CONCAT(MONTHNAME(a.created_at), " ", DATE_FORMAT(a.created_at, "%d, %Y")) as date'))
                    ->where('position', 'User')
                    ->where(function ($query) {
                        $query->where('a.company', null)
                              ->orWhere('a.company', 4)
                              ->orWhere('a.company', 5);
                    })
                    ->distinct()
                    ->get();

        $userWithRequest = DB::table('userBranchMaintenance')
                    ->where(function ($query) {
                        $query->where('request', 'remove')
                            ->orWhere('request', 'additional');
                    })
                    ->select('userID')
                    ->distinct()
                    ->pluck('userID');

        foreach($data as $item)
            $item->request = 0;

        foreach ($userWithRequest as $id)
            foreach($data as $item)
                if($id == $item->id AND $item->request == 0){
                    $item->request = 1;
                    break;
                }

        // print_r($data);
        // $data = DB::table('users as a')
        //             ->leftJoin('userBranchMaintenance as b', 'a.id', '=', 'b.userID')
        //             ->select('a.id', 'a.name', 'email', 'b.company', 'chainCode', 'branchName', 'b.status',
        //                     DB::raw('CONCAT(MONTHNAME(a.created_at), " ", DATE_FORMAT(a.created_at, "%d, %Y")) as date'),
        //                     DB::raw('CONCAT(MONTHNAME(b.date_end), " ", DATE_FORMAT(b.date_end, "%d, %Y")) as dateEnd'), 'b.request')
        //             ->where('position', 'User')
        //             ->get();

        // foreach ($data as $item){
        //     if($item->chainCode == null){
        //         $item->chainCode = 'N/A';
        //         $item->branchName = 'N/A';
        //         $item->company = 'N/A';
        //         $item->status = 'New Account';
        //     }
        // }
        //Object to Array
        $array = json_decode(json_encode($data), true);
        //Sorting
        function sortArrayByKey(&$array,$key,$string = false,$asc = true){
            if($string){
                usort($array,function ($a, $b) use(&$key,&$asc)
                {
                    if($asc)    return strcmp(strtolower($a[$key]), strtolower($b[$key]));
                    else        return strcmp(strtolower($b[$key]), strtolower($a[$key]));
                });
            }else{
                usort($array,function ($a, $b) use(&$key,&$asc)
                {
                    if($a[$key] == $b[$key]){return 0;}
                    if($asc) return ($a[$key] < $b[$key]) ? -1 : 1;
                    else     return ($a[$key] > $b[$key]) ? -1 : 1;

                });
            }
        }
        sortArrayByKey($array,"name",true);//String sort (Asc) || add ,false to DESC
        if(count($userWithRequest) > 0)
        sortArrayByKey($array,"request",false,false);//Number Sort (Desc) || remove ,false,false to ASC

        $promoObj = json_decode(json_encode($array));
        foreach($promoObj as $promo){
            $dataPromo = DB::table('userBranchMaintenance')
                    ->select('company', 'chainCode', 'branchName', 'permanent',
                    DB::raw('CONCAT(MONTHNAME(date_end), " ", DATE_FORMAT(date_end, "%d, %Y")) as dateEnd'))
                    ->where('userID', $promo->id)
                    ->where(function ($query) {
                        $query->where('request', null)
                            ->orWhere('request', false);
                    })
                    ->get();

            $data = json_decode(json_encode($dataPromo), true);
            $promo->details = $data;
        }
        // print_r($promoObj);
        return response()->json($promoObj);
    }
    public function usersMaintenanceViewDetails(Request $request){

        $data = DB::table('userBranchMaintenance')
                    ->select('company', 'chainCode', 'branchName', 'permanent', 'request',
                    DB::raw('CONCAT(MONTHNAME(date_start), " ", DATE_FORMAT(date_start, "%d, %Y")) as dateStart'),
                    DB::raw('CONCAT(MONTHNAME(date_end), " ", DATE_FORMAT(date_end, "%d, %Y")) as dateEnd'))
                    ->where('userID', $request->userID)
                    ->where(function ($query) {
                        $query->where('request', null)
                              ->orWhere('request', 'remove');
                    })
                    // ->where('request', null)
                    // ->whereIn('request', [null, false])
                    ->get();

        return response()->json($data);
    }
    public function usersMaintenanceRequestBranch(Request $request){

        $data = DB::table('userBranchMaintenance')
                    ->select('id', 'company', 'chainCode', 'branchName', 'request',
                    DB::raw('CONCAT(MONTHNAME(created_date), " ", DATE_FORMAT(created_date, "%d, %Y")) as dateCreated'))
                    ->where('userID', $request->userID)
                    ->where(function ($query) {
                        $query->where('request', 'remove')
                              ->orWhere('request', 'additional');
                    })
                    ->get();

        return response()->json($data);
    }
    public function fetchPromoBranchInfo(Request $request){

        $data = DB::table('userBranchMaintenance')
                    ->select('company', 'chainCode', 'branchName')
                    ->where('userID', $request->userID)
                    ->get();

        return response()->json($data);
    }
    public function fetchItemsNBFIExcel(Request $request){
        $data = DB::table('nbfi_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', $request->ItemNo)
                ->get();

        return response()->json($data);
    }

    public function fetchItemsEPCExcel(Request $request){
        $data = DB::table('epc_items')
                ->select('ItemNo', 'ItemDescription')
                ->where('ItemNo', $request->ItemNo)
                ->get();

        return response()->json($data);
    }

    public function fetchSameItems(Request $request){

        $company = $request->company;

        if($company == "NBFI" || $company == "ASC" || $company == "CMC"){

            $data = DB::table('nbfi_items as a')
                    ->join('nbfibrandsmaintenance as b', 'a.Brand', '=', 'b.id')
                    ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'brandNames', 'Category')
                    ->where("ItemNo", $request->ItemNo)
                    ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'brandNames', 'Category')
                    ->get();

        }else if($company == "EPC" || $company == "AHLC"){

            $data = DB::table('epc_items as')
                    ->join('nbfibrandsmaintenance as b', 'a.Brand', '=', 'b.id')
                    ->select('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor as Color', 'brandNames', 'Category')
                    ->where("ItemNo", $request->ItemNo)
                    ->groupBy('ItemNo', 'ItemDescription', 'Size', 'StyleCode', 'ChildColor', 'brandNames', 'Category')
                    ->get();
        }

        if($data->isEmpty())
        return response()->json([]);
        else
        return response()->json($data);
    }
    public function usersProfile(Request $request){

        $user = DB::table('users as a')
                    ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('a.name', 'a.email', DB::raw('CONCAT(b.name, " (", b.shortName ,")") as company'), 'a.position',
                    DB::raw('CONCAT(MONTHNAME(a.created_at), " ", DATE_FORMAT(a.created_at, "%d, %Y")) as date'), 'a.status')
                    ->where('a.id', $request->userID)
                    ->get();
        if($user[0]->position == "User"){
            $user = DB::table('users as a')
                    ->join('userBranchMaintenance as b', 'a.id', '=', 'b.userID')
                    ->join('companyTbl as c', 'b.company', '=', 'c.shortName')
                    ->select('a.name', 'a.email', DB::raw('CONCAT(c.name, " (", c.shortName ,")") as company'), 'a.position', 'b.id', 'b.chainCode',
                            'b.branchName', DB::raw('CONCAT(MONTHNAME(a.created_at), " ", DATE_FORMAT(a.created_at, "%d, %Y")) as date'), 'a.status',
                            'b.request', 'b.permanent')
                    ->where('a.id', $request->userID)
                    // ->where(function ($query) {
                    //     $query->where('b.request', null)
                    //           ->orWhere('b.request', false);
                    // })
                    ->get();
        }

        $array = json_decode(json_encode($user), true);
        $temp = false;

        foreach($array as $key => $arr) {
            if($arr['request'] == 'additional'){
                unset($array[$key]);
                $temp = true;
            }
        }
        foreach($array as $key => $arr)
            $array[$key]['withRequest'] = $temp;

        $array = array_values($array);

        return response()->json($user);
    }

    public function fetchImageBranchDoc(Request $request){

        // $data = DB::table('imageBranchDocTbl')
        //             ->select('path')
        //             ->where('transactionID', $request->transactionID)
        //             ->where('company', $request->company)
        //             ->get();

        if($request->company == "NBFI"){
            $imagePaths = DB::table('imageBranchDocTbl')
                        ->select('path')
                        ->where('transactionID', $request->transactionID)
                        ->where('company', $request->company)
                        ->get()
                        ->pluck('path')
                        ->map(function ($path) {
                            return asset('uploads/NBFI/' . $path);
                        })
                        ->toArray();
        }else{
            $imagePaths = DB::table('imageBranchDocTbl')
                        ->select('path')
                        ->where('transactionID', $request->transactionID)
                        ->where('company', $request->company)
                        ->get()
                        ->pluck('path')
                        ->map(function ($path) {
                            return asset('uploads/EPC/' . $path);
                        })
                        ->toArray();
        }

        return response()->json(['imagePaths' => $imagePaths]);

        // return response()->json($data);
        // return Storage::url('app/images/' . $request->company . '/' . $data);
    }

    public function fetchPullOutLetterDates(Request $request){

        $data = DB::table("pullOutLetterDates")
                ->select("id", "plID", "company", "authorizedPersonnel", "dateStart", "dateEnd")
                ->where("plID", $request->plID)
                ->where("company", $request->company)
                ->get();

        return response()->json($data);
    }
}
