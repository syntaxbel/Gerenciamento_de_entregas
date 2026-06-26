<?php


header('Content-Type: application/json; charset=utf-8');


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


include  '../inc/funcoes.php';
require_once '../inc/conexao.php';


$retorno = [
    'status'  => 'error',
    'message' => 'Erro desconhecido'
];

try
{
    
    verificar_metodo_post();

    
    $id          = $_POST['pId']         ?? '';
    $vNome       = $_POST['pNome']       ?? '';
    $vCpf        = $_POST['pCpf']        ?? '';
    $vNascimento = $_POST['pNascimento'] ?? null;
    $vTelefone   = $_POST['pTelefone']   ?? '';
    $vEmail      = $_POST['pEmail']      ?? '';
    $vLogin      = $_POST['pLogin']      ?? '';
    $vSenha      = $_POST['pSenha']      ?? '';

    
    $id          = trim($id);
    $vNome       = trim($vNome);
    $vCpf        = trim($vCpf);
    $vNascimento = trim($vNascimento) ?: null;
    $vTelefone   = trim($vTelefone);
    $vEmail      = trim($vEmail);
    $vLogin      = trim($vLogin);
    $vSenha      = trim($vSenha);

    
    if ($id == '' || $vNome == '' || $vEmail == '' || $vLogin == '')
    {
        throw new Exception("Preencha todos os campos obrigatórios.");
    }

    if (!is_numeric($id))
    {
        throw new Exception("ID do usuário inválido.");
    }

    
    $conexao->begin_transaction();

    
    $sqlPessoa = $conexao->prepare("
        UPDATE cadastro.tbPessoas
        SET    nome          = ?,
               cpf           = ?,
               nascimento     = ?,
               telefone       = ?,
               email          = ?,
               atualizado_por = 1,
               atualizado_em  = NOW()
        WHERE  pessoa_id = ?
    ");

    if (!$sqlPessoa)
    {
        throw new Exception("Erro no prepare (tbPessoas): " . $conexao->error);
    }

    $sqlPessoa->bind_param("sssssi",
        $vNome,
        $vCpf,
        $vNascimento,
        $vTelefone,
        $vEmail,
        $id
    );

    if (!$sqlPessoa->execute())
    {
        throw new Exception("Erro ao atualizar pessoa: " . $sqlPessoa->error);
    }

    $sqlPessoa->close();

    
    salvar_log(
        "UPDATE cadastro.tbPessoas - id: {$id} | nome: {$vNome}",
        'editar.sql'
    );


    if ($vSenha !== '')
    {
        $vSenhaHash = sha1($vSenha);

        $sqlUsuario = $conexao->prepare("
            UPDATE seguranca.tbUsuarios
            SET    nome          = ?,
                   login         = ?,
                   senha         = ?,
                   atualizado_por = 1,
                   atualizado_em  = NOW()
            WHERE  usuario_id = ?
        ");

        if (!$sqlUsuario)
        {
            throw new Exception("Erro no prepare (tbUsuarios com senha): " . $conexao->error);
        }

        $sqlUsuario->bind_param("sssi",
            $vNome,
            $vLogin,
            $vSenhaHash,
            $id
        );
    }
    else
    {
     
        $sqlUsuario = $conexao->prepare("
            UPDATE seguranca.tbUsuarios
            SET    nome          = ?,
                   login         = ?,
                   atualizado_por = 1,
                   atualizado_em  = NOW()
            WHERE  usuario_id = ?
        ");

        if (!$sqlUsuario)
        {
            throw new Exception("Erro no prepare (tbUsuarios sem senha): " . $conexao->error);
        }

        $sqlUsuario->bind_param("ssi",
            $vNome,
            $vLogin,
            $id
        );
    }

    if (!$sqlUsuario->execute())
    {
        throw new Exception("Erro ao atualizar usuário: " . $sqlUsuario->error);
    }

    if ($sqlUsuario->affected_rows >= 0)
    {
       
        salvar_log(
            "UPDATE seguranca.tbUsuarios - id: {$id} | login: {$vLogin}",
            'editar.sql'
        );

        $retorno = [
            'status'  => 'success',
            'message' => 'Usuário atualizado com sucesso.'
        ];
    }
    else
    {
        throw new Exception("Nenhum registro foi atualizado.");
    }

    $sqlUsuario->close();

    
    $conexao->commit();
    $conexao->close();
}
catch (Throwable $e)
{
    if (isset($conexao) && $conexao instanceof mysqli)
    {
        $conexao->rollback();
    }

    $retorno = [
        'status'  => 'error',
        'message' => $e->getMessage()
    ];
}

echo json_encode($retorno);
exit;
