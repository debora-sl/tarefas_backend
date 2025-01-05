<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefas extends Model
{
    use HasFactory;
    protected $table = 'tarefas';
    use SoftDeletes;
}
