<?php
session_start();
/* DOIS MODOS POSSÍVEIS -> local, producao*/
$modo = 'local'; 

if($modo =='local'){
    $servidor ="localhost";
    $usuario = "root";
    $senha = "";
    $banco = "login";
}

if($modo =='producao'){
    $servidor ="";
    $usuario = "";
    $senha = "";
    $banco = "";
}

try{
   $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha); 
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
   //echo "Banco conectado com sucesso!"; 
}catch(PDOException $erro){
    echo "Falha ao se conectar com o banco! ";
}

function limparPost($dados){ //anti-cheats
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

//FUNÇÃO PARA AUTENTICAÇÃO
function auth($tokenSessao){
    global $pdo;
    //VERIFICAR SE TEM AUTORIZAÇÃO
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($tokenSessao));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    //SE NÃO ENCONTRAR O USUÁRIO
    if(!$usuario){
        return false;
    }else{
       return $usuario;
    }
}



