<?php

namespace App\Http\Controllers;

use App\Models\Arquivos;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArquivosController extends Controller
{
    // função salvar arquivos
    public function salvarArquivo(Request $request)
    {
        if ($request->hasFile('arquivo')) {
            $timeStampAtual = new DateTime();
            $timeStampAtual = $timeStampAtual->getTimestamp();

            $arquivo = $request->file('arquivo');
            $nomeDoArquivoOriginal = $arquivo->getClientOriginalName();
            $nomeDoArquivoCriptografado = $timeStampAtual . '_' . Str::random(32);

            $salvar = $arquivo->storeAs('uploads', $nomeDoArquivoOriginal, 'local');
            $caminho = 'uploads/';

            $arquivoBd = new Arquivos();
            $arquivoBd->nome_original =  $nomeDoArquivoOriginal;
            $arquivoBd->nome_criptografado =  $nomeDoArquivoCriptografado;
            $arquivoBd->caminho =  $caminho;
            $arquivoBd->created_by = auth()->id();
            $arquivoBd->save();

            return response('Arquivo salvo: ' . $arquivoBd, 201);
        } else {
            return response('Erro: ' . 422);
        }
    }

    // função para download dos arquivos
    public function download($id)
    {
        $arquivo = Arquivos::where('id', $id)->first();
        $caminho = storage_path('app/') . $arquivo->caminho . $arquivo->nome_criptografado;
        $nomeOriginal = $arquivo->nome_original;

        return response()->download($caminho, $nomeOriginal);
    }
}
