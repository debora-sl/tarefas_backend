<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tarefas;

class TarefasController extends Controller
{
    // criar tarefa
    public function criar(Request $request)
    {
        $tarefa = new Tarefas();
        $tarefa->id_projeto = $request->id_projeto;
        $tarefa->nome = $request->nome;
        $tarefa->created_by = auth()->id();

        // setando para que não seja null para não dar erro ao cadastrar
        if (isset($request->prioridade))
            $tarefa->prioridade = $request->prioridade;
        if (isset($request->prioridade))
            $tarefa->status = $request->status;

        $tarefa->save();

        return response('tarefa criada ' . $tarefa, 201);
    }


    // consultar individualmente
    public function consultar($id)
    {
        // where('created_by', auth()->id() - consultando apenas projetos que este usuário logado e autenticado criou. Depos será possível consultar também projetos em que o usuário é integrante
        $tarefa = Tarefas::where('id', $id)->where('created_by', auth()->id())->first();

        if ($tarefa == null)
            return response('Erro, tarefa não localizada', 404);

        return response('tarefa: ' . $tarefa, 200);
    }

    // lista todos os projetos
    public function listar()
    {
        // where('created_by', auth()->id() - listando apenas todos projetos que este usuário logado e autenticado criou.
        $tarefa = Tarefas::where('created_by', auth()->id())->get();

        return response('Tarefas: ' . $tarefa, 200);
    }

    // deletar um usuário
    public function deletar($id)
    {
        $tarefa = Tarefas::where('id', $id)->first();

        if ($tarefa == null)
            return response('tarefa não deletada', 404);

        $tarefa->deleted_by = auth()->id();
        $tarefa->save();
        $tarefa->delete();

        return response('tarefa deletada!', 200);
    }

    // editar um dado do contato
    public function editarUmaInforacao(Request $request, $id)
    {
        $tarefa = Tarefas::where('id', $id)->where('created_by', auth()->id())->first();

        if (isset($request->nome))
            $tarefa->nome = $request->nome;
        if (isset($request->status))
            $tarefa->status = $request->status;
        if (isset($request->prioridade))
            $tarefa->prioridade = $request->prioridade;

        $tarefa->updated_by = auth()->id();
        $tarefa->save();

        return response('tarefa editada ' . $tarefa, 200);
    }

    // filtrar dados dos usuários
    public function filtrar(Request $request)
    {
        // $requestVazio = true; se caso não for informado nada, retorne erro
        $requestVazio = true;

        $tarefa = Tarefas::where('created_by', auth()->id());

        if (isset($request->id_projeto)) {
            $requestVazio = false;
            $tarefa->where('id_projeto', $request->id_projeto);
        } else {
            return response('Erro, nenhum id_projeto informado', 403);
        }

        if (isset($request->nome)) {
            $requestVazio = false;
            $tarefa->where('nome', 'like', "%$request->nome%");
        }

        if (isset($request->status)) {
            $requestVazio = false;
            $tarefa->where('status', $request->status);
        }

        if (isset($request->prioridade)) {
            $requestVazio = false;
            $tarefa->where('prioridade', $request->prioridade);
        }

        if ($requestVazio == true)
            return response('Erro, nenhum filtro informado', 403);

        $tarefa = $tarefa->get();

        return response($tarefa, 200);
    }
}
