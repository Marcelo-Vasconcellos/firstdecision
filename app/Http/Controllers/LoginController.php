<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    private $db;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $db)
    {
        $this->db = $db;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        try {
            $qtd = $this->db->count();
        } catch (\Exception $e) {
            return $this->errorDB($e);
        }

        if ($qtd > 0) {
            return response()->json([
                'message' => 'Sucesso.',
                'data' => $this->db->all()
            ], 201);
        } else {
            return response()->json([
                'message' => 'Base de dados vazia.',
                'data' => null
            ], 200);
        }
    }
    public function show(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        try {
            $db = $this->db::where('email', $request->email)->first();
        } catch (\Exception $e) {
            return $this->errorDB($e);
        }
        if (!$db || !password_verify($request->password, $db->password)) {
            return response()->json([
                'message' => 'Não autorizado.',
                'errors' => [
                    'code' => '401',
                    'message' => 'Unauthorized.',
                ]
            ], 401);
        }

        return response()->json([
            'message' => 'Autorizado.',
            'data' => [
                'id' => $db->id,
                'name' => $db->name
            ]
        ], 200);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:20',
            'password_confirm' => 'required|same:password'
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 50 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.unique' => 'O e-mail já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.max' => 'A senha deve ter no máximo 20 caracteres.',
            'password_confirm.required' => 'A confirmação de senha é obrigatória.',
            'password_confirm.same' => 'A confirmação de senha deve ser igual à senha.'
        ]);

        try {
            $db = new $this->db();
            $db->name = $request->name;
            $db->email = $request->email;
            $db->password = Hash::make($request->password);
            $db->save();
        } catch (\Exception $e) {
            return $this->errorDB($e);
        }

        return response()->json([
            'message' => 'Cadastrado com sucesso.',
            'data' => ['id' => $db->id]
        ], 201);

    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|string|min:3|max:50',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6|max:20'
        ], [
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 50 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.max' => 'A senha deve ter no máximo 20 caracteres.',

        ]);

        try {
            $db = $this->db::where('email', $request->email)->first();
        } catch (\Exception $e) {
            return $this->errorDB($e);
        }

        if (!$db) {
            return response()->json([
                'message' => 'Registro não encontrado.',
                'errors' => [
                    'code' => '404',
                    'message' => 'Not found.',
                ]
            ], 404);
        }
        if ( !($request->name) && !($request->password) ) {
            return response()->json([
                'message' => 'Nenhuma ação foi realizada.',
                'data' => null
            ], 200);

        }
        if($request->name) {
            $db->name = $request->name;
        }
        if($request->password) {
            $db->password = Hash::make($request->password);
        }
        $db->save();

        return response()->json([
            'message' => 'Atualização realizada com sucesso.',
            'data' => null
        ], 201);
    }
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',

        ]);

        try {
            $db = $this->db::where('email', $request->email)->first();
        } catch (\Exception $e) {
            return $this->errorDB($e);
        }

        if (!$db) {
            return response()->json([
                'message' => 'Registro não encontrado.',
                'errors' => [
                    'code' => '404',
                    'message' => 'Not found.',
                ]

            ], 404);
        }
        $db->delete();
        return response()->json([
            'message' => 'Deletado com sucesso.',
            'data' => null
        ], 201);
    }

    public function errorDB ($e) {
        return response()->json([
            'message' => 'Erro ao executar uma operação com o banco de dados.',
            'errors' => [
                'code' => '500',
                'message' => 'Intermal Server Error.',
                'exception' => [$e->getMessage()],
            ]
        ], 500);
    }

}
