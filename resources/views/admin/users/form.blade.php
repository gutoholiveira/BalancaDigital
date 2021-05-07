@if ($formMode === 'edit')
{!! Form::open(['route' => ["usuario.update", ['id'=>$user->id]], 'method'=>'put', 'class'=>'formulario']) !!}
@else
{!! Form::open(['route' => 'usuario.store', 'class'=>'formulario']) !!}
@endif
@csrf

<div class="row text-left">
    <div class="mb-2 col-md-12">
        <label style="font-size:10.5pt; color:#151515;">Nome</label>
        <x-jet-input class="block mt-1 w-full @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', isset($user)?$user->name:'') }}" required />
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="mb-2 col-md-12">
        <label style="font-size:10.5pt; color:#151515;">Email</label>
        <x-jet-input class="block mt-1 w-full @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', isset($user)?$user->email:'') }}" required />
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="mb-2 col-md-12">
        <x-jet-label value="{{ __('Senha') }}" />
        <x-jet-input class="block mt-1 w-full @error('password') is-invalid @enderror" type="text" name="password" style="-webkit-text-security:disc;" autocomplete="off" />
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="text center mt-2" style="text-align: center; display:flex; flex-direction: row-reverse; justify-content: center;">
    @if($formMode === 'edit')
    <button type="submit" class="btn btn-success">Atualizar</button>
    @else
    <button type="submit" class="btn btn-success">Salvar</button>
    @endif
    {!! Form::close() !!}
</div>
