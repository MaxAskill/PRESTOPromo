<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutBranchModel extends Model
{
    use HasFactory;
    protected $table = "pullOutBranchTbl";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'chainCode',
        'company',
        'branchName',
        'transactionType',
        'status',
        'dateTime',
        'updated_at',
        'editedBy',
        'promoEmail',
        'SLA_count',
        'SLA_status'
    ];
}
