<?php

namespace App\Http\Controllers;

use App\Models\Arquivos;

use Illuminate\Http\Request;

class ArquivosController extends Controller
{
    // função salvar arquivos
    public function salvarArquivo(Request $request)
    {
        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            $nomeDoArquivo = $arquivo->getClientOriginalName();

            $salvar = $arquivo->storeAs('uploads', $nomeDoArquivo, 'local');
        }
    }
}
