<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;
    protected $table = "transactionLogTbl";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'dateTime',
        'userID',
        'action_type',
        'table_affected',
        'old_data',
        'new_data'

    ];
}
