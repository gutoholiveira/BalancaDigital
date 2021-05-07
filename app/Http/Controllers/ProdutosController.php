<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $produtos = Produto::where('descricao', 'LIKE', "%$keyword%")
            ->orWhere('codigo', 'LIKE', "%$keyword%")
            ->orWhere('valor', 'LIKE', "%$keyword%")
            ->orWhere('peso', 'LIKE', "%$keyword%")
            ->orderBy('codigo', 'ASC')
            ->paginate(30);

        $formMode = 'create';

        return view('produtos.index', compact('produtos', 'formMode', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Produto::create([
                'codigo' => $request['codigo'],
                'descricao' => $request['descricao'],
                'peso' => $request['peso'],
                'valor' => $request['valor'],
            ]);
        } catch (QueryException $e) {
            toast('Erro ao cadastrar produto!', 'error');
            return redirect()->back();
        }

        toast('Produto cadastrado com sucesso!', 'success');
        return redirect()->route('produtos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        try {
            $data = $request->all();
            $produto->update($data);
        } catch (QueryException $e) {
            toast('Erro ao atualizar produto!', 'error');
            return redirect()->back();
        }

        toast('Produto atualizado com sucesso!', 'success');
        return redirect()->route('produtos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Produto::destroy($id);
        } catch (QueryException $e) {
            toast('Erro ao excluir produto!', 'error');
            return redirect()->back();
        }

        toast('Produto excluído com sucesso!', 'success');
        return redirect()->route('produtos');
    }

    public function getCodigo(Request $request)
    {
        $produtos = Produto::where('codigo', 'LIKE', "%$request->q%")
        ->orderBy('codigo', 'ASC')
        ->get();

        return response()->json($produtos);
    }

    public function getDescricao(Request $request)
    {
        $produtos = Produto::where('descricao', 'LIKE', "%$request->q%")
        ->orderBy('descricao', 'ASC')
        ->get();

        return response()->json($produtos);
    }

    public function getId(Request $request)
    {
        $produto = Produto::where('id', $request->id)
        ->first();

        return response()->json($produto);
    }
}
