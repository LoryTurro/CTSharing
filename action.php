<?php
#QUERY

function register($nome,$cognome,$username,$email,$pwd,$nPatente,$gruppo) {

    require 'DBC.php';

    $sql = "INSERT INTO utente (nome, cognome, username, email, password, nPatente, gruppo) VALUES (:nome, :cognome, :username, :email, :password, :nPatente, :gruppo)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cognome', $cognome);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $pwd);
    $stmt->bindParam(':nPatente', $nPatente);
    $stmt->bindParam(':gruppo', $gruppo);
    try {
        $stmt->execute();
    } catch(PDOException $e) {
        return false;
    }
    return true;
}

function login($username, $pwd) {

    require 'DBC.php';

    $sql = "SELECT username, password FROM utente WHERE username=:username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    try {
        $stmt->execute();
    } catch(PDOException $e) {
        return false;
    }
    $result = $stmt->fetch();

    if (isset($result['username'])) {
        if (password_verify($pwd, $result['password']))
            return "accettato";
        else
            return "password";
    }
    else{
        return "utente"; //utente non esiste
    }


}

function get_user_id($username){
    require 'DBC.php';

    $sql = "SELECT id FROM utente WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    try {
        $stmt->execute();
    } catch(PDOException $e) {
        return false;
    }

    $id_utente = $stmt->fetch();

    return $id_utente[0];
}

function register_socio({})

?>
