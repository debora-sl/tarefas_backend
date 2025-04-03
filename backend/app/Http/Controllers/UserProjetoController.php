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
        $userProjeto->id_user =  $request->id_user;

        $userProjeto->save();


        return response('UserProjeto criado: ', 201);
    }

    // função para deletar usuarioProjeto
    public function deletar($projeto, $user)
    {
        $userProjeto = UserProjeto::where('id_projeto', $projeto)
            ->where('id_user', $user)
            ->delete();

        return response('UserProjeto deletado: ', 200);
    }
}
