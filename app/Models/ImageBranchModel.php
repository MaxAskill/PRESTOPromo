<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageBranchModel extends Model
{
    use HasFactory;
    protected $table = "imageBranchDocTbl";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'transactionID',
        'company',
        'path',
    ];
}
