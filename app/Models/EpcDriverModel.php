<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpcDriverModel extends Model
{
    use HasFactory;
    protected $table = "driverMaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'position',
        'status',
        'dateTime',
        'updated_at'
    ];
}
