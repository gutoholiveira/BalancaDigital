<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\LogAdmController;
use App\Http\Controllers\LogClientController;
use App\Http\Controllers\LogErrorController;
use App\Http\Controllers\MbController;
use App\Models\Alerta;
use App\Models\Organizacao;
use App\Models\Plano;
use App\Models\Role;
use App\Models\User;
use Error;
use ErrorException;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $users = User::where('name', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->orderBy('name', 'ASC')
            ->paginate(30);

        $formMode = 'create';

        return view('admin.users.index', compact('users', 'formMode', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
                'password' => ['required', 'string', 'max:255'],
            ])->validate();

            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        } catch (QueryException $e) {
            toast('Erro ao cadastrar usuário!', 'error');
            return redirect()->back();
        }

        toast('Usuário cadastrado com sucesso!', 'success');
        return redirect()->route('usuarios');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
            ])->validate();

            if ($request['email'] !== $user->email) {
                Validator::make($request->all(), [
                    'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
                ])->validate();
            }

            $data = $request->except('password');

            if ($request['password'] !== null) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);
        } catch (QueryException $e) {
            toast('Erro ao atualizar usuário!', 'error');
            return redirect()->back();
        }

        toast('Usuário atualizado com sucesso!', 'success');
        return redirect()->route('usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        try {
            User::destroy($id);
        } catch (QueryException $e) {
            toast('Erro ao excluir usuário!', 'error');
            return redirect()->back();
        }

        toast('Usuário excluído com sucesso!', 'success');
        return redirect()->route('usuarios');
    }
}
