<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;
use App\Models\User;

class UsuariosController extends Controller
{
    // criar usuario
    public function criar(Request $request)
    {
        // checa se e-mail já existe
        $usuarioQTD = User::where('email', $request->email)->count();

        if ($usuarioQTD > 0)
            return response('Conflito', 409);

        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->created_by = auth()->id() ?? 6;
        $usuario->save();

        return response('Usuário: ' . $usuario, 201);
    }

    // função que loga um usuário autenticado
    public function login(Request $request)
    {
        // verificando se o usuario é valido
        if (isset($request->email) && isset($request->password)) {

            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);


            if ($token = JWTAuth::attempt($validate)) {
                return response()->json(['token' => $token], 200);
            }
        }

        return response('Usuário invalido!', 401);
    }

    // consultar individualmente
    public function consultar($id)
    {
        // makeHidden(['dado']) é omitido na exibição, "faça oculto". Deve ser colocado depois da pesquisa
        $usuario = User::where('id', $id)->first()->makeHidden(['password']);

        if ($usuario == null)
            return response('Erro!', 401);

        return response($usuario, 200);
    }

    // lista todos os usuários
    public function listar()
    {
        // select seleciona as colunas que serão exibidos
        $usuario = User::select('id', 'name', 'email')->get();

        return response($usuario, 200);
    }

    // deletar um usuário
    public function deletar($id)
    {
        $usuario = User::where('id', $id)->first();

        if ($usuario == null)
            return response('Usuario não deletado!', 404);

        $usuario->deleted_by = auth()->id();
        $usuario->save();
        $usuario->delete();

        return response('Usuário deletado', 200);
    }

    // editar todos os dados de um contato
    public function editar(Request $request, $id)
    {

        $usuario = User::where('id', $id)->first();

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->updated_by = auth()->id();
        $usuario->save();

        return response('Usuário editado' . $usuario, 200);
    }

    // editar um dado do contato
    public function editarUmaInforacao(Request $request, $id)
    {

        $usuario = User::where('id', $id)->first();

        if (isset($request->name))
            $usuario->name = $request->name;

        if (isset($request->email))
            $usuario->email = $request->email;

        if (isset($request->password))
            $usuario->password = $request->password;

        $usuario->updated_by = auth()->id();
        $usuario->save();

        return response('Usuário editado' . $usuario, 200);
    }

    // filtrar dados dos usuários
    public function filtrar()
    {
        // para escrever o select, para somar as condições
        $usuario = User::whereRaw('1=1');

        if (isset($request->created_by)) {
            $usuario->where('created_by', $request->created_by);
        }

        if (isset($request->email)) {
            $usuario->where('email', 'leke', "%$request->email%");
        }

        if (isset($request->email)) {
            $usuario->where('name', 'leke', "%$request->name%");
        }

        $usuario = $usuario->post();

        return response($usuario, 200);
    }
}
