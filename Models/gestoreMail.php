<?php

class gestoreMail {
    public function mailInterventoAssegnato ($pdo, $nome, $idTecnico) {
        require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/settingsMailer.php');
        $tecnico = new utenteTecnico();
        $dati = $tecnico->trovaTecnico($pdo, $idTecnico);
        $email = $dati[0]->email;
        $mail->addAddress("$email");
        $mail->Subject = "Nuovo intervento assegnato!";
        $bodyContent = "<h1>Ti è stato assegnato l'intervento '$nome'</h1>"; 
        $mail->Body = $bodyContent;
        $mail->send();
    }
    
    public function mailPrendiIntervento ($pdo, $idIntervento) {
        require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/settingsMailer.php');
        $intervento = new intervento();
        $dati = $intervento->trovaIntervento($pdo, $idIntervento);
        $titolo = $dati[0]->nome;
        $admin = new utenteAdmin();
        $tecnico = new utenteTecnico();
        $datiTecnico = $tecnico->trovaTecnico($pdo, $dati[0]->idTecnico);
        $nome = $datiTecnico[0]->nome;
        $cognome = $datiTecnico[0]->cognome;
        $datiAdmin = $admin->trovaAdmin($pdo, $dati[0]->creatoDa);
        $email = $datiAdmin[0]->email;
        $mail->addAddress("$email");
        $mail->Subject = "Intervento preso in carico da un tecnico!";
        $bodyContent = "<h1>L'intervento '$titolo' è stato preso in carica da $nome $cognome!</h1>"; 
        $mail->Body = $bodyContent;
        $mail->send();
    }
    
    public function mailTerminaIntervento ($pdo, $idIntervento) {
        $intervento = new intervento();
        $dati = $intervento->trovaIntervento($pdo, $idIntervento);
        $titolo = $dati[0]->nome;
        $admin = new utenteAdmin();
        $tecnico = new utenteTecnico();
        $datiTecnico = $tecnico->trovaTecnico($pdo, $dati[0]->idTecnico);
        $datiAdmin = $admin->trovaAdmin($pdo, $dati[0]->creatoDa);
        require ($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/settingsMailer.php');
        session_start();
        if($_SESSION['utente']) {
            $nome = $datiAdmin[0]->nome;
            $cognome = $datiAdmin[0]->cognome;
            $email = $datiTecnico[0]->email;
            $mail->addAddress("$email");
            $mail->Subject = "Il tuo intervento e' stato chiuso da un admin!";
            $bodyContent = "<h1>L'intervento '$titolo' è stato chiuso dall'admin $nome $cognome!</h1>"; 
            $mail->Body = $bodyContent;
            $mail->send();
        }
        if($_SESSION['utenteB']) {
            $nome = $datiTecnico[0]->nome;
            $cognome = $datiTecnico[0]->cognome;
            $email = $datiAdmin[0]->email;
            $mail->addAddress("$email");
            $mail->Subject = "Intervento chiuso dal tecnico!";
            $bodyContent = "<h1>L'intervento '$titolo' è stato chiuso dal tecnico $nome $cognome!</h1>"; 
            $mail->Body = $bodyContent;
            $mail->send();
        }
    }
}
