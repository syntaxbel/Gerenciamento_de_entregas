<?php

header('Content-Type: application/json; charset=utf-8');


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


include 'inc/funcoes.php';
require_once 'inc/conexao.php';


$retorno = [
    'status'  => 'error',
    'message' => 'Erro desconhecido'
];

try
{
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST")
    {
        throw new Exception("Método inválido.");
    }

   
    $vUsuario = trim($_POST['pUsuario'] ?? '');
    $vSenha   = trim($_POST['pSenha'] ?? '');

    
    if ($vUsuario === '' || $vSenha === '')
    {
        throw new Exception("Preencha todos os campos.");
    }

    
    $vSenhaHash = sha1($vSenha);

    
    $sql = $conexao->prepare("
        SELECT
            usuario_id,
            nome,
            login
        FROM `seguranca.tbUsuarios`
        WHERE login = ?
          AND senha = ?
    ");

    if (!$sql)
    {
        throw new Exception(
            "Erro no prepare: " . $conexao->error
        );
    }

    $sql->bind_param(
        "ss",
        $vUsuario,
        $vSenhaHash
    );

    if (!$sql->execute())
    {
        throw new Exception(
            "Erro ao executar consulta: " . $sql->error
        );
    }

    $resultado = $sql->get_result();

    if ($resultado->num_rows === 1)
    {
        $usuario = $resultado->fetch_assoc();

        session_start();

        $_SESSION['usuario_id'] = $usuario['usuario_id'];
        $_SESSION['nome']       = $usuario['nome'];
        $_SESSION['login']      = $usuario['login'];

        $retorno = [
            'status'   => 'success',
            'message'  => 'Login realizado com sucesso!',
            'redirect' => '/operario/mainpage.php'
        ];
    }
    else
    {
        $retorno = [
            'status'  => 'error',
            'message' => 'Usuário ou senha incorretos.'
        ];
    }

    $sql->close();
    $conexao->close();
}
catch (Throwable $e)
{
    $retorno = [
        'status'  => 'error',
        'message' => $e->getMessage()
    ];
}

echo json_encode($retorno);
exit;
