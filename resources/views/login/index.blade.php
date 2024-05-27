@extends('layouts.app')

@section('content')
<div id="app">
    <h1>Manutenção do cadastro de usuários</h1>
    <button class="btn btn-primary" onclick="window.location.href='/create'">Novo Usuário</button>


    <table id="list-users" border="1" class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Editar</th>
                <th scope="col">Excluir</th>
            </tr>
        </thead>
        <tbody>
            <!-- Conteúdo da tabela será preenchido via AJAX -->
        </tbody>
    </table>
    <p id="mensagem-vazia" style="display: none;">Base de dados vazia.</p>
</div>

<!-- Modal de Confirmação Exclusão -->
<div id="modalExcluir" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Exclusão de usuário</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p id="modalExcluirTexto"></p>
        </div>
        <div class="modal-footer">
          <button type="button" id="confirmarExcluir" class="btn btn-primary">Confirmar</button>
          <button type="button" id="cancelarModal" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação Edição -->
<div id="modalEditar" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Alterar dados do usuário</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p id="modalEditarTexto"></p>
            <div class="form-group">
                <label for="editarName">Nome:</label>
                <input type="text" class="form-control" id="editarName" name="name" required placeholder="digite o novo nome ou deixe em branco para manter o original">
            </div>
            <div class="form-group">
                <label for="editarPassword">Senha:</label>
                <input type="password" class="form-control" id="editarPassword" name="password" required placeholder="deixe em branco para manter a atual ou digite uma nova">
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="confirmarEditar" class="btn btn-primary">Confirmar</button>
          <button type="button" id="cancelarModal" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

<script>
    $(document).ready(function() {
        var usuarioKeyId = null;

        async function carregarUsers() {
            try {
                const response = await $.ajax({
                    url: '/api/login/list',
                    type: 'GET'
                });

                $('#list-users tbody').empty();
                $('#mensagem-vazia').hide();

                if (response.data && response.data.length > 0) {
                    response.data.forEach(user => {
                        $('#list-users tbody').append(
                            `<tr scope="row">
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td><a href="#" class="editar-usuario text-primary" data-id="${user.id}" data-email="${user.email}" data-name="${user.name}">Editar</a></td>
                                <td><a href="#" class="excluir-usuario text-danger" data-id="${user.id}" data-email="${user.email}" data-name="${user.name}">Excluir</a></td>
                            </tr>`
                        );
                    });
                } else {
                    $('#mensagem-vazia').show();
                }
            } catch (error) {
                console.error('Erro ao carregar usuários:', error);
            }
        }

        // Carregar usuários na inicialização da página
        carregarUsers();

        // Tratamento de clique para MODAL excluir usuário
        $(document).on('click', '.excluir-usuario', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var email = $(this).data('email');
            var name = $(this).data('name');
            usuarioKeyId = email;

            $('#modalExcluirTexto').text(`Deseja realmente excluir o usuário ${email}?`);
            $('#modalExcluir').show();
        });

        // Tratamento de clique para MODAL editar usuário
        $(document).on('click', '.editar-usuario', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var email = $(this).data('email');
            var name = $(this).data('name');
            usuarioKeyId = email;
            $('#editarName').val(name);
            $('#editarPassword').val('');
            $('#modalEditarTexto').text(`Email: ${email}`);
            $('#modalEditar').show();
        });


        // Tratamento de clique para fechar o MODAL
        $('.close, #cancelarModal').on('click', function() {
            $('#modalExcluir').hide();
            $('#modalEditar').hide();
            usuarioKeyId = null;
        });

        // Tratamento de clique para confirmar exclusão
        $('#confirmarExcluir').on('click', async function() {
            if (usuarioKeyId) {
                try {
                    await $.ajax({
                        url: `/api/login/`,
                        type: 'DELETE',
                        data: {
                            email: usuarioKeyId
                        }
                    });
                    $('#modalExcluir').hide();
                    usuarioKeyId = null;
                    window.location.reload(); // Recarregar a página
                    //carregarUsers();
                } catch (error) {
                    console.error('Erro ao excluir usuário:', error);
                    alert('Erro ao excluir usuário.');
                }
                usuarioKeyId = null;
            }
        });

        $('#confirmarEditar').on('click', async function() {
    if (usuarioKeyId) { // Verifica se o ID do usuário está definido
        try {
            // Obtém os valores dos campos de nome e senha
            var name = $('#editarName').val();
            var password = $('#editarPassword').val();

            var data = {
                email: usuarioKeyId,
            };

            if (name.trim() !== '') {
                data.name = name;
            }

            if (password.trim() !== '') {
                data.password = password;
            }

            // Envia uma requisição AJAX para atualizar os dados do usuário
            await $.ajax({
                url: `/api/login/`,
                type: 'PUT',
                data: data
            });

            // Oculta o modal de edição
            $('#modalEditar').hide();

            // Redireciona para recarregar a página
            window.location.reload();
        } catch (xhr) {
            // Captura e trata qualquer erro que ocorra durante a atualização do usuário
            var mensagemErro = '';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                var erros = xhr.responseJSON.errors;

                // Verifica se há erros relacionados ao nome do usuário
                if (erros.name) {
                    mensagemErro += erros.name.join('<br>');
                }

                // Verifica se há erros relacionados à senha do usuário
                if (erros.password) {
                    // Adiciona uma quebra de linha se já houver mensagens de erro do nome do usuário
                    if (mensagemErro !== '') {
                        mensagemErro += '<br>';
                    }
                    mensagemErro += erros.password.join('<br>');
                }
            } else {
                // Se não houver erros específicos de validação, trata como um erro genérico de requisição
                mensagemErro = 'Erro: ' + xhr.status + ' - ' + xhr.statusText;
            }


            alert(mensagemErro);
            $('#modalEditar').hide();

        }

        // Limpa o ID do usuário após a conclusão da operação
        usuarioKeyId = null;
    }
});    });
</script>
@endsection
