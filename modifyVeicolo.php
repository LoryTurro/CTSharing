<?php

session_start();

require "action.php";

if (!isset($_SESSION["logged"])) {
    header("location: login.php");
    exit();
}
if (isset($_REQUEST['id']))
    $veicolo=getVeicoloById($_REQUEST['id']);
else
    header("location: index.php");

$id_agenzia_veicolo=$veicolo['id_agenzia'];

if ($id_agenzia_veicolo != $_SESSION['id_agenzia'])
    header("location: index.php");

$modello = $veicolo['modello']; $tipo = $veicolo['tipo']; $alimentazione = $veicolo['alimentazione']; $targa = $veicolo['targa']; $prezzo_ora = $veicolo['prezzo_ora'];

if (isset($_REQUEST['conferma'])){
    $modello=$_REQUEST['modello'];
    $tipo=$_REQUEST['tipo'];
    $alimentazione=$_REQUEST['alimentazione'];
    $targa=$_REQUEST['targa'];
    $prezzo_ora=$_REQUEST['prezzo_ora'];
    if(empty(trim($targa)))
        $targa=NULL;

    if(empty($modello))
        $php_errormsg = "modello assente";
    elseif(empty($tipo))
        $php_errormsg = "tipo assente";
    elseif(empty($alimentazione))
        $php_errormsg = "alimentazione assente";
    elseif(empty($prezzo_ora))
        $php_errormsg = "prezzo assente";

    if(!isset($php_errormsg)) {
        if (modifyVeicolo($modello, $tipo, $alimentazione, $targa, $prezzo_ora, $_REQUEST['id'])) {
            sleep(1);
            header("location: indexSocio.php");
            //header("location: login.php?s");
        } else
            echo "<div class='error-msg' style='color: #D8000C; background-color: #FFBABA;margin: 0 1px;padding: 10px;border-radius: 3px 3px 3px 3px;'>
                  <i class='fa fa-times-circle'></i>Targa gia registrata.</div>";
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
<body onload="verificaVariabileGet()">

<div class="wrapper animate__animated animate__fadeInDownBig">
    <div class="form-left">
        <img src="img/logo.png">
        <p style="margin-top: 15px">
            In questa pagina puoi modificare il veicolo selezionato per poter sempre essere al passo con i tempi. Aggiorna questi valori quando vuoi e continua ad usufruire dei nostri servizi
        </p>
        <div class="form-field" style="margin-top: 20px; position: center">
            <input type="submit" id="index" name="index" class="account" value="Indietro" onclick="window.location='index.php';">
        </div>
    </div>
    <form class="form-right" name="form" method="post">
        <h2 class="text-uppercase">Modifica Veicolo</h2>
        <div id="verifica">
            <div class="mb-3">
                <label>Modello</label>
                <input type="text" name="modello" id="modello" class="input-field" value="<?php echo $modello?>" required>
            </div>
            <div class="mb-3">

                <label>Tipo</label>
                <select name="tipo" id="tipo" class="input-field" required>
                    <option value="">Seleziona una opzione</option>
                    <option value="auto" <?php echo $tipo=="auto"?"selected":""?>>Auto</option>
                    <option value="moto" <?php echo $tipo=="moto"?"selected":""?>>Moto</option>
                    <option value="bicicletta"<?php echo $tipo=="bicicletta"?"selected":""?>>Bicicletta</option>
                    <option value="monopattino"<?php echo $tipo=="monopattino"?"selected":""?>>Monopattino</option>
                    <option value="altro"<?php echo $tipo=="altro"?"selected":""?>>Altro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="alimentazione">Alimentazione</label>
                <select class="input-field" name="alimentazione" id="alimentazione" required>
                    <option value="">Seleziona una opzione</option>
                    <option value="elettrica" <?php echo $alimentazione=="elettrica"?"selected":""?>>Elettrica</option>
                    <option value="benzina" <?php echo $alimentazione=="benzina"?"selected":""?>>Benzina</option>
                    <option value="diesel"<?php echo $alimentazione=="diesel"?"selected":""?>>Diesel</option>
                    <option value="GPL"<?php echo $alimentazione=="GPL"?"selected":""?>>GPL</option>
                    <option value="metano"<?php echo $alimentazione=="metano"?"selected":""?>>Metano</option>
                    <option value="ibrida"<?php echo $alimentazione=="ibrida"?"selected":""?>>Ibrida</option>
                    <option value="idrogeno"<?php echo $alimentazione=="idrogeno"?"selected":""?>>Idrogeno</option>
                    <option value="gambe"<?php echo $alimentazione=="gambe"?"selected":""?>>Gambe</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Prezzo all'ora</label>
                <input type="number" class="input-field" name="prezzo_ora" id="prezzo_ora" value="<?php echo $prezzo_ora ?>">
            </div>
        </div>

        <div class="mb-3">
            <label>Posizione ????</label>
            <input type="text" class="input-field" name="posizione" id="posizione" value="<?php echo "wip"?>">
        </div>

        <div class="mb-3">
            <label>Targa</label>
            <input type="text" class="input-field" name="targa" id="targa" value="<?php echo $targa?>">
        </div>

        <div class="form-field" style="display: flex">
            <input type="submit" style="margin-right: 44px" value="Conferma" class="register" name="conferma" id="conferma">
        </div>
        <div class="loader" id="loader" style="display: none"></div>
    </form>
</div>
</body>
<script>
    document.getElementById("conferma").addEventListener("click",()=>{
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
    function verificaVariabileGet() {
        // Estrai i parametri GET dall'URL corrente
        var queryString = window.location.search;

        // Verifica se la variabile "id" è presente nell'URL
        if (queryString.indexOf("id") == -1) {
            // Reindirizza alla pagina principale se la variabile "id" non è presente
            window.location.href = "index.php";
        }
    }
</script>
</html>