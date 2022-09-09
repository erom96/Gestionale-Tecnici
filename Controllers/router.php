<?php

require ($_SERVER['DOCUMENT_ROOT'].'/database/database.php');
require ($_SERVER['DOCUMENT_ROOT'].'/helpers/functions.php');
require ($_SERVER['DOCUMENT_ROOT'].'/Models/utenteAdmin.php');
require ($_SERVER['DOCUMENT_ROOT'].'/Models/utenteTecnico.php');
require ($_SERVER['DOCUMENT_ROOT'].'/Models/intervento.php');
require ($_SERVER['DOCUMENT_ROOT'].'/Models/gestoreMail.php');
class router {
    public function process () {
        global $pdo;
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim(str_replace("Controllers/router.php", "", $url), '/');
        $tokens = explode('/', $url);
        switch ($tokens[0]) {
            case 'view':
                switch ($tokens[1]) {
                    case 'loginAdmin':
                        switch ($tokens[2]) {
                            case 'verifyLogin':
                                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    verifyLoginA($pdo);
                                }
                                break;
                        }
                        break;
                    case 'loginTecnico' :
                        switch ($tokens[2]) {
                            case 'verifyLogin':
                                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    verifyLoginB($pdo);
                                }
                                break;
                        }
                        break;
                }
                break;
            case 'logout':
                logout($pdo);
                break;
            case 'creaUtente':
                switch ($tokens[1]) {
                    case 'admin':
                        $utenteAdmin = new utenteAdmin();
                        $utenteAdmin->creaAdmin($pdo);
                        break;
                    case 'tecnico':
                        $utenteTecnico = new utenteTecnico();
                        $utenteTecnico->creaTecnico($pdo);
                        break;
                }
                break;
            case 'creaIntervento':
                $intervento = new intervento();
                $intervento->creaIntervento($pdo);
                break;
            case 'eliminaIntervento':
                $intervento = new intervento();
                $intervento->eliminaIntervento($pdo, $tokens[1]);
                break;
            case 'aggiornaIntervento':
                $intervento = new intervento();
                $intervento->aggiornaIntervento($pdo, $tokens[1]);
                break;
            case 'eliminaAdmin':
                $utenteAdmin = new utenteAdmin();
                $utenteAdmin->eliminaAdmin($pdo, $tokens[1]);
                break;
            case 'aggiornaAdmin':
                $utenteAdmin = new utenteAdmin();
                $utenteAdmin->aggiornaAdmin($pdo, $tokens[1]);
                break;
            case 'eliminaTecnico':
                $utenteTecnico = new utenteTecnico();
                $utenteTecnico->eliminaTecnico($pdo, $tokens[1]);
                break;
            case 'aggiornaTecnico':
                $utenteTecnico = new utenteTecnico();
                $utenteTecnico->aggiornaTecnico($pdo, $tokens[1]);
                break;
        }
    }
}

$router = new router();
$router->process();
