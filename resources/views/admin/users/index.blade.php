<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items: center; justify-content: center;">
            <h2 class=" col font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Usuários') }}
            </h2>
            <div class="col">
                {!! Form::open(['method' => 'GET', 'route' => 'usuarios', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search']) !!}
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
                                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Usuário</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @include ('admin.users.form', ['formMode' => 'create'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p p-6">
                <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar" style="height:auto;">
                    <table class="table table-hover table-borderless table-sm" style="font-size: 10pt;">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr style="transform: rotate(0);">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td class="text-right">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['usuario.destroy', ['id' => $item->id]], 'style' => 'display:inline']) !!}
                                        <button type="submit" class="mr-4" title="Deletar Usuário"
                                            onclick="return confirm('Confirmar exclusão?')">
                                            <i class="fas fa-trash m-1" style="color:red;"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                <div class="modal fade editar" data-backdrop="static" id="edit{{ $item->id }}"
                                    tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body text-left">
                                                <button type="button" class="close mb-4" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <br>
                                                {!! Form::model($item, ['method' => 'PUT', 'route' => ['usuario.update', ['id' => $item->id]], 'class' => 'form-horizontal']) !!}
                                                @include ('admin.users.form', ['formMode' => 'edit', 'user'=>
                                                $item])
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination mb-4" style="justify-content: center;"> {!! $users->appends(['search' => Request::get('search')])->render() !!} </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
