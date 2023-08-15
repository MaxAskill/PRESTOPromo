<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NbfiBrandModel extends Model
{
    use HasFactory;
    protected $table = "nbfibrandsmaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'brandNames',
        'status'
    ];
}
