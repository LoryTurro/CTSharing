<?php

session_start();

require "action.php";

if (!isset($_SESSION["logged"])) {
    header("location: login.php");
    exit();
}

$utente=getUserValue($_SESSION['username']);

$nome = $utente['nome']; $cognome = $utente['cognome']; $username = $utente['username']; $email = $utente['email']; $nPatente = $utente['nPatente'];

if (isset($_REQUEST['registrati'])){
    $nome=$_REQUEST['nome'];
    $cognome=$_REQUEST['cognome'];
    $username=$_REQUEST['username'];
    $email=$_REQUEST['email'];
    $nPatente=$_REQUEST['numero_patente'];
    if(empty(trim($nPatente)))
        $nPatente=NULL;

    if(empty($nome))
        $php_errormsg = "Nome assente";
    elseif(empty($cognome))
        $php_errormsg = "Cognome assente";
    elseif(empty($username))
        $php_errormsg = "Username assente";
    elseif(empty($email))
        $php_errormsg = "Email assente";

    if(!isset($php_errormsg)) {
        if (modify($nome, $cognome, $username, $email, $nPatente, $_SESSION['username'])) {
            sleep(1);
            header("location: index.php");
            //header("location: login.php?s");
        } else
            echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                  <i class='fa fa-times-circle'></i>Username o email gia registrati.</div>";
    } else
        echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
              <i class='fa fa-times-circle'></i>".$php_errormsg."</div>";
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/pages/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="icon" href="img/favicon.ico">
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-3.6.3.min.js"></script>
    <title>CT Sharing</title>

</head>
<body>

<div class="wrapper animate__animated animate__fadeInDownBig">
    <div class="form-left">
        <img src="img/logo.png">
        <p style="margin-top: 15px">
            In questa pagina puoi modificare i tuoi dati per poter sempre essere al passo con i tempi. Aggiorna questi valori quando vuoi e continua ad usufruire dei nostri servizi
        </p>
        <div class="form-field" style="margin-top: 20px; position: center">
            <input type="submit" id="index" name="index" class="account" value="Indietro" onclick="window.location='index.php';">
        </div>
    </div>
    <form class="form-right" name="form" method="post">
        <h2 class="text-uppercase">Modifica Profilo</h2>
        <div id="verifica">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" id="nome" class="input-field" value="<?php echo $nome?>" required>
            </div>
            <div class="mb-3">
                <label>Cognome</label>
                <input type="text" name="cognome" id="cognome" class="input-field" value="<?php echo $cognome?>"required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" class="input-field" name="username" id="username" value="<?php echo $username?>"required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="input-field" name="email" id="email" value="<?php echo $email?>"required>
            </div>
        </div>
        <?php if($_SESSION['gruppo']=="utilizzatore")
            echo'
            <div class="mb-3">
                <label>Numero Patente</label>
                <input type="text" class="input-field" name="numero_patente" id="numero_patente" min="10" maxlength="10" value="'.$nPatente.'"oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
            </div>'; ?>

        <div class="form-field" style="display: flex">
            <input type="submit" style="margin-right: 44px" value="Conferma" class="register" name="registrati" id="registrati">
            <input type="button" style="width: 170px;" id="password" name="password" class="register" value="Cambia password" onclick="window.location='modifyPassword.php';">
        </div>
        <div class="loader" id="loader" style="display: none"></div>
    </form>
</div>
</body>
<script>
    document.getElementById("registrati").addEventListener("click",()=>{
        //attiva la barra di caricamento quando gli input dentro a div "verifica" non sono vuoti
        var inputs = document.querySelectorAll('#verifica input');
        var filled = true;
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value === '') {
                filled = false;
                break;
            }
        }
        if (filled) {
            document.getElementById("loader").style.display="block";
        }
    })
    //animazione uscita quando si clicca il bottone "Hai gia un account"
    document.getElementById("index").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
    document.getElementById("password").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
</script>
</html>