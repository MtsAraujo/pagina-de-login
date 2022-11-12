<?php
require('config/conexao.php');

//VERIFICAÇÃO DE AUTENTICAÇÃO
$user = auth($_SESSION['TOKEN']);
if ($user){
    echo '<!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">';
    echo "<h1 class='text-light text-center'> SEJA BEM-VINDO  ".$user['nome']."!</h1>";
    echo "<br><br><a style='max-width: 150px ;' class='btn btn-primary p-3 ms-5'; href='logout.php'>Sair do sistema</a>";
}else{
    //REDIRECIONAR PARA LOGIN
    header('location: login.php'); 
}
