<?php

require "action.php";

$nome = null; $cognome = null; $username = null; $email = null; $nPatente = null;

if (isset($_REQUEST['registrati'])){
    $nome=$_REQUEST['nome'];
    $cognome=$_REQUEST['cognome'];
    $username=$_REQUEST['username'];
    $email=$_REQUEST['email'];
    $pwd=$_REQUEST['pwd'];
    $cpwd=$_REQUEST['cpwd'];
    $nPatente=$_REQUEST['numero_patente'];
    if(empty(trim($nPatente)))
        $nPatente=NULL;

    if (isset($_REQUEST['gruppo']))
        $gruppo="socio";
    else
        $gruppo="utilizzatore";

    if($pwd == $cpwd) {
        $enc_pwd = password_hash($pwd, PASSWORD_DEFAULT);
        if (register($nome, $cognome, $username, $email, $enc_pwd, $nPatente, $gruppo)) {
            sleep(1);
            //auto login dopo la registrazione
            switch (login($username, $pwd)){
                case "accettato":
                    sleep(1);
                    header("location: index.php");
                    break;
            }
            //header("location: login.php?s");
        }
        else
            echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                  <i class='fa fa-times-circle'></i>Username o email gia registrati.</div>";
    }
    else
        echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
              <i class='fa fa-times-circle'></i>Le password non corrispondono.</div>";

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
        /* Infobox socio */
        .infobox {
            position: absolute;
            background-color: #ffffff;
            padding: 10px;
            border: 2px solid #3786bd;
            border-radius: 10px;
            z-index: 1;
            max-width: 15%;
            box-sizing: border-box;
            font-weight: 600;
            display: none;
            left: 20px;
            font-size: 13px;
        }
        .infobox:before {
            content: "";
            position: absolute;
            top: 50%;
            left: -15px;
            margin-top: -10px;
            border-top: 10px solid transparent;
            border-right: 10px solid #3786bd;
            border-bottom: 10px solid transparent;
            border-left: none;
        }
    </style>

</head>
<body>
<div class="infobox animate__animated animate__fadeInRight animate__faster" id="info">
    Essere socio vuol dire avere la possibilità di poter creare il tuo sistema di sharing e di integrarlo con gli altri già esistenti
</div>

<div class="wrapper animate__animated animate__fadeInDownBig">
    <div class="form-left">
        <img src="img/logo.png">
        <p style="margin-top: 15px">
            Benvenuto nella pagina di registrazione del nostro sistema di prenotazione di veicoli! Qui potrai registrarti come utente e accedere alle nostre funzionalità di ricerca e prenotazione di auto, scooter, moto, biciclette, monopattini e molto altro. Inserisci i tuoi dati personali per registrarti e iniziare ad utilizare i nostri servizi.
        </p>
        <div class="form-field animate__animated animate__headShake animate__delay-1s" style="margin-top: 20px; position: center">
            <input type="submit" id="account" name="account" class="account" value="Hai già un account?" onclick="window.location='login.php';">
        </div>
    </div>
    <form class="form-right" name="form" method="post">
        <h2 class="text-uppercase">Registrazione</h2>
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
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="pwd" id="pwd" class="input-field" required>
            </div>
            <div class="mb-3">
                <label>Conferma Password</label>
                <input type="password" name="cpwd" id="cpwd" class="input-field" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Numero Patente</label>
            <input type="text" class="input-field" name="numero_patente" id="numero_patente" min="10" maxlength="10" value="<?php echo $nPatente?> "oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
        </div>
        <div class="form-check" style="display: flex; align-items: center;">
            <input class="check" type="checkbox" role="switch" id="gruppo" name="gruppo" <?= isset($_REQUEST['gruppo'])?"checked":"" ?>>
            <label id="infobox" style="margin: 0" class="form-check-label" for="flexSwitchCheckDefault">Vuoi diventare socio?</label>
        </div>

        <div class="form-field" style="display: flex">
            <input type="submit" value="Registrati" class="register" name="registrati" id="registrati">
        </div>
        <div class="loader" id="loader" style="display: none"></div>
    </form>
</div>
</body>
<script>
    const infobox = document.querySelector('.infobox');
    document.getElementById("infobox").addEventListener("mouseenter",()=>{
        //rende visibile il div con le info per i soci
        infobox.classList.remove('animate__fadeOutRight');
        document.getElementById("info").style.display="block"
        //allinea il div con la scritta id=infobox (Vuoi diventare socio?)
        var rect = document.getElementById("infobox").getBoundingClientRect()
        var info = document.getElementById("info")
        info.style.top=(rect.top+window.scrollY-info.getBoundingClientRect().height/2/1.25)+"px"
        info.style.left=(rect.left+window.scrollX+rect.width+20)+"px"
    })
    document.getElementById("infobox").addEventListener("mouseleave",()=>{
        infobox.classList.add('animate__fadeOutRight');
        //document.getElementById("info").style.display="none"
    })
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
    document.getElementById("account").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })
</script>
</html>


