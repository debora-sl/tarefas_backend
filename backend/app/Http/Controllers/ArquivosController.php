<?php

namespace App\Http\Controllers;

use App\Models\Arquivos;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

            $salvar = $arquivo->storeAs('uploads', $nomeDoArquivoCriptografado, 'local');
            $caminho = 'uploads/';

            $arquivoBd = new Arquivos();
            $arquivoBd->nome_original =  $nomeDoArquivoOriginal;
            $arquivoBd->nome_criptografado =  $nomeDoArquivoCriptografado;
            $arquivoBd->caminho =  $caminho;
            $arquivoBd->created_by = auth()->id();
            $arquivoBd->save();

            return response('Arquivo salvo: ' . $arquivoBd, 201);
        } else {
            return response()->json(['erro' => 'Nenhum arquivo foi enviado'], 422); // ajustado
        }
    }

    // função para download dos arquivos
    public function download($id)
    {
        // withTrashed() arquivo = Arquivos::where('id', $id)->withTrashed()->first(); -  com o lixo
        $arquivo = Arquivos::where('id', $id)->first();
        if ($arquivo == null) {
            return response('Arquivo não existe no BD', 404);
        };

        $caminho = $arquivo->caminho . $arquivo->nome_criptografado;

        if (!Storage::fileExists($caminho)) {
            return response('Arquivo não existe', 404);
        };

        $caminho = storage_path('app/') . $arquivo->caminho . $arquivo->nome_criptografado;
        $nomeOriginal = $arquivo->nome_original;

        return response()->download($caminho, $nomeOriginal);
    }

    // função para deletar um arquivo
    public function deletar($id)
    {
        $arquivo = Arquivos::where('id', $id)->first();
        $arquivo->deleted_by = auth()->id();
        $arquivo->save();

        if ($arquivo == null) {
            return response('Arquivo não existe no BD', 404);
        }

        $caminho = $arquivo->caminho . $arquivo->nome_criptografado;

        if (!Storage::fileExists($caminho)) {
            return response('Arquivo não existe', 404);
        };

        Storage::delete($caminho);
        $arquivo = Arquivos::where('id', $id)->delete();

        response('Arquivo excluído: ' . $arquivo, 200);
    }
}
