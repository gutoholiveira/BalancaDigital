<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items: center; justify-content: center;">
            <h2 class=" col font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Produtos') }}
            </h2>
            <div class="col">
                {!! Form::open(['method' => 'GET', 'route' => 'produtos', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search']) !!}
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Pesquisar"
                        value="{{ isset($keyword) ? $keyword : '' }}">
                    <span class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col text-right">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Adicionar
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Produto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @include ('produtos.form', ['formMode' => 'create'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar" style="height:auto;">
                    <table class="table table-hover table-borderless table-sm" style="font-size: 10pt;">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Peso</th>
                                <th>Valor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produtos as $produto)
                                <tr style="transform: rotate(0);">
                                    <td>{{ $produto->codigo }}</td>
                                    <td>{{ $produto->descricao }}</td>
                                    <td>{{ $produto->peso }} gramas</td>
                                    <td>R$ {{ $produto->valor }}</td>
                                    <td class="text-right">
                                        <!-- Button trigger modal -->
                                        <a data-bs-toggle="modal" data-bs-target="#edit{{ $produto->id }}">
                                            <i class="fas fa-pen m-1" style="color:blue; cursor: pointer;"></i>
                                        </a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['produto.destroy', ['id' => $produto->id]], 'style' => 'display:inline']) !!}
                                        <button type="submit" class="mr-4" title="Deletar Produto"
                                            onclick="return confirm('Confirmar exclusão?')">
                                            <i class="fas fa-trash m-1" style="color:red;"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ $produto->id }}" tabindex="-1"
                                    aria-labelledby="prod" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="prod">Edição de Produto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @include ('produtos.form', ['formMode' => 'edit'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination mb-4" style="justify-content: center;"> {!! $produtos->appends(['search' => Request::get('search')])->render() !!} </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
