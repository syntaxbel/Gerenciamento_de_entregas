<?php
include  '../inc/funcoes.php';
require_once '../inc/conexao.php';

$origem = $_GET["origem"] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Incluir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>

        function salvarUsuario(origem)
        {
            const vNome       = document.getElementById("nome").value;
            const vCpf        = document.getElementById("cpf").value;
            const vNascimento = document.getElementById("nascimento").value;
            const vTelefone   = document.getElementById("telefone").value;
            const vLogin      = document.getElementById("login").value;
            const vSenha      = document.getElementById("senha").value;

            if (vNome.trim() === "" || vCpf.trim() === "" || vLogin.trim() === "" || vSenha.trim() === "")
            {
                alert("Preencha todos os campos obrigatórios.");
                return;
            }

            $.ajax({
                url:      'acao_incluir.php',
                type:     'POST',
                dataType: 'json',
                data: {
                    pNome:       vNome,
                    pCpf:        vCpf,
                    pNascimento: vNascimento,
                    pTelefone:   vTelefone,
                    pLogin:      vLogin,
                    pSenha:      vSenha
                },
                success: function(data)
                {
                    if (data.status === "success")
                    {
                        alert(data.message);
                        if (origem == 1)
                        {
                            window.location.href = "../login.php";
                        }
                        else
                        {
                            window.location.href = "usuario_listar.php";
                        }
                    }
                    else
                    {
                        alert(data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert("Erro na requisição: " + textStatus + " - " + errorThrown);
                    console.log("Resposta do PHP:", jqXHR.responseText);
                }
            });
        }

        function voltarPagina(origem)
        {
            if (origem == 0)
            {
                window.location.href = "../login.php";
            }
            else
            {
                window.location.href = "usuario_listar.php";
            }
        }

    </script>
</head>

<body class="bg-light min-vh-100 d-flex flex-column">

    <main id="principal" class="container-fluid flex-fill d-flex align-items-start justify-content-center py-4">
        <div class="card shadow p-4 w-100">

            <h2 class="mb-4">Usuário - Incluir</h2>

            <form id="formUsuario" onsubmit="return false;">

                <div class="mb-3">
                    <button type="button" class="btn btn-secondary"
                            onclick="voltarPagina(<?php echo $origem; ?>)">
                        Voltar
                    </button>
                    <button type="button" class="btn btn-success me-2"
                            onclick="salvarUsuario(<?php echo $origem; ?>)">
                        Gravar
                    </button>
                </div>

                <hr>

                
                <h5 class="mb-3 text-muted">Dados Pessoais</h5>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                    <input type="text" id="nome" name="pNome" class="form-control"
                           placeholder="Ex: Maria da Silva" required>
                </div>

                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                    <input type="text" id="cpf" name="pCpf" class="form-control"
                           placeholder="000.000.000-00" maxlength="14" required>
                </div>

                <div class="mb-3">
                    <label for="nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" id="nascimento" name="pNascimento" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" id="telefone" name="pTelefone" class="form-control"
                           placeholder="(00) 00000-0000" maxlength="20">
                </div>

                <hr>

                
                <h5 class="mb-3 text-muted">Dados de Acesso</h5>

                <div class="mb-3">
                    <label for="login" class="form-label">Login <span class="text-danger">*</span></label>
                    <input type="text" id="login" name="pLogin" class="form-control"
                           placeholder="Ex: maria.empresa" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                    <input type="password" id="senha" name="pSenha" class="form-control" required>
                </div>

            </form>

        </div>
    </main>

</body>
</html>
