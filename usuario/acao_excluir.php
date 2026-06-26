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

    
    $usuario_id = $_POST['pUsuario_id'] ?? '';
    $usuario_id = trim($usuario_id);

    
    if ($usuario_id == '')
    {
        throw new Exception("ID do usuário não informado.");
    }

    if (!is_numeric($usuario_id))
    {
        throw new Exception("ID do usuário inválido.");
    }

    
    $conexao->begin_transaction();

    
    salvar_log(
        "DELETE seguranca.tbUsuarios + cadastro.tbPessoas WHERE usuario_id = {$usuario_id}",
        'delete.sql'
    );

    
    $sqlUsuario = $conexao->prepare(
        "DELETE FROM seguranca.tbUsuarios WHERE usuario_id = ?"
    );

    if (!$sqlUsuario)
    {
        throw new Exception("Erro no prepare (tbUsuarios): " . $conexao->error);
    }

    $sqlUsuario->bind_param("i", $usuario_id);

    if (!$sqlUsuario->execute())
    {
        throw new Exception("Erro ao excluir usuário: " . $sqlUsuario->error);
    }

    $sqlUsuario->close();

    
    $sqlPessoa = $conexao->prepare(
        "DELETE FROM cadastro.tbPessoas WHERE pessoa_id = ?"
    );

    if (!$sqlPessoa)
    {
        throw new Exception("Erro no prepare (tbPessoas): " . $conexao->error);
    }

    $sqlPessoa->bind_param("i", $usuario_id);

    if ($sqlPessoa->execute())
    {
        $retorno = [
            'status'  => 'success',
            'message' => 'Usuário excluído com sucesso!'
        ];
    }
    else
    {
        throw new Exception("Erro ao excluir pessoa: " . $sqlPessoa->error);
    }

    $sqlPessoa->close();


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
