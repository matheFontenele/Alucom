@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Usuário: {{ $usuario->name }}</h2>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Função no Sistema</label>
            <select name="funcao" class="form-control" required>
                @foreach($funcoes as $funcao)
                    <option value="{{ $funcao }}" {{ $usuario->funcao == $funcao ? 'selected' : '' }}>
                        {{ $funcao }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection