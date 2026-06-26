<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../inc/funcoes.php';
require_once '../inc/conexao.php';

$retorno = [
    'status'  => 'error',
    'message' => 'Erro desconhecido'
];

try
{
    verificar_metodo_post();

    $vNome       = trim($_POST['pNome'] ?? '');
    $vCpf        = trim($_POST['pCpf'] ?? '');
    $vNascimento = trim($_POST['pNascimento'] ?? '');
    $vTelefone   = trim($_POST['pTelefone'] ?? '');
    $vLogin      = trim($_POST['pLogin'] ?? '');
    $vSenha      = trim($_POST['pSenha'] ?? '');

    if (
        empty($vNome) ||
        empty($vCpf) ||
        empty($vLogin) ||
        empty($vSenha)
    )
    {
        throw new Exception('Preencha todos os campos obrigatórios.');
    }

    $vNascimento = ($vNascimento === '') ? null : $vNascimento;

    $vSenhaHash = sha1($vSenha);

    $conexao->begin_transaction();

    
    $sqlPessoa = $conexao->prepare("
        INSERT INTO `cadastro.tbPessoas`
        (
            nome,
            cpf,
            nascimento,
            telefone,
            pessoa_tipo_id,
            atualizado_por,
            atualizado_em
        )
        VALUES
        (
            ?, ?, ?, ?, 1, 1, CURDATE()
        )
    ");

    if (!$sqlPessoa)
    {
        throw new Exception(
            'Erro prepare tbPessoas: ' . $conexao->error
        );
    }

    $sqlPessoa->bind_param(
        "ssss",
        $vNome,
        $vCpf,
        $vNascimento,
        $vTelefone
    );

    if (!$sqlPessoa->execute())
    {
        throw new Exception(
            'Erro ao inserir pessoa: ' . $sqlPessoa->error
        );
    }

    $novaPessoaId = $conexao->insert_id;

    $sqlPessoa->close();

    $sqlUsuario = $conexao->prepare("
        INSERT INTO `seguranca.tbUsuarios`
        (
            usuario_id,
            nome,
            login,
            senha,
            atualizado_por
        )
        VALUES
        (
            ?, ?, ?, ?, 1
        )
    ");

    if (!$sqlUsuario)
    {
        throw new Exception(
            'Erro prepare tbUsuarios: ' . $conexao->error
        );
    }

    $sqlUsuario->bind_param(
        "isss",
        $novaPessoaId,
        $vNome,
        $vLogin,
        $vSenhaHash
    );

    if (!$sqlUsuario->execute())
    {
        throw new Exception(
            'Erro ao inserir usuário: ' . $sqlUsuario->error
        );
    }

    $sqlUsuario->close();

    $conexao->commit();

    $retorno = [
        'status'  => 'success',
        'message' => 'Usuário cadastrado com sucesso!',
        'id'      => $novaPessoaId
    ];
}
catch (Throwable $e)
{
    if (isset($conexao))
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
