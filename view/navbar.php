<?php
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = trim(str_replace("view/", "", $url), '/');
$tokens = explode('/', $url);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestionale</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php if($tokens[0] === 'dashboardA.php' || $tokens[0] === 'dashboardB.php' || $tokens[0] === 'index.php' || $tokens[0] === 'loginTecnico.php' || $tokens[0] === 'loginAdmin.php' || $tokens[0] === 'aggiornaIntervento.php') { echo 'active'; }?>" aria-current="page" href="/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <?php if($_SESSION["utente"]) { ?>
                    <a class="nav-link  <?php if($tokens[0] === 'creaIntervento.php') { echo 'active'; }?>" href="/view/creaIntervento.php">Crea intervento</a>
                </li> <?php } ?>
                <li class="nav-item">
                    <?php if($_SESSION["utente"]) { ?>
                    <a class="nav-link <?php if($tokens[0] === 'creaUtente.php' || $tokens[0] === 'creaUtenteA.php' || $tokens[0] === 'creaUtenteB.php') { echo 'active'; }?>" href="/view/creaUtente.php">Crea utente</a>
                </li> <?php } ?>
                <li class="nav-item">
                    <?php if($_SESSION["utente"]) { ?>
                  <a class="nav-link <?php if($tokens[0] === 'gestioneUtenti.php' || $tokens[0] === 'aggiornaUtenteA.php' || $tokens[0] === 'aggiornaUtenteB.php') { echo 'active'; }?>" href="/view/gestioneUtenti.php">Gestione utenti</a>
                </li> <?php } ?>
            </ul>
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link">
                        <div id="wtime">
                            <span id="time"></span>    
                        </div>
                    </a>
                </li>
            </ul>
            <?php if($_SESSION["utente"]) { ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link">Benvenuto, <?=$_SESSION["utente"]?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Controllers/router.php/logout">Logout</a>
                    </li>
                </ul> <?php                 
                } ?>
            <?php if($_SESSION["utenteB"]) { ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Benvenuto, <?=$_SESSION["utenteB"]?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Controllers/router.php/logout">Logout</a>
                    </li>
                </ul> <?php
                } ?>
        </div>
    </div>
</nav>

<script src="/js/filter.js"></script>
