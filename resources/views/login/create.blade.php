@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cadastro</h1>

    <div class="form-group">
        <label for="name">Nome:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="password">Senha:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label for="password_confirm">Confirme a senha:</label>
        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
    </div>

    <button id="create-button" class="btn btn-primary">Confirmar</button>
    <div id="mensagem-erro" style="color: red; display: none;"></div>

</div>
@endsection
