<?php

require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');

function verifyLoginA ($pdo) {
    // Iniziallizzo la sessione
    session_start();

    // Controllo se l'utente è già loggato. Se sì lo redirecto
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["utente"]){
        header("location: /view/dashboardA.php");
        exit;
    }

    // Definisco le variabli e le inizializzo a 0
    $email = $password = "";
    $email_err = $password_err = $login_err = "";

    // Processo il form quando viene inviato
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Valido email
        if(empty(trim($_POST["email"]))){
            $email_err = "Inserisci una email.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Valido password
        if(empty(trim($_POST["password"]))){
            $password_err = "Inserisci una password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Valido le credenziali
        if(empty($email_err) && empty($password_err)){
            
            // Preparo lo statement
            $sql = "SELECT id, email, password, nome, cognome FROM utenteAdmin WHERE email = :email";

            if($stmt = $pdo->prepare($sql)){
                
                // Bindo le variabili per lo statement
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

                // Li setto...
                $param_email = trim($_POST["email"]);

                // Provo ad eseguirlo...
                if($stmt->execute()){
                    
                    // Controllo se esiste l'email, se sì controllo la password
                    if($stmt->rowCount() == 1){
                        if($row = $stmt->fetch()){
                            $nome = $row["nome"];
                            $cognome = $row["cognome"];
                            $id = $row["id"];
                            $email = $row["email"];
                            $hashed_password = $row["password"];
                            if(password_verify($password, $hashed_password)){
                                
                                // La password è giusta, apro la sessione
                                session_start();

                                // Riempio $_SESSION
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;
                                $_SESSION["utente"] = "$nome $cognome";

                                // Redirecto all'index
                                header("location: /view/dashboardA.php");
                            } else{
                                
                                // Password non valida, messaggio di errore
                                $login_err = "email o password non valide.";
                            }
                        }
                    } else {
                        
                        // email non esiste, messaggio di errore
                        $login_err = "email o password non valide.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Chiudo
                unset($stmt);
            }
        }

        if($email_err || $password_err || $login_err) {
            require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
            echo "<br> &nbsp; &nbsp; $email_err <br> &nbsp; &nbsp;";
            echo $password_err;
            echo $login_err;
            ?> <br> <br> &nbsp; &nbsp;
            <a href="/view/loginAdmin.php">  
                <button type="button" class="btn btn-primary distance">Indietro</button>
            </a>
            <?php
            require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
        }

        // Chiudo la connessione
        unset($pdo);
    } 
}


function verifyLoginB ($pdo) {
    // Iniziallizzo la sessione
    session_start();
 
    // Controllo se l'utente è già loggato. Se sì lo redirecto
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["utenteB"]){
        header("location: /view/dashboardA.php");
        exit;
    }

    // Definisco le variabli e le inizializzo a 0
    $email = $password = "";
    $email_err = $password_err = $login_err = "";

    // Processo il form quando viene inviato
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Valido email
        if(empty(trim($_POST["email"]))){
            $email_err = "Inserisci una email.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Valido password
        if(empty(trim($_POST["password"]))){
            $password_err = "Inserisci una password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate le credenziali
        if(empty($email_err) && empty($password_err)){
            
            // Preparo lo statement
            $sql = "SELECT id, email, password, nome, cognome FROM utenteTecnico WHERE email = :email";

            if($stmt = $pdo->prepare($sql)){
                
                // Bindo le variabili per lo statement
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

                // Li setto...
                $param_email = trim($_POST["email"]);

                // Provo ad eseguirlo...
                if($stmt->execute()){
                    
                    // Controllo se esiste l'email, se sì controllo la password
                    if($stmt->rowCount() == 1){
                        if($row = $stmt->fetch()){
                            $nome = $row["nome"];
                            $cognome = $row["cognome"];
                            $id = $row["id"];
                            $email = $row["email"];
                            $hashed_password = $row["password"];
                            if(password_verify($password, $hashed_password)){
                                
                                // La password è giusta, apro la sessione
                                session_start();

                                // Riempio $_SESSION
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;
                                $_SESSION["utenteB"] = "$nome $cognome";

                                // Setto l'ultimo accesso
                                $sql = "UPDATE utenteTecnico SET ultimoAccesso='" .time() ."' WHERE id=$id";
                                $stmt = $pdo->query($sql);

                                // Redirecto all'index
                                header("location: /view/dashboardB.php");
                            } else {
                                
                                // Password non valida, messaggio di errore
                                $login_err = "email o password non valide.";
                            }
                        }
                    } else {
                        // email non esiste, messaggio di errore
                        $login_err = "email o password non valide.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Chiudo
                unset($stmt);
            }
        }

        if($email_err || $password_err || $login_err) {
            require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
            echo "<br> &nbsp; &nbsp; $email_err <br> &nbsp; &nbsp;";
            echo $password_err;
            echo $login_err;
            ?> <br> <br> &nbsp; &nbsp;
            <a href="/view/loginTecnico.php">
                <button type="button" class="btn btn-primary distance">Indietro</button>
            </a>
            <?php
            require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
        }

        // Chiudo la connessione
        unset($pdo);
    } 
}

function logout($pdo) {  
    // Iniziallizzo la sessione
    session_start();

    // Unsetto tutte le variabili $_SESSION
    $_SESSION = array();

    // Distruggo la sessione
    session_destroy();

    // Redirecto all'index
    header("location: /index.php");
    exit;
}
