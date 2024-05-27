<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Usuários</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-begin">
            <p>DESAFIO: DESENVOLVEDOR PHP + LARAVEL </p>
        </div>
        <div class="d-flex justify-content-begin">
            <p>  Cadastro de Usuários PHP, Laravel, PostgreSQL e AJAX para acesso das APIs desenvolvidas em Laravel</p>
        </div>

            <div class="d-flex justify-content-end">
                <a href="/" ">HOME</a>
            </div>

        @yield('content')
    </div>
    <script>
        $(document).ready(function() {
            ///// tratamento de login incial //////////////////////////////////////
            $('#login-button').click(function() {
                var email = $('#email').val();
                var password = $('#password').val();
                $.ajax({
                    url: '/api/login',
                    type: 'GET',
                    data: {
                        // Dados que você deseja enviar para a rota
                        email: email,
                        password: password,
                    },
                    success: function(response) {
                        window.location.href = '/index';
                    },
                    error: functionError

                });
            });
            /////// fim tratamento ////////////////////////////////////////////////
            ///// tratamento para create novo usuario //////////////////////////////////////
            $('#create-button').click(function() {
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password_confirm = $('#password_confirm').val();
                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        password_confirm: password_confirm
                    },
                    success: function(response) {
                        window.location.href = '/index';
                    },
                    error: functionError
                });
            });
            /////// fim tratamento /////////////////////////////////////////////////////
            /////// função comum para tratamento de erros //////////////////////////////
            function functionError(xhr, status, error) {

                if (xhr.status === 422 && xhr.responseJSON) {
                    var jsonResponse = xhr.responseJSON;
                    var mensagemErro = '' //jsonResponse.message;
                    var errorMessages = '';

                    if (jsonResponse.errors) {
                        for (var field in jsonResponse.errors) {
                            if (jsonResponse.errors.hasOwnProperty(field)) {
                                jsonResponse.errors[field].forEach(function(error) {
                                    errorMessages += '' + error + '<br>';
                                });
                            }
                        }
                    }

                    $('#mensagem-erro').html(mensagemErro + errorMessages).show();
                } else if (xhr.responseJSON) {
                    var jsonResponse = xhr.responseJSON;
                    var mensagemErro = jsonResponse.message;
                    $('#mensagem-erro').text(mensagemErro).show();
                } else {
                    var mensagemErro = 'Erro: ' + xhr.status + ' - ' + xhr.statusText;
                    $('#mensagem-erro').text(mensagemErro).show();
                }


            }

        });
    </script>
</body>
</html>
