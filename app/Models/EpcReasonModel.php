<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpcReasonModel extends Model
{
    use HasFactory;
    protected $table = "reasonMaintenance";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'company',
        'reasonLabel',
        'dateTime',
        'status',
        'updated_at'
    ];
}
