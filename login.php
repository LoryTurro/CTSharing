<?php

require "action.php";
//funzione per mandare il codice in sleep con un indice di millisecondi invece che di secondi (come la funzione sleep())
function m_sleep($milliseconds) {
    return usleep($milliseconds * 1000);
}

if (isset($_GET['s'])){
    echo "<div class='success-msg' style='color: #270; background-color: #DFF2BF;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
          <i class='fa fa-check'></i>Registrazione effettuata con successo.</div>";
}

$username=null;

if (isset($_REQUEST['accedi'])){
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    switch (login($username, $password)){
        case "accettato":
            sleep(1);
            header("location: index.php");
            break;
        case "utente":
            m_sleep(350);
            echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
              <i class='fa fa-times-circle'></i>L'utente non esiste.</div>";
            break;
        case "password":
            m_sleep(350);
            echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
              <i class='fa fa-times-circle'></i>Password errata.</div>";
            break;
    }
}

?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/pages/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-3.6.3.min.js"></script>
    <title>CT Sharing</title>

    <style>

    </style>

</head>
<body>
<div class="wrapper animate__animated animate__fadeInDownBig">
    <div class="form-left">
        <img src="logo2.png">
        <p style="margin-top: 15px">
            Benvenuto nella pagina di login del nostro sistema di prenotazione di veicoli! Qui puoi accedere al tuo account e utilizzare le nostre funzionalità di ricerca e prenotazione di auto, scooter, moto, biciclette, monopattini e molto altro.
        </p>
        <div class="form-field animate__animated animate__headShake animate__delay-1s" style="margin-top: 20px; position: center">
            <input type="submit" id="account" name="account" class="account" value="Non hai un account?" onclick="window.location='register.php';">
        </div>
    </div>
    <form class="form-right" name="form" method="post">
        <div id="verifica">
            <h2 class="text-uppercase">Login</h2>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" class="input-field" name="username" id="username" value="<?= $username?>" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" id="password" class="input-field" required>
            </div>
        </div>
        <div class="form-field" style="display: flex; margin-top: 15px">
            <input type="submit" value="Accedi" class="register" name="accedi" id="accedi">
        </div>
        <div class="loader" id="loader" style="display: none"></div>
    </form>
</div>
</body>
<script>
    document.getElementById("accedi").addEventListener("click",()=>{
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
    //animazione uscita quando si clicca il bottone "Non hai un account"
    document.getElementById("account").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
</script>
</html>