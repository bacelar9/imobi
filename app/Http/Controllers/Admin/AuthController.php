<?php

namespace LaraDev\Http\Controllers\Admin;

use LaraDev\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaraDev\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        /** Se já estiver logado mantémm a url admin/home */
        if(Auth::check() === true){ // Retorna uma seção ativa (true)
            return redirect()->route('admin.home');
        }
        return view('admin.index');
    }

    public function home()
    {
        return view('admin.dashboard');
    }

    public function login(Request $request)
    {

        /** Validar preenchimento campos do form */
        if (in_array('', $request->only('email', 'password'))) {
            $json['message'] = $this->message->error('Oops, informe todos os dados para efetuar o login')->render();
            return response()->json($json);
        }
        /** Validar email */
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL))
        {
            $json['message'] = $this->message->error('Oops, informe um email válido')->render();
            return response()->json($json);
        }
        /** Validar senha */
        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];

       if (!Auth::attempt($credentials)) {
            $json['message'] = $this->message->error('Oops, usuário e senha não confere')->render();
            return response()->json($json);
       }

       /** envia o IP para o método private */
       $this->authenticated($request->getClientIp());

       $json['redirect'] = route('admin.home');
       return response()->json($json);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    /**
     * Obtem o id do usuário logado
     * e atualiza os campos
     * */
    private function authenticated(string $ip)
    {
       $user = User::where('id', Auth::user()->id);
       $user->update([
           'last_login_at' => date('Y-m-d H:i:s'),
           'last_login_ip' => $ip
       ]);

    }
}
