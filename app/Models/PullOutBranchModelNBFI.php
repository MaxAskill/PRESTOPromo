<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutBranchModelNBFI extends Model
{
    use HasFactory;
    protected $table = "pullOutBranchTblNBFI";
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
        'approvedBy',
        'approvedDate',
        'promoEmail',
        'SLA_count',
        'SLA_status'
    ];
}
