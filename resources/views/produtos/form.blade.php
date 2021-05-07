@if ($formMode === 'edit')
{!! Form::open(['route' => ["produto.update", ['id'=>$produto->id]], 'method'=>'put', 'class'=>'formulario']) !!}
@else
{!! Form::open(['route' => 'produto.store', 'class'=>'formulario']) !!}
@endif
@csrf

<div class="row text-left">
    <div class="mb-2 col-md-12">
        <label style="font-size:10.5pt; color:#151515;">Código</label>
        <x-jet-input class="block mt-1 w-full @error('codigo') is-invalid @enderror" type="text" name="codigo" value="{{ old('codigo', isset($produto)?$produto->codigo:'') }}" required />
        @error('codigo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="mb-2 col-md-12">
        <label style="font-size:10.5pt; color:#151515;">Descrição</label>
        <x-jet-input class="block mt-1 w-full @error('descricao') is-invalid @enderror" type="text" name="descricao" value="{{ old('descricao', isset($produto)?$produto->descricao:'') }}" required />
        @error('descricao')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="mb-2 col-md-6">
        <label style="font-size:10.5pt; color:#151515;">Peso em gramas</label>
        <x-jet-input class="block mt-1 w-full @error('peso') is-invalid @enderror" type="number" name="peso" value="{{ old('peso', isset($produto)?$produto->peso:'0') }}" required />
        @error('peso')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="mb-2 col-md-6">
        <label style="font-size:10.5pt; color:#151515;">Valor R$</label>
        <x-jet-input class="block mt-1 w-full @error('valor') is-invalid @enderror" type="number" name="valor" step="0.01" min="0.00" value="{{ old('valor', isset($produto)?$produto->valor:'0') }}"/>
        @error('valor')
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

