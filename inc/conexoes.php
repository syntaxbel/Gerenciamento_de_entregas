<?php

define('DB_HOST',   'savir069bd.vpshost12372.mysql.dbaas.com.br');
define('DB_USER',   'savir069bd');   
define('DB_PASS',   'savir069#BD');
define('DB_NAME',   'savir069bd');
 
$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 
if ($conexao->connect_error)
{
    die(json_encode([
        'status'  => 'error',
        'message' => 'Falha na conexão com o banco: ' . $conexao->connect_error
    ]));
}
 
$conexao->set_charset('utf8');
