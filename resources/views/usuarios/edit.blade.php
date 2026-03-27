@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Editar Usuário: {{ $usuario->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Função no Sistema</label>
                                <select name="funcao" class="form-control" required>
                                    @foreach($funcoes as $funcao)
                                        <option value="{{ $funcao }}" {{ (old('funcao', $usuario->funcao) == $funcao) ? 'selected' : '' }}>
                                            {{ $funcao }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                                <input type="password" name="password" class="form-control">
                                <input type="password" name="password_confirmation" class="form-control mt-2" placeholder="Confirme a nova senha">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary text-white">Atualizar Usuário</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection