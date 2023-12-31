<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarksModel extends Model
{
    use HasFactory;
    protected $table = "remarksListMaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'remarksName',
        'date',
    ];
}
