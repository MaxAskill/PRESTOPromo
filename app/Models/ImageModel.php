<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class ImageModel extends Model
{
    use HasFactory;

    public function getImageAttribute($value){
        return Storage::url("uploads/" .$value);
    }
}
