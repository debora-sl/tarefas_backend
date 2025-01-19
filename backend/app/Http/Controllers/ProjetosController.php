<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

use App\Models\Projetos;
use App\Models\Tarefas;

class ProjetosController extends Controller
{
    // para converter as datas
    public static function javascriptDateToPhpDate($data)
    {
        if ($data == null)
            return null;

        $date = new DateTime($data);
        $date->setTimezone(new \DateTimeZone('America/Fortaleza'));
        $date->modify('+3 hours');

        return $date;
    }

    // criar projeto
    public function criar(Request $request)
    {
        $projeto = new Projetos();
        $projeto->nome = $request->nome;
        $projeto->descricao = $request->descricao;

        if (isset($request->dataDeInicio))
            $projeto->dataDeInicio = static::javascriptDateToPhpDate($request->dataDeInicio);

        if (isset($request->dataDeConclusao))
            $projeto->dataDeConclusao = static::javascriptDateToPhpDate($request->dataDeConclusao);

        $projeto->pontos = $request->pontos;
        $projeto->created_by = auth()->id();

        // setando para que não seja null para não dar erro ao cadastrar
        if (isset($request->prioridade))
            $projeto->prioridade = $request->prioridade;
        if (isset($request->status))
            $projeto->status = $request->status;

        $projeto->save();

        return response('Projeto criado ' . $projeto, 201);
    }


    // consultar individualmente
    public function consultar($id)
    {
        // where('created_by', auth()->id() - consultando apenas projetos que este usuário logado e autenticado criou. Depos será possível consultar também projetos em que o usuário é integrante
        $projeto = Projetos::select('id', 'nome', 'descricao', 'dataDeInicio', 'dataDeConclusao', 'pontos', 'prioridade', 'status')->where('id', $id)->where('created_by', auth()->id())->first();


        if ($projeto == null)
            return response('Erro, projeto não localizado', 404);

        // trazendo as tarefas
        $projeto->tarefas = Tarefas::select('id', 'nome', 'prioridade', 'status', 'created_at')->where('id_projeto', $projeto->id)->get();

        return response()->json($projeto, 200);
    }

    // lista todos os projetos
    public function listar()
    {
        // where('created_by', auth()->id() - listando apenas todos projetos que este usuário logado e autenticado criou.
        // Obtém todos os projetos criados pelo usuário autenticado
        $projetos = Projetos::where('created_by', auth()->id())->get();

        // Retorna os projetos como JSON
        return response()->json($projetos, 200);
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
            $projeto->dataDeInicio = static::javascriptDateToPhpDate($request->dataDeInicio);

        if (isset($request->dataDeConclusao))
            $projeto->dataDeConclusao = static::javascriptDateToPhpDate($request->dataDeConclusao);

        if (isset($request->pontos))
            $projeto->pontos = $request->pontos;

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
