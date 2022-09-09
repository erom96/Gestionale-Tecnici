<?php

class commento {
    public function creaCommento($pdo, $idIntervento) {
        // Definisco le variabli e le inizializzo a 0
        session_start();
        $descrizione = $importanza = "";
        $id = $_SESSION['id'];
        $dataCreazione = time();
        $descrizione_err = "";
        
        // Processo il form quando viene inviato
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // Valido descrizione
            if (empty(trim($_POST["messaggio"]))) {
                $descrizione_err = "Inserisci un messaggio.";
            } else {
                $descrizione = ($_POST["messaggio"]);
            }
            
            // Valido id
            if($_SESSION['utente']) {
                $idAdmin = $id;
            }
            if($_SESSION['utenteB']) {
                $idTecnico = $id;
            }
            
            // Valido importanza
            $importanza = $_POST["importanza"];    
            
            // Controllo se ci sono errori prima di inserire nel database
            if (empty($descrizione_err)) {

                // Preparo lo statement
                $sql = "INSERT INTO noteIntervento (descrizione, importanza, dataCreazione";
                if ($idTecnico) {
                    $sql .= ", idTecnico";
                }
                if ($idAdmin) {
                    $sql .=", idAdmin";
                }
                $sql .= ", idIntervento) VALUES (:descrizione, :importanza, :dataCreazione,";
                if ($idTecnico) {
                    $sql .= " :idTecnico, ";
                }
                if ($idAdmin) {
                    $sql .=" :idAdmin, ";
                }
                $sql .= " :idIntervento)";

                if ($stmt = $pdo->prepare($sql)) {
                    
                    // Bindo le variabili per lo statement
                    $stmt->bindParam(":descrizione", $descrizione, PDO::PARAM_STR);
                    $stmt->bindParam(":importanza", $importanza, PDO::PARAM_STR);
                    $stmt->bindParam(":idIntervento", $idIntervento, PDO::PARAM_STR);
                    if ($idTecnico) {
                        $stmt->bindParam(":idTecnico", $idTecnico, PDO::PARAM_STR);
                    }
                    if ($idAdmin) {
                        $stmt->bindParam(":idAdmin", $idAdmin, PDO::PARAM_STR);
                    }
                    $stmt->bindParam(":dataCreazione", $dataCreazione, PDO::PARAM_STR);

                    // Provo ad eseguirlo...
                    $stmt->execute();
                    $last_id = $pdo->lastInsertId();

                    // Chiudo
                    unset($stmt);
                }
            }            
        }    
        return $last_id;
    }
    
    public function trovaInterventoCorrelato($pdo, $idIntervento) {
        $result = [];
        $stm = $pdo->query("SELECT id, descrizione, dataCreazione, importanza, idAdmin, idTecnico FROM noteIntervento WHERE idIntervento = $idIntervento ORDER BY dataCreazione DESC");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }
    
    public function importanzaCommento($importanza) {
        switch($importanza) {
            case 'bassa':
                $colore = "color: green;";
                break;
            case 'media':
                $colore = "color: #FFBF00;";
                break;
            case 'alta':
                $colore = "color: orange;";
                break;
            case 'critica':
                $colore = "color: red;";
                break;
        }
        return $colore;
    }
    
    public function datiCommento($pdo, $id) {
        $result = [];
        $stm = $pdo->query("SELECT id, descrizione, dataCreazione, importanza, idAdmin, idTecnico FROM noteIntervento WHERE id = $id");

        if ($stm) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        return $result;
    }
}
