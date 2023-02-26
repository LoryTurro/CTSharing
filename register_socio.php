<?php

session_start();

require "action.php";

if (!isset($_SESSION["logged"])) {
    header("location: login.php");
    exit();
}
if (first_register_socio($_SESSION['username'])!="registrazione") {
    header("location: index.php");
    exit();
}

if (isset($_REQUEST['registrati'])) {
    $username = $_SESSION['username'];
    $nome = $_REQUEST['nome'];
    $email = $_REQUEST['email'];
    $nTel = $_REQUEST['nTel'];
    $indirizzo = $_REQUEST['indirizzo'];
    $pIva = $_REQUEST['pIva'];
    $referente = $_REQUEST['referente'];

    if(empty($nome))
        $php_errormsg = "Nome assente";
    elseif(empty($email))
        $php_errormsg = "Email assente";
    elseif(empty($nTel))
        $php_errormsg = "Numero di telefono assente";
    elseif(empty($indirizzo))
        $php_errormsg = "indirizzo assente";
    elseif(empty($pIva))
        $php_errormsg = "Partita IVA assente";
    elseif(empty($referente))
        $php_errormsg = "Referente assente";

    if(!isset($php_errormsg)){
        if (register_socio($username, $nome, $email, $nTel, $indirizzo, $pIva, $referente)){
            sleep(1);
            header("location: index.php");
        }
    }
    else {
        echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                  <i class='fa fa-times-circle'></i>" . $php_errormsg . "</div>";
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
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <title>CT Sharing</title>

    <style>

    </style>

</head>
<body>
<div id="error-msg" name="error-msg" class='error-msg' style='display:none; color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
    <i class='fa fa-times-circle'></i>Numero di telefono non valido.</div>
<div class="wrapper animate__animated animate__fadeInDownBig" >
    <div class="form-left">
        <img src="img/logo2.png">
        <p style="margin-top: 15px">
            Benvenuto nella pagina di registrazione della tua agenzia nel nostro sistema di prenotazione di veicoli! Qui potrai registrare la tua agenzia e accedere alle nostre funzionalità di messa a disposizione di auto, scooter, moto, biciclette, monopattini e molto altro. Inserisci i dati relativi all'agenzia per iniziare ad utilizare i nostri servizi.
        </p>
        <div class="form-field animate__animated animate__headShake animate__delay-1s" style="margin-top: 20px; position: center">
            <input type="submit" id="account" name="account" class="account" value="Non hai un account?" onclick="window.location='login.php';">
        </div>
    </div>
    <form class="form-right" name="form" method="post" onsubmit="process(event)">
        <h2 class="text-uppercase">Registrazione socio</h2>
        <div id="verifica">
            <div class="mb-3">
                <label>Nome Agenzia</label>
                <input type="text" name="nome" id="nome" class="input-field" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="input-field" name="email" id="email" required>
            </div>
            <div class="mb-3 input-box" id="phoneInput">
                <label>Numero Telefono</label>
                <input style="
                        width: 324px;
                        height: 43px;
                        border: 1px solid #e5e5e5;
                        border-top-left-radius: 5px;
                        border-bottom-right-radius: 5px;
                        outline: none;
                        color: #333;"
                        type="tel" name="nTel" id="nTel" min="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
            </div>
            <div class="mb-3">
                <label>Indirizzo Agenzia</label>
                <input type="text" name="indirizzo" id="indirizzo" class="input-field" required>
            </div>
            <div class="mb-3">
                <label>Partita IVA</label>
                <input type="number" name="pIva" id="pIva" class="input-field" maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
            </div>
            <div class="mb-3">
                <label>Referente</label>
                <input type="text" class="input-field" name="referente" id="referente" required>
            </div>
        </div>

        <div class="form-field" style="display: flex; margin-top: 15px">
            <input type="submit" value="Regista Socio" class="register" name="registrati" id="registrati">
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
    document.getElementById("account").addEventListener("click",()=>{
        document.querySelector('.wrapper').classList.add("animate__fadeOutUpBig");
    })

    var input = document.querySelector("#phoneInput input");
    var iti = window.intlTelInput(input, {
        preferredCountries: ["it"],
        onlyCountries: ["al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk",
            "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv",
            "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro",
            "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb"],
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });
    //verifica validita nTel
    var form = document.querySelector("form");
    var errorMessage = document.querySelector("#error-msg");
    form.addEventListener("submit", function(event) {
        var phoneNumber = iti.getNumber();
        if (!iti.isValidNumber()) {
            scrollToTop();
            event.preventDefault();
            errorMessage.style.display = "block";

            document.getElementById("loader").style.display="none";
        } else {
            errorMessage.style.display = "none";
            document.querySelector("#nTel").value = phoneNumber;
        }
    });

    // Impostazione manuale bordo dell'intesimento con id nTel
    const nTelInput = document.getElementById('nTel');

    // Aggiungi il bordo verde quando l'input viene selezionato
    nTelInput.addEventListener('focus', function() {
        this.style.border = '1px solid #31a031';
    });

    // Rimuovi il bordo verde quando si seleziona un altro elemento
    document.addEventListener('click', function(event) {
        if (event.target !== nTelInput) {
            nTelInput.style.border = '1px solid #e5e5e5';
        }
    });
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }



</script>
</html>
