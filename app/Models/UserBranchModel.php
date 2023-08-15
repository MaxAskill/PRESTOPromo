<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBranchModel extends Model
{
    use HasFactory;
    protected $table = "userBranchMaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'userID',
        'company',
        'chainCode',
        'branchName',
        'created_date',
        'date_end',
        'request',
        'status',
        'permanent'
    ];
}
