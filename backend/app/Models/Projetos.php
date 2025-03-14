<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projetos extends Model
{
    use HasFactory;
    protected $table = 'projetos';
    use SoftDeletes;
}
