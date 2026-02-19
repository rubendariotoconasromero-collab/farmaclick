<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoForm extends Model
{
    protected $table = 'permiso_forms';
    protected $fillable = [
        'id',
        'id_grupo'
    ];    
    public $timestamps = false;
}
