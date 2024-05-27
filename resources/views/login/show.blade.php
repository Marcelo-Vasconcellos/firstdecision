@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Login</h1>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Senha:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group">
        <a id="login-link" href="/create">Seu primeiro acesso? Click aqui!</a>
    </div>

    <button id="login-button" class="btn btn-primary">Fazer Login</button>
    <div id="mensagem-erro" style="color: red; display: none;"></div>

</div>
<br>
<div class="border border-primary">
        <p>
            Para a análise do teste, explico a divisão do BACK-END e FRONT-END e a forma de uso das API para o controle do fluxo de dados. Para atender o conceito de segregação funcional do front-end esse foi desenvolvido em BLADE com suporte AJAX para acesso das APIs não sendo utilizado os conceitos integrados de ROTAS do Laravel. <br> <br>

            Definições para fazer as chamadas APIs back-end Laravel. Todos os conceitos de validação foram implementados, incluso se o servidor de dados não estiver disponível no ato da execução da API. <br>
            "/api/login" método GET efetua a validação para login. <br>
            "/api/login" método POST efetua o cadastro de um usuário. <br>
            "/api/login" método PUT efetua atualização do cadastro de um usuário. <br>
            "/api/login" método DELETE efetua a deleção de um usuário. <br>
            "/api/login/list" método GET lista todos os usuários castrados. <br>
            ** para o teste de API via Postman, a passagem de parâmetros dever ser com body/raw e definir header /Accept application/json. <br>
            *** ao acionar uma API sem parâmetro será apresesentado o nome dos atributos para a passagem das informações requeridas. <br>
            **** o método PUT é o único que os atributos podem ser omitidos excepto a chave de acesso email, os valores esperados para atualizar são name e password os quais serão validados mas não tem obricação de serem passados como parâmetros.<br> <br>
            Rotas para as chamadas front-end: <br>
            "/" aciona a view "show" que efetua o login inicial <br>
            "/create" aciona a view "create" para cadastro de um novo usuário <br>
            "/index" aciona a view "index" para o tratamento CRUD para a gestão do cadastro de usuários <br> <br>
            Testes automatizados: <br>
            Executar: php artisan test tests/Feature/LoginControllerStoreTest.php <br>


        </p>
</div>
@endsection
