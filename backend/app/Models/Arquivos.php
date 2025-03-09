<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivos extends Model
{
    use HasFactory;
    protected $table = 'arquivos';
    use SoftDeletes;
}
