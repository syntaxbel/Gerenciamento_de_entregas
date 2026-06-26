<?php
require_once '../inc/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários - Listar</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        @media print {
            #menu, #rodape, .barra-botoes { display: none !important; }
            body  { background: #fff !important; }
            .card { border: 0; box-shadow: none !important; }
        }
    </style>

    <script>

      
        function editar()
        {
            const marcados = document.querySelectorAll('input[name="check_id"]:checked');

            if (marcados.length === 0)
            {
                alert("Selecione um registro para editar.");
                return;
            }

            if (marcados.length > 1)
            {
                alert("Selecione apenas um registro para editar.");
                return;
            }

            const id = marcados[0].value;
            window.location.href = "usuario_editar.php?usuario_id=" + encodeURIComponent(id);
        }

        async function excluir()
        {
            const marcados = document.querySelectorAll('input[name="check_id"]:checked');

            if (marcados.length === 0)
            {
                alert("Selecione um registro para excluir.");
                return;
            }

            if (marcados.length > 1)
            {
                alert("Selecione apenas um registro para excluir.");
                return;
            }

            if (!confirm("Deseja realmente excluir este usuário?"))
            {
                return;
            }

            const usuario_id = marcados[0].value;

            try
            {
                const dados = new FormData();
                dados.append("pUsuario_id", usuario_id);

                const resposta = await fetch("acao_excluir.php", {
                    method: "POST",
                    body:   dados
                });

                const data = await resposta.json();
                alert(data.message);

                if (data.status === "success")
                {
                    location.reload();
                }
            }
            catch (erro)
            {
                console.error(erro);
                alert("Erro ao excluir usuário.");
            }
        }

        
        document.addEventListener('DOMContentLoaded', function()
        {
            const checkTodos = document.getElementById('checkTodos');

            if (checkTodos)
            {
                checkTodos.addEventListener('change', function()
                {
                    document.querySelectorAll('input[name="check_id"]')
                        .forEach(cb => cb.checked = checkTodos.checked);
                });
            }
        });

       
        function imprimir()
        {
            window.print();
        }

    </script>
</head>

<body class="bg-light min-vh-100 d-flex flex-column">

   
    <main id="principal" class="container-fluid flex-fill d-flex align-items-start justify-content-center py-4">
        <div class="card shadow p-4 w-100">

            <h2 class="mb-4">Usuários - Listar</h2>

            
            <div class="mb-3 barra-botoes">

                <button type="button" class="btn btn-secondary me-2"
                        onclick="window.location.href='../principal.html'">
                    Voltar
                </button>

                <button type="button" class="btn btn-primary me-2"
                        onclick="window.location.href='usuario_incluir.php?origem=1'">
                    Incluir
                </button>

                <button type="button" class="btn btn-primary me-2"
                        onclick="javascript:editar();">
                    Editar
                </button>

                <button type="button" class="btn btn-danger me-2"
                        onclick="javascript:excluir();">
                    Excluir
                </button>

                <button type="button" class="btn btn-inverse me-2"
                        onclick="javascript:imprimir();">
                    Imprimir
                </button>

            </div>

           
            <table class="table table-striped table-hover align-middle">

               
                <thead class="table-primary">
                    <tr>
                        <th scope="col">
                            <input type="checkbox" id="checkTodos">
                        </th>
                        <th scope="col">ID</th>
                        <th scope="col">Login</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">E-mail</th>
                    </tr>
                </thead>

                
                <tbody>

                <?php

                  
                    $sql = "
                        SELECT  u.usuario_id,
                                u.login,
                                p.nome,
                                p.cpf,
                                p.telefone,
                                p.email
                        FROM    seguranca.tbUsuarios AS u
                        JOIN    cadastro.tbPessoas   AS p
                                ON p.pessoa_id = u.usuario_id
                        ORDER BY p.nome
                    ";

                    $result = $conexao->query($sql);
                    $i      = 0;

                    if ($result && $result->num_rows > 0)
                    {
                        while ($row = $result->fetch_assoc())
                        {
                            $usuario_id = urlencode($row['usuario_id']);
                            $login      = htmlspecialchars($row['login']);
                            $nome       = htmlspecialchars(utf8_encode($row['nome']));
                            $cpf        = htmlspecialchars($row['cpf']);
                            $telefone   = htmlspecialchars($row['telefone']);
                            $email      = htmlspecialchars($row['email']);

                            echo "<tr>";
                            echo "  <td><input type='checkbox' name='check_id' value='{$usuario_id}'></td>";
                            echo "  <td>{$row['usuario_id']}</td>";
                            echo "  <td><a href='usuario_editar.php?usuario_id={$usuario_id}' class='text-decoration-none'>{$login}</a></td>";
                            echo "  <td>{$nome}</td>";
                            echo "  <td>{$cpf}</td>";
                            echo "  <td>{$telefone}</td>";
                            echo "  <td>{$email}</td>";
                            echo "</tr>";

                            $i++;
                        }
                    }
                    else
                    {
                        echo "<tr>";
                        echo "  <td colspan='7' class='text-center text-muted py-4'>Nenhum registro encontrado.</td>";
                        echo "</tr>";
                    }

                ?>

                </tbody>
            </table>

        </div>
    </main>

 

</body>
</html>
