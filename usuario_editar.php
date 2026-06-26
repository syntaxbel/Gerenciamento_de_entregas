<?php

include  '../inc/funcoes.php';
require_once '../inc/conexao.php';


$usuario_id = $_GET["usuario_id"] ?? '';


if ($usuario_id == '')
{
    echo "<script>
            alert('Usuário não informado.');
            window.location.href='usuario_listar.php';
          </script>";
    exit;
}


$nome       = '';
$cpf        = '';
$nascimento = '';
$telefone   = '';
$email      = '';
$login      = '';


$sql = "
    SELECT  u.usuario_id,
            u.login,
            p.nome,
            p.cpf,
            p.nascimento,
            p.telefone,
            p.email
    FROM    seguranca.tbUsuarios AS u
    JOIN    cadastro.tbPessoas   AS p
            ON p.pessoa_id = u.usuario_id
    WHERE   u.usuario_id = ?
";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($idBanco, $login, $nome, $cpf, $nascimento, $telefone, $email);

if (!$stmt->fetch())
{
    echo "<script>
            alert('Usuário não encontrado.');
            window.location.href='usuario_listar.php';
          </script>";
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Editar</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>

        
        function salvarUsuario()
        {
            const vId         = document.getElementById("id").value;
            const vNome       = document.getElementById("nome").value;
            const vCpf        = document.getElementById("cpf").value;
            const vNascimento = document.getElementById("nascimento").value;
            const vTelefone   = document.getElementById("telefone").value;
            const vEmail      = document.getElementById("email").value;
            const vLogin      = document.getElementById("login").value;
            const vSenha      = document.getElementById("senha").value;

            if (
                vId.trim()    === "" ||
                vNome.trim()  === "" ||
                vEmail.trim() === "" ||
                vLogin.trim() === ""
            )
            {
                alert("Preencha todos os campos obrigatórios.");
                return;
            }

            
            $.ajax({
                url:      'acao_editar.php',
                type:     'POST',
                dataType: 'json',
                data: {
                    pId:         vId,
                    pNome:       vNome,
                    pCpf:        vCpf,
                    pNascimento: vNascimento,
                    pTelefone:   vTelefone,
                    pEmail:      vEmail,
                    pLogin:      vLogin,
                    pSenha:      vSenha
                },
                success: function(data)
                {
                    if (data.status === "success")
                    {
                        alert(data.message);
                        window.location.href = "usuario_listar.php";
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

      
        function voltarPagina()
        {
            window.location.href = "usuario_listar.php";
        }

    </script>
</head>

<body class="bg-light min-vh-100 d-flex flex-column">

    
    <main id="principal" class="container-fluid flex-fill d-flex align-items-start justify-content-center py-4">
        <div class="card shadow p-4 w-100">

            <h2 class="mb-4">Usuário - Editar</h2>

            <form id="formUsuario" onsubmit="return false;">

                
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary"
                            onclick="voltarPagina()">
                        Voltar
                    </button>
                    <button type="button" class="btn btn-success me-2"
                            onclick="salvarUsuario()">
                        Gravar
                    </button>
                </div>

                <hr>

                
                <div class="mb-3">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" id="id" name="pId" class="form-control"
                           readonly style="background-color: #e9ecef;"
                           value="<?php echo htmlspecialchars($usuario_id); ?>">
                </div>

                
                <h5 class="mb-3 text-muted">Dados Pessoais</h5>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                    <input type="text" id="nome" name="pNome" class="form-control" required
                           value="<?php echo htmlspecialchars($nome); ?>">
                </div>

                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" id="cpf" name="pCpf" class="form-control" maxlength="14"
                           value="<?php echo htmlspecialchars($cpf); ?>">
                </div>

                <div class="mb-3">
                    <label for="nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" id="nascimento" name="pNascimento" class="form-control"
                           value="<?php echo htmlspecialchars($nascimento); ?>">
                </div>

                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" id="telefone" name="pTelefone" class="form-control" maxlength="20"
                           value="<?php echo htmlspecialchars($telefone); ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="pEmail" class="form-control" required
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <hr>

                
                <h5 class="mb-3 text-muted">Dados de Acesso</h5>

                <div class="mb-3">
                    <label for="login" class="form-label">Login <span class="text-danger">*</span></label>
                    <input type="text" id="login" name="pLogin" class="form-control" required
                           value="<?php echo htmlspecialchars($login); ?>">
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Nova Senha</label>
                    <input type="password" id="senha" name="pSenha" class="form-control">
                    <small class="text-muted">Deixe em branco para não alterar a senha.</small>
                </div>

            </form>

        </div>
    </main>



</body>
</html>
