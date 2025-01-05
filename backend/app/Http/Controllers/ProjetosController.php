<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Projetos;
use App\Models\Tarefas;

class ProjetosController extends Controller
{
    // criar projeto
    public function criar(Request $request)
    {
        $projeto = new Projetos();
        $projeto->nome = $request->nome;
        $projeto->descricao = $request->descricao;
        $projeto->dataDeInicio = $request->dataDeInicio;
        $projeto->dataDeConclusao = $request->dataDeConclusaoe;
        $projeto->pontos = $request->pontos;
        $projeto->created_by = auth()->id();

        // setando para que não seja null para não dar erro ao cadastrar
        if (isset($request->prioridade))
            $projeto->prioridade = $request->prioridade;
        if (isset($request->prioridade))
            $projeto->status = $request->status;

        $projeto->save();

        return response('Projeto criado ' . $projeto, 201);
    }


    // consultar individualmente
    public function consultar($id)
    {
        // where('created_by', auth()->id() - consultando apenas projetos que este usuário logado e autenticado criou. Depos será possível consultar também projetos em que o usuário é integrante
        $projeto = Projetos::select('id', 'nome', 'prioridade', 'status')->where('id', $id)->where('created_by', auth()->id())->first();


        if ($projeto == null)
            return response('Erro, projeto não localizado', 404);

        // trazendo as tarefas
        $projeto->tarefas = Tarefas::select('id', 'nome', 'prioridade', 'status')->where('id_projeto', $projeto->id)->get();

        return response('Projeto: ' . $projeto, 200);
    }

    // lista todos os projetos
    public function listar()
    {
        // where('created_by', auth()->id() - listando apenas todos projetos que este usuário logado e autenticado criou.
        $projeto = Projetos::where('created_by', auth()->id())->get();

        return response('Projetos: ' . $projeto, 200);
    }

    // deletar um usuário
    public function deletar($id)
    {
        $projeto = Projetos::where('id', $id)->first();

        if ($projeto == null)
            return response('projeto não deletado', 404);

        $projeto->deleted_by = auth()->id();
        $projeto->save();
        $projeto->delete();

        return response('Projeto deletado!', 200);
    }

    // editar um dado do contato
    public function editarUmaInforacao(Request $request, $id)
    {
        $projeto = Projetos::where('id', $id)->where('created_by', auth()->id())->first();

        if (isset($request->nome))
            $projeto->nome = $request->nome;
        if (isset($request->descricao))
            $projeto->descricao = $request->descricao;
        if (isset($request->dataDeInicio))
            $projeto->dataDeInicio = $request->dataDeInicio;
        if (isset($request->nome))
            $projeto->dataDeConclusao = $request->dataDeConclusao;
        if (isset($request->dataDeConclusao))
            $projeto->pontos = $request->pontos;
        if (isset($request->pontos))
            $projeto->nome = $request->nome;
        if (isset($request->status))
            $projeto->status = $request->status;
        if (isset($request->prioridade))
            $projeto->prioridade = $request->prioridade;

        $projeto->updated_by = auth()->id();
        $projeto->save();

        return response('Projeto editado ' . $projeto, 200);
    }

    // filtrar dados dos usuários
    public function filtrar(Request $request)
    {
        // $requestVazio = true; se caso não for informado nada, retorne erro
        $requestVazio = true;

        $projeto = Projetos::where('created_by', auth()->id());

        if (isset($request->nome)) {
            $requestVazio = false;
            $projeto->where('nome', 'like', "%$request->nome%");
        }

        if (isset($request->prioridade)) {
            $requestVazio = false;
            $projeto->where('prioridade', $request->prioridade);
        }

        if (isset($request->dataDeInicio)) {
            $requestVazio = false;
            $projeto->where('dataDeInicio', '>=', $request->dataDeInicio);
        }

        if (isset($request->dataDeConclusao)) {
            $requestVazio = false;
            $projeto->where('dataDeConclusao', '<=', $request->dataDeConclusao);
        }

        if (isset($request->status)) {
            $requestVazio = false;
            $projeto->where('status', $request->status);
        }

        if ($requestVazio == true)
            return response('Erro, nenhum filtro informado', 403);

        $projeto = $projeto->get();

        return response($projeto, 200);
    }
}
