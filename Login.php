<?php
require('config/conexao.php');

if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
    //RECEBER OS DADOS VINDO DO POST E LIMPAR
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);   
    //VERIFICAR SE EXISTE ESTE USUÁRIO
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$senha_cript));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    if($usuario){
        //verifica se o email foi confirmado 
        if($usuario['status']=="confirmado"){
            //CRIAR UM TOKEN
            $token = sha1(uniqid().date('d-m-Y-H-i-s'));

            //ATUALIZAR O TOKEN DESTE USUARIO NO BANCO
            $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
            if($sql->execute(array($token,$email,$senha_cript))){
                //ARMAZENAR ESTE TOKEN NA SESSAO (SESSION)
                $_SESSION['TOKEN'] = $token;
                header('location: restrita.php');
            }
        }else{
            $erro_login = "Por favor confirme seu cadastro no seu e-mail cadastrado!";
        }        

    }else{
        $erro_login = "Email e/ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <img style="width: 700px;" class="ms-5 p-5" src="img/astronauta.jpg" alt="">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <form method="post" class="mb-md-5 mt-md-4 pb-5">

                                <h2 class="fw-bold mb-5 text-uppercase">Login</h2>

                                <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
                                    <div class="alert alert-success">
                                    <?php  echo "Usuario cadastrado com sucesso"; ?>
                                    </div>
                                <?php } ?>

                                <?php if(isset($erro_login)){ ?>
                                    <div class="alert alert-danger">
                                    <?php  echo $erro_login; ?>
                                    </div>
                                <?php } ?>

                                <?php if(isset($erro_geral)){ ?>
                                    <div class="alert alert-danger">
                                    <?php  echo "Email ou senha incorretos"; ?>
                                    </div>
                                <?php } ?>

                                <div class="form-outline form-white mb-4 text-start">
                                    <input name="email" type="email" id="email" placeholder="Email" class="form-control form-control-lg" required>
                                </div>

                                <div class="form-outline form-white text-start">
                                    <input name="senha" type="password" id="senha" placeholder="Senha" class="form-control form-control-lg mb-3" required>
                                </div>

                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#">Esqueceu Sua Senha?</a>
                                </p>

                                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>

                            </form>

                            <div>
                                <p class="mb-0">Ainda não tem conta? <a href="cadastrar.php"
                                        class="text-white-50 fw-bold">Registre-se</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
</body>

</html>