<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutModel extends Model
{
    use HasFactory;
    protected $table = "pullOutTbl";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'plID',
        'chainCode',
        'company',
        'branchName',
        'brand',
        'transactionType',
        'boxNumber',
        'boxLabel',
        'itemCode',
        'quantity',
        'amount',
        'status',
        'dateTime',
        'updated_at',
        'editedBy'

    ];
}
