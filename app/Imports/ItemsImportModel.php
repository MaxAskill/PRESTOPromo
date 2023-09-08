<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\EpcItemsBranchModel;

class ItemsImportModel implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row) {
            $ItemNo = $row['itemno'];
            $ItemDescription = $row['itemdescription'];
            $InnerCartonQuantity = $row['innercartonquantity'];
            $MasterCartonQuantity = $row['mastercartonquantity'];
            $PurchasingUoM = $row['purchasinguom'];
            $SalesUoM = $row['salesuom'];
            $StockUoM = $row['stockuom'];
            $Brand = $row['brand'];
            $Department = $row['department'];
            $SubDepartment = $row['subdepartment'];
            $Category = $row['category'];
            $SubCategory = $row['subcategory'];
            $SizeCategory = $row['sizecategory'];
            $Size = $row['size'];
            $SizeCode = $row['sizecode'];
            $ParentSize = $row['parentsize'];
            $ParentColor = $row['parentcolor'];
            $ChildColor = $row['childcolor'];
            $StyleCode = $row['stylecode'];
            $Class = $row['class'];
            $SubClass = $row['subclass'];
            $Packaging = $row['packaging'];
            $Specification = $row['specification'];
            $Collection = $row['collection'];
            $Section = $row['section'];
            $SortCode = $row['sortcode'];
            $EffectivePrice = $row['effectiveprice'];
            $SRP = $row['srp'];

            // Create or update the branch in the database
            EpcItemsModel::updateOrCreate(
                ['ItemNo' => $ItemNo, 'ItemDescription' => $ItemDescription],
                ['InnerCartonQuantity' => $InnerCartonQuantity, 'MasterCartonQuantity' => $MasterCartonQuantity,
                'PurchasingUoM' => $PurchasingUoM, 'SalesUoM' => $SalesUoM,
                'StockUoM' => $StockUoM, 'Brand' => $Brand,
                'Department' => $Department, 'SubDepartment' => $SubDepartment,
                'Category' => $Category, 'SubCategory' => $SubCategory,
                'SizeCategory' => $SizeCategory, 'Size' => $Size,
                'SizeCode' => $SizeCode, 'ParentSize' => $ParentSize,
                'ParentColor' => $ParentColor, 'ChildColor' => $ChildColor,
                'StyleCode' => $StyleCode, 'Class' => $Class,
                'SubClass' => $SubClass, 'Packaging' => $Packaging,
                'Specification' => $Specification, 'Collection'=> $Collection,
                'Section' => $Section, 'SortCode' => $SortCode,
                'EffectivePrice' => $EffectivePrice, 'SRP' => $SRP],
            );
        }

    }
}
