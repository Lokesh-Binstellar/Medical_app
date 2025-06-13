<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadQR extends Model
{
   protected $table = 'upload_qr';
    protected $fillable = ['qr_image'];
}
