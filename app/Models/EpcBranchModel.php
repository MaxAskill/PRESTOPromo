<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpcBranchModel extends Model
{
    use HasFactory;
    protected $table = "epcbranchmaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'chainCode',
        'branchCode',
        'branchName',
        'status'
    ];
}
