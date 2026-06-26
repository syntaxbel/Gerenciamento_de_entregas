<?php

function salvar_log($sql, $arquivo)
{
    $pasta = '../logs/';

    
    if (!is_dir($pasta))
    {
        mkdir($pasta, 0755, true);
    }

    $linha = date('Y-m-d H:i:s') . " | " . $sql . PHP_EOL;
    file_put_contents($pasta . $arquivo, $linha, FILE_APPEND);
}


function verificar_metodo_post()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        throw new Exception('Método inválido.');
    }
}


function limpar($valor)
{
    return trim(htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'));
}
