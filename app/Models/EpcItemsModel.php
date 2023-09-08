<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpcItemsModel extends Model
{
    use HasFactory;
    protected $table = "epc_items";
    protected $primaryKey = "ItemNo";
    public $timestamps = false;
    protected $fillable = [
        'ItemNo',
        'ItemDescription',
        'InnerCartonQuantity',
        'MasterCartonQuantity',
        'PurchasingUoM',
        'SalesUoM',
        'StockUoM',
        'Brand',
        'Department',
        'Sub-Department',
        'Category',
        'SubCategory',
        'SizeCategory',
        'Size',
        'SizeCode',
        'ParentSize',
        'ParentColor',
        'ChildColor',
        'StyleCode',
        'Class',
        'SubClass',
        'Packaging',
        'Specification',
        'Collection',
        'Section',
        'SortCode',
        'EffectivePrice',
        'SRP'
    ];
}
