<?php

class intervento {

    public function creaIntervento($pdo) {
        // Definisco le variabli e le inizializzo a 0
        $nome = $priorita = $dataLimite = $idTecnico = $descrizione = $stato = $dataInizio = "";
        $creatoDa = $_POST["admin"];
        $dataCreazione = time();
        $nome_err = $dataLimite_err = $descrizione_err = "";

        // Processo il form quando viene inviato
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Valido nome
            if (empty(trim($_POST["nome"]))) {
                $nome_err = "Inserisci una titolo.";
            } else {
                $nome = ($_POST["nome"]);
            }

            // Valido priorita
            $priorita = $_POST["priorita"];

            // Valido dataLimite
            if (empty(($_POST["dataLimite"]))) {
                $dataLimite_err = "Inserisci una data di scadenza per l'intervento.";
            } else {
                $dataLimite = strtotime($_POST["dataLimite"]);
            }

            if ($dataLimite <= time() && $dataLimite) {
                $dataLimite_err = "La data inserita non è valida.";
            }

            // Valido idTecnico
            $idTecnico = $_POST["tecnico"];

            // Valido descrizione
            if (empty(trim($_POST["descrizione"]))) {
                $descrizione_err = "Inserisci una descrizione.";
            } else {
                $descrizione = ($_POST["descrizione"]);
            }

            // Valido stato

            if ($idTecnico == "") {
                $stato = "non iniziato";
            } else {
                $stato = "in corso";
            }

            // Valido dataInizio

            if ($idTecnico) {
                $dataInizio = time();
            }

            // Controllo se ci sono errori prima di inserire nel database
            if (empty($nome_err) && empty($dataLimite_err) && empty($descrizione_err)) {

                // Preparo lo statement
                $sql = "INSERT INTO interventoTecnico (nome, priorita, dataLimite";
                if ($idTecnico) {
                    $sql .= ", idTecnico, dataInizio";
                }
                $sql .= ", descrizione, stato, creatoDa, dataCreazione) VALUES (:nome, :priorita, :dataLimite,";
                if ($idTecnico) {
                    $sql .= " :idTecnico, :dataInizio, ";
                }
                $sql .= " :descrizione, :stato, :creatoDa, :dataCreazione)";

                if ($stmt = $pdo->prepare($sql)) {
                    
                    // Bindo le variabili per lo statement
                    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                    $stmt->bindParam(":priorita", $priorita, PDO::PARAM_STR);
                    $stmt->bindParam(":dataLimite", $dataLimite, PDO::PARAM_STR);
                    if ($idTecnico) {
                        $stmt->bindParam(":idTecnico", $idTecnico, PDO::PARAM_STR);
                        $stmt->bindParam(":dataInizio", $dataInizio, PDO::PARAM_STR);
                    }
                    $stmt->bindParam(":descrizione", $descrizione, PDO::PARAM_STR);
                    $stmt->bindParam(":stato", $stato, PDO::PARAM_STR);
                    $stmt->bindParam(":creatoDa", $creatoDa, PDO::PARAM_STR);
                    $stmt->bindParam(":dataCreazione", $dataCreazione, PDO::PARAM_STR);

                    // Provo ad eseguirlo...
                    if ($stmt->execute()) {
                        // Redirecto all'index
                        header("location: /index.php");
                        if ($idTecnico) {
                            $gestoreMail = new gestoreMail();
                            $gestoreMail->mailInterventoAssegnato($pdo, $nome, $idTecnico);
                        }
                    } else {
                        echo "Oops! Qualcosa è andato storto, riprova.";
                    }

                    // Chiudo
                    unset($stmt);
                }
            }

            if ($nome_err || $dataLimite_err || $descrizione_err) {
                session_start();
                require ($_SERVER['DOCUMENT_ROOT'] . '/view/navbar.php');
                echo "<br> &nbsp; &nbsp; $nome_err <br> &nbsp; &nbsp;";
                echo "$dataLimite_err <br> &nbsp; &nbsp;";
                echo "$descrizione_err";
                ?> <br> <br> &nbsp; &nbsp;
                <a href="/view/creaIntervento.php">
                    <button type="button" class="btn btn-primary distance">Indietro</button>
                </a>
                <?php
                require ($_SERVER['DOCUMENT_ROOT'] . '/view/footer.php');
            }

            // Chiudo la connessione
            unset($pdo);
        }
    }
    
    public function chiudiIntervento($pdo, $id) {
        $tempo = time();
        $stm = $pdo->query("UPDATE interventoTecnico SET stato = 'terminato', dataTermine = $tempo WHERE id = $id");
        if($stm->execute()) {
            session_start();
            $gestoreMail = new gestoreMail();
            $gestoreMail->mailTerminaIntervento($pdo, $id);  
        }
    }
    
    public function listaInterventiTecnici($pdo, $id) {
        $result = [];
        $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico WHERE idTecnico = $id AND stato = 'in corso'");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }
    
    public function listaInterventiDisponibili($pdo) {
        $result = [];
        $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico WHERE stato = 'non iniziato'");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function listaTuttiInterventi($pdo) {
        $result = [];
        $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function iMieiInterventi($pdo, $id) {
        $result = [];
        $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where creatoDa = $id");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function eliminaIntervento($pdo, $id) {
        $sql = "DELETE FROM interventoTecnico WHERE id = $id";
        $pdo->query($sql);
        header("location: /index.php");
        exit;
    }

    public function trovaIntervento($pdo, $id) {
        $result = [];
        $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione, dataTermine from interventoTecnico where id = $id");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function filtraIntervento($pdo, $stato) {
        $result = [];
        if ($stato == 1) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'in corso'");
        }
        if ($stato == 2) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'non iniziato'");
        }
        if ($stato == 3) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'terminato'");
        }
        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function filtraMieiInterventi($pdo, $stato, $id) {
        $result = [];
        if ($stato == 1) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'in corso' and creatoDa = $id");
        }
        if ($stato == 2) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'non iniziato' and creatoDa = $id");
        }
        if ($stato == 3) {
            $stm = $pdo->query("select id, dataCreazione, priorita, dataLimite, dataInizio, creatoDa, stato, idTecnico, nome, descrizione from interventoTecnico where stato = 'terminato' and creatoDa = $id");
        }
        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }

    public function aggiornaIntervento($pdo, $id) {
        // Definisco le variabli e le inizializzo a 0
        $nome_err = $descrizione_err = $dataLimite_err = "";

        // Processo il form quando viene inviato
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Valido nome
            if (empty(trim($_POST["nome"]))) {
                $nome_err = "Inserisci una titolo.";
            } else {
                $nome = ($_POST["nome"]);
            }

            // Valido priorita
            $priorita = $_POST["priorita"];

            // Valido dataLimite
            if (empty(($_POST["dataLimite"]))) {
                $dataLimite_err = "Inserisci una data di scadenza per l'intervento.";
            } else {
                $dataLimite = strtotime($_POST["dataLimite"]);
            }
            if ($dataLimite <= time() && $dataLimite) {
                $dataLimite_err = "La data inserita non è valida.";
            }

            // Valido idTecnico
            if ($_POST["tecnico"] == "") {
                $idTecnico = NULL;
            } else {
                $idTecnico = $_POST["tecnico"];
            }

            // Valido descrizione
            if (empty(trim($_POST["descrizione"]))) {
                $descrizione_err = "Inserisci una descrizione.";
            } else {
                $descrizione = ($_POST["descrizione"]);
            }

            // Valido stato
            if ($_POST["tecnico"] == "") {
                $stato = "non iniziato";
            } else {
                $stato = "in corso";
            }

            // Valido dataInizio
            if ($idTecnico) {
                $dataInizio = time();
            }

            // Prendo idIntervento
            $idIntervento = $_POST["idIntervento"];
            
            // Controllo se ci sono errori prima di inserire nel database
            if (empty($nome_err) && empty($descrizione_err) && empty($dataLimite_err)) {
                
                // Preparo lo statement
                $sql = "UPDATE interventoTecnico SET nome = :nome, descrizione = :descrizione, priorita = :priorita, dataLimite = :dataLimite, stato = :stato, idTecnico = :idTecnico";
                if ($idTecnico) {
                    $sql .= " ,dataInizio = :dataInizio";
                }
                $sql .= " WHERE id = '$idIntervento'";

                if ($stmt = $pdo->prepare($sql)) {
                    
                    // Bindo le variabili per lo statement
                    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                    $stmt->bindParam(":priorita", $priorita, PDO::PARAM_STR);
                    $stmt->bindParam(":dataLimite", $dataLimite, PDO::PARAM_STR);
                    if ($idTecnico) {
                        $stmt->bindParam(":dataInizio", $dataInizio, PDO::PARAM_STR);
                    }
                    $stmt->bindParam(":idTecnico", $idTecnico, PDO::PARAM_STR);
                    $stmt->bindParam(":stato", $stato, PDO::PARAM_STR);
                    $stmt->bindParam(":descrizione", $descrizione, PDO::PARAM_STR);

                    // Provo ad eseguirlo...
                    if ($stmt->execute()) {
                        
                        // Redirecto all'index
                        header("location: /index.php");
                    } else {
                        echo "Oops! Qualcosa è andato storto, riprova.";
                    }

                    // Chiudo
                    unset($stmt);
                }
            }

            if ($nome_err || $descrizione_err || $dataLimite_err) {
                session_start();
                require ($_SERVER['DOCUMENT_ROOT'] . '/view/navbar.php');
                echo "<br> &nbsp; &nbsp; $nome_err <br> &nbsp; &nbsp;";
                echo "$descrizione_err <br> &nbsp; &nbsp;";
                echo "$dataLimite_err";
                ?> <br> <br> &nbsp; &nbsp;
                <a href="/view/aggiornaIntervento.php/<?= $idIntervento ?>"> 
                    <button type="button" class="btn btn-primary distance">Indietro</button>
                </a>
                <?php
                require ($_SERVER['DOCUMENT_ROOT'] . '/view/footer.php');
            }

            // Chiudo la connessione
            unset($pdo);
        }
    }
}
