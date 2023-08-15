<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NbfiBranchModel extends Model
{
    use HasFactory;
    protected $table = "nbfibranchmaintenance";
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
