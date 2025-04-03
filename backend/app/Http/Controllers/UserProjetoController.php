<?php

namespace App\Http\Controllers;

use App\Models\UserProjeto;
use Illuminate\Http\Request;

class UserProjetoController extends Controller
{
    // função para cadastrar usuarioProjeto
    public function cadastrar(Request $request)
    {
        $userProjeto = new UserProjeto();
        $userProjeto->id_projeto = $request->id_projeto;
        $userProjeto->id_user = auth()->id();
        $userProjeto->save();

        return response('UserProjeto criado: ', 201);
    }

    // função para deletar usuarioProjeto
    public function deletar($id)
    {
        $userProjeto = UserProjeto::where('id_projeto', $id)
            ->where('id_user', auth()->id())
            ->delete();

        return response('UserProjeto deletado: ', 200);
    }
}
