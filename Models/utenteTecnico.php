<?php

class utenteTecnico {
    public function creaTecnico($pdo) {
        // Definisco le variabli e le inizializzo a 0
        $email = $password = $confirmPassword = $nome = $cognome = "";
        $livello = "basso";
        $email_err = $password_err = $confirmPassword_err = $nome_err = $cognome_err = "";

        // Processo il form quando viene inviato
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            // Valido email
            if(empty(trim($_POST["email"]))){
                $email_err = "Inserisci una email.";
            } else{
                
                // Preparo lo statement della SELECT
                $sql = "SELECT id FROM utenteTecnico WHERE email = :email";
                if($stmt = $pdo->prepare($sql)){
                    
                    // Bindo le variabili per lo statement della SELECT
                    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

                    // Li setto...
                    $param_email = trim($_POST["email"]);

                    // Provo ad eseguirlo...
                    if($stmt->execute()){
                        if($stmt->rowCount() == 1){
                            $email_err = "Account con inidirizzo email già esistente.";
                        } else{
                            $email = trim($_POST["email"]);
                        }
                    } else {
                        echo "Oops! Qualcosa è andato storto, riprova.";
                    }

                    // Chiudo
                    unset($stmt);
                }
            }

            // Valido password
            if(empty(trim($_POST["password"]))){
                $password_err = "Inserisci una password.";     
            } elseif(strlen(trim($_POST["password"])) < 5){
                $password_err = "La password dev'essere di almeno 5 caratteri.";
            } else{
                $password = trim($_POST["password"]);
            }

            // Valido confirmPassword
            if(empty(trim($_POST["confirmPassword"]))){
                $confirmPassword_err = "Conferma la password.";     
            } else {
                $confirmPassword = trim($_POST["confirmPassword"]);
                if(empty($password_err) && ($password != $confirmPassword)){
                    $confirmPassword_err = "Le password non combaciano.";
                }
            }

            // Valido nome
            if(empty(trim($_POST["nome"]))){
                $nome_err = "Inserisci un nome.";     
            } else {
                $nome = trim($_POST["nome"]);
            }

             // Valido cognome
            if(empty(trim($_POST["cognome"]))){
                $cognome_err = "Inserisci un cognome.";     
            } else {
                $cognome = trim($_POST["cognome"]);
            }

            // Valido livello
            $livello = trim($_POST["livello"]);

            // Controllo se ci sono errori prima di inserire nel database
            if(empty($email_err) && empty($password_err) && empty($confirmPassword_err) && empty($nome_err) && empty($cognome_err)){

                // Preparo lo statement
                $sql = "INSERT INTO utenteTecnico (email, password, nome, cognome, livello) VALUES (:email, :password, :nome, :cognome, :livello)";

                if($stmt = $pdo->prepare($sql)){
                    
                    // Bindo le variabili per lo statement
                    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                    $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                    $stmt->bindParam(":cognome", $cognome, PDO::PARAM_STR);
                    $stmt->bindParam(":livello", $livello, PDO::PARAM_STR);

                    // Li setto...
                    $param_email = $email;
                    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creo la password_hash

                    // Provo ad eseguirlo...
                    if($stmt->execute()){
                        
                        // Redirecto all'index
                        header("location: /index.php");
                    } else {
                        echo "Oops! Qualcosa è andato storto, riprova.";
                    }

                    // Chiudo
                    unset($stmt);
                }
            }

            if($email_err || $password_err || $confirmPassword_err || $cognome_err || $nome_err) {
                session_start();
                require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
                echo "<br> &nbsp; &nbsp; $email_err <br> &nbsp; &nbsp;";
                echo "$password_err <br> &nbsp; &nbsp;";
                echo "$confirmPassword_err <br> &nbsp; &nbsp;";
                echo "$cognome_err <br> &nbsp; &nbsp;";
                echo $nome_err;
                ?> <br> <br> &nbsp; &nbsp;
                <a href="/view/creaUtenteB.php"> 
                    <button type="button" class="btn btn-primary distance">Indietro</button>
                </a>
                <?php
                require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
            }

            // Chiudo la connessione
            unset($pdo);
        }
    }
    
    public function listaTecnici ($pdo) {        
        $result = [];
        $stm = $pdo->query("select id, nome, cognome, livello, ultimoAccesso from utenteTecnico");
        
        if($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);   
        }
       
        return $result;
    }
    
    public function prendiIntervento ($pdo, $idTecnico, $idIntervento) {
        $time = time();
        $stm = $pdo->query("UPDATE interventoTecnico SET stato = 'in corso', dataInizio = $time, idTecnico = $idTecnico WHERE id = $idIntervento ");
        if($stm->execute()) {
            $success = 1;
            $gestoreMail = new gestoreMail();
            $gestoreMail->mailPrendiIntervento($pdo, $idIntervento);
        } else {
            $success = 0;
        }
        return $success;
    }
    
    public function eliminaTecnico ($pdo, $id) {
        $sql = "DELETE FROM utenteTecnico WHERE id = $id";
        $pdo->query($sql);
        header("location: /view/gestioneUtenti.php");
        exit;
    }
    
    public function trovaTecnico ($pdo, $id) {
        $result = [];
        $stm = $pdo->query("select id, nome, cognome, livello, email from utenteTecnico WHERE id = $id");
        
        if($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);   
        }
       
        return $result;
    }
    
    public function aggiornaTecnico ($pdo, $id) {
        // Definisco le variabli e le inizializzo a 0
        $password_err = $confirmPassword_err = $nome_err = $cognome_err = "";

        // Processo il form quando viene inviato
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            // Valido email
            $param_email = trim($_POST["email"]);

            // Valido password
            if(strlen(trim($_POST["password"])) < 5 && !empty(trim($_POST["password"]))){
                $password_err = "La password dev'essere di almeno 5 caratteri.";
            } else {
                $password = trim($_POST["password"]);
            }

            // Valido confirmPassword
            if(strlen(trim($_POST["confirmPassword"])) >= 5 && !empty(trim($_POST["confirmPassword"]))){
                if($_POST["password"] != $_POST["confirmPassword"]){
                    $confirmPassword_err = "Le password non combaciano.";
                } else {
                    $confirmPassword = trim($_POST["confirmPassword"]);
                }   
            }

            // Valido nome
            if(empty(trim($_POST["nome"]))){
                $nome_err = "Inserisci un nome.";     
            } else {
                $nome = trim($_POST["nome"]);
            }

            // Valido cognome
            if(empty(trim($_POST["cognome"]))){
                $cognome_err = "Inserisci un cognome.";     
            } else {
                $cognome = trim($_POST["cognome"]);
            }
            
            // Valido livello
            $livello = trim($_POST["livello"]);

            // Controllo se ci sono errori prima di inserire nel database
            if(empty($password_err) && empty($confirmPassword_err) && empty($nome_err) && empty($cognome_err)){

                // Preparo lo statement e lo statement 2 per l'aggiornamento della password
                $sql = "UPDATE utenteTecnico SET nome = :nome, cognome = :cognome, livello = :livello WHERE email = '$param_email' ";
                if (!empty($password)) {
                    $sql2 = "UPDATE utenteTecnico SET password = :password WHERE email = '$param_email' ";
                }

                if($stmt = $pdo->prepare($sql)){
                    // Bindo le variabili per lo statement
                    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                    $stmt->bindParam(":cognome", $cognome, PDO::PARAM_STR);
                    $stmt->bindParam(":livello", $livello, PDO::PARAM_STR);

                    if($sql2) {
                        
                        // Bindo le variabili per lo statement 2, e lo eseguo
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->bindParam(":password", $param_password, PDO::PARAM_STR);
                        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creo la password_hash
                        $stmt2->execute();
                    }

                    // Provo ad eseguire lo statement...
                    if($stmt->execute()){
                        // Redirecto all'index
                        header("location: /view/gestioneUtenti.php");
                    } else {
                        echo "Oops! Qualcosa è andato storto, riprova.";
                    }

                    // Chiudo
                    unset($stmt);
                    if($stmt2) {
                        unset($stmt2);
                    }
                }
            }

            if($password_err || $confirmPassword_err || $cognome_err || $nome_err) {
                session_start();
                require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
                echo "<br> &nbsp; &nbsp; $password_err <br> &nbsp; &nbsp;";
                echo "$confirmPassword_err <br> &nbsp; &nbsp;";
                echo "$cognome_err <br> &nbsp; &nbsp;";
                echo $nome_err;
                ?> <br> <br> &nbsp; &nbsp;
                <a href="/view/aggiornaUtenteB.php/<?=$id?>"> 
                    <button type="button" class="btn btn-primary distance">Indietro</button>
                </a>
                <?php
                require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
            }

            // Chiudo la connessione
            unset($pdo);
        }
    }
}
