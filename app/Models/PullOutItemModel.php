<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutItemModel extends Model
{
    use HasFactory;
    protected $table = "pullOutItemsTbl";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'plID',
        'boxNumber',
        'boxLabel',
        'itemCode',
        'brand',
        'quantity',
        'amount',
        'status',
        'dateTime',
        'updated_at',
        'editedBy'
    ];
}
