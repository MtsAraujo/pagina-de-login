<?php
require('config/conexao.php');

//VERIFICAR SE A POSTAGEM EXISTE DE ACORDO COM OS CAMPOS
if(isset($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
    //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
    if(empty($_POST['nome_completo']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){
        $erro_geral = "Todos os campos são obrigatórios!";
    }else{
        //RECEBER VALORES VINDOS DO POST E LIMPAR
        $nome = limparPost($_POST['nome_completo']);
        $email =limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);//criptografia de senha
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //VERIFICAR SE NOME É APENAS LETRAS E ESPAÇOS
        if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        //VERIFICAR SE EMAIL É VÁLIDO
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_email = "Formato de e-mail inválido!";
        }

        //VERIFICAR SE SENHA TEM MAIS DE 6 DÍGITOS
        if(strlen($senha) < 6 ){
            $erro_senha = "Senha deve ter 6 caracteres ou mais!";
        }

        //VERIFICAR SE RETEPE SENHA É IGUAL A SENHA
        if($senha !== $repete_senha){
            $erro_repete_senha = "Senha e repetição de senha diferentes!";
        }

        //VERIFICAR SE CHECKBOX FOI MARCADO
        if($checkbox!=="ok"){
            $erro_checkbox = "Desativado";
        }

        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)){
            //VERIFICAR SE ESTE EMAIL JÁ ESTÁ CADASTRADO NO BANCO
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuario = $sql->fetch();
            //SE NÃO EXISTIR O USUARIO - ADICIONAR NO BANCO
            if(!$usuario){
                $id += 1 ;
                $recupera_senha="";
                $token="";
                $status = "novo";
                $data_cadastro = date('d/m/Y');
                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (?,?,?,?,?,?,?,?)");
                if($sql->execute(array($id,$nome,$email,$senha_cript,$recupera_senha,$token,$status, $data_cadastro))){
                    header('location: Login.php?result=ok');
                }
            }else{
                //JÁ EXISTE USUARIO APRESENTAR ERRO
                $erro_geral = "Usuário já cadastrado";
            }
        }

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
    <title>Cadastrar</title>
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <img style="width: 700px;" class="" src="img/astronauta.jpg" alt="">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white mb-5 " style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form method="post">
                                <h1 class="fw-bold mb-5 text-uppercase">Cadastrar</h1>
                                
                                <?php if(isset($erro_geral)){ ?>
                                    <div class="alert alert-danger">
                                    <?php  echo $erro_geral; ?>
                                    </div>
                                <?php } ?>
                                

                                <div class="form-outline form-white text-start mb-4">
                                    <input <?php if(isset($erro_geral) or isset($erro_nome)){echo 'class="erro-input form-control form-control-lg"';}?> class="form-control form-control-lg" name="nome_completo" type="text" placeholder="Nome Completo" <?php if(isset($_POST['nome_completo'])){ echo "value='".$_POST['nome_completo']."'";}?> required>
                                    <?php if(isset($erro_nome)){ ?>
                                    <div class="erro"><?php echo $erro_nome; ?></div>
                                    <?php } ?>    
                                </div>

                                <div class="form-outline form-white text-start mb-4">
                                    <input <?php if(isset($erro_geral) or isset($erro_email)){echo 'class="erro-input form-control form-control-lg"';}?> class="form-control form-control-lg" type="email" name="email" placeholder="email" <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";}?> required>
                                    <?php if(isset($erro_email)){ ?>
                                    <div class="erro"><?php echo $erro_email; ?></div>
                                    <?php } ?>     
                                </div>

                                <div class="form-outline form-white text-start mb-4">
                                    <input type="password" <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class="erro-input form-control form-control-lg"';}?> class="form-control form-control-lg" name="senha" placeholder="Senha mínimo 6 Dígitos" <?php if(isset($_POST['senha'])){ echo "value='".$_POST['senha']."'";}?> required>
                                    <?php if(isset($erro_senha)){ ?>
                                    <div class="erro"><?php echo $erro_senha; ?></div>
                                    <?php } ?>     
                                </div>

                                <div class="form-outline form-white text-start mb-4">
                                    <input type="password" <?php if(isset($erro_geral) or isset($erro_repete_senha)){echo 'class="erro-input form-control form-control-lg"';}?> class="form-control form-control-lg" name="repete_senha" placeholder="Repita a senha criada" <?php if(isset($_POST['repete_senha'])){ echo "value='".$_POST['repete_senha']."'";}?> required>
                                    <?php if(isset($erro_repete_senha)){ ?>
                                    <div class="erro"><?php echo $erro_repete_senha; ?></div>
                                    <?php } ?>                 
                                </div>   
                                
                                <div class="form-check mb-5" <?php if(isset($erro_geral) or isset($erro_checkbox)){echo 'class="input-group erro-input form-control form-control-lg"';}else{echo 'class="input-group"';}?>>
                                    <input class="form-check-input" type="checkbox" id="termos" name="termos" value="ok" required>
                                    <label class="form-check-label" for="termos">Ao se cadastrar você concorda com a nossa <a class="text-white-50 fw-bold" href="#">Política de Privacidade</a> e os <a class="text-white-50 fw-bold" href="#">Termos de uso</a></label>
                                </div>  
                            
                                
                                <button class="btn btn-outline-light btn-lg px-5 mb-5" type="submit">Cadastrar</button>
                            </form>

                            <div>
                                <p class="mb-0">Já possui Conta? <a href="Login.php"
                                        class="text-white-50 fw-bold">Login</a>
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