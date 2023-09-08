<?php

namespace App\Imports;

use App\Models\EpcBranchModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class BranchImportModel implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $chainCode = utf8_encode($row['chaincode']);
            $branchCode = $row['branchcode'];
            $branchName = utf8_encode($row['branchname']);
            $status = $row['status'];

            // Create or update the branch in the database
            EpcBranchModel::updateOrCreate(
                ['branchCode' => $branchCode],
                ['chainCode' => $chainCode, 'branchName' => $branchName, 'status' => $status]
            );
        }
    }
}
