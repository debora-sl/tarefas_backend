<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProjeto extends Model
{
    use HasFactory;
    // pedindo para não salvar os registros de tempo (ex: updated_at) e nada incrementável
    public $timestamps = false;
    public $incrementing = false;

    // informando as chaves primárias
    protected $primaryKey = ['id_user', 'id_projeto'];

    protected $table = 'user_projeto';
}
