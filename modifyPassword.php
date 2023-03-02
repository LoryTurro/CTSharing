<?php

session_start();

require "action.php";

if (!isset($_SESSION["logged"])) {
    header("location: login.php");
    exit();
}

if (isset($_REQUEST['registrati'])){
    $pwd=$_REQUEST['pwd'];
    $newPwd=$_REQUEST['newPwd'];
    $confNewPwd=$_REQUEST['confNewPwd'];


    if(empty($pwd))
        $php_errormsg = "Password assente";
    elseif(empty($newPwd))
        $php_errormsg = "Nuova password assente";
    elseif(empty($confNewPwd))
        $php_errormsg = "Conferma nuova password assente";

    if(!isset($php_errormsg)) {
        switch(updatePwd($_SESSION['username'], $pwd, $newPwd, $confNewPwd)) {
            case "ok":
                echo "gay";
                sleep(1);
                header("location: index.php");
            //header("location: login.php?s");
                break;
            case "wrongpass":
                echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                <i class='fa fa-times-circle'></i>Password attuale errata</div>";
                break;
            case "missmpass":
                echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                <i class='fa fa-times-circle'></i>Le password non corrispondono</div>";
                break;
            case "error":
                echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                <i class='fa fa-times-circle'></i>C'è stato un errore durante l'inserimento</div>";
        }
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
            In questa pagina potrai modificare la tua password. Ti consigliamo di scegliere una password di almeno 8 caratteri che abbia lettere maiuscole, minuscole, numeri e simboli. Noi non vogliamo obbligarti quindi a te la scelta!
        </p>
        <div class="form-field animate__animated animate__headShake animate__delay-1s" style="margin-top: 20px; position: center; display: flex; justify-content: space-between">
            <input type="submit" id="modify" name="modify" class="account" value="Torna alla modifica" onclick="window.location='modify.php';">
            <input type="submit" id="index" name="index" class="account" value="Torna ai veicoli" style="margin-left: 20px" onclick="window.location='index.php#section_2';">
        </div>
    </div>
    <form class="form-right" name="form" method="post">
        <h2 class="text-uppercase" style="margin-bottom: 12px;">Modifica Password</h2>
        <h1 class="text-uppercase" >Modifica password <?php echo $_SESSION['username'] ?> </h1>
        <div id="verifica">
            <div class="mb-3">
                <label>Password attuale</label>
                <input type="password" name="pwd" id="pwd" class="input-field" required>
            </div>
            <div class="mb-3">
                <label>Nuova password</label>
                <input type="password" name="newPwd" id="newPwd" class="input-field" required>
            </div>
            <div class="mb-3">
                <label>Conferma nuova password</label>
                <input type="password" class="input-field" name="confNewPwd" id="confNewPwd" required>
            </div>

        <div class="form-field" style="display: flex">
            <input type="submit" value="Conferma" class="register" name="registrati" id="registrati">
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
    document.getElementById("modify").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
    document.getElementById("index").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
</script>
</html>