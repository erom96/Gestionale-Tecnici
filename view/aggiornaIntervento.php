<html>

    <head>
        <?php
        require ($_SERVER['DOCUMENT_ROOT'] . '/public/bootstrap.php');
        require ($_SERVER['DOCUMENT_ROOT'] . '/Controllers/router.php');
        ?>
    </head>

    <body> 
        <?php
        session_start();
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim(str_replace("view/aggiornaIntervento", "", $url), '/');
        $tokens = explode('/', $url);
        $intervento = new intervento();
        $dati = $intervento->trovaIntervento($pdo, $tokens[1]);
        require ($_SERVER['DOCUMENT_ROOT'] . '/view/navbar.php');
        if (!$_SESSION["utente"]) {
            header("location: /index.php");
            exit;
        }
        ?>
        
        <h2 class='text-center navbarfix'>Aggiorna Intervento <a href="">#<?= $dati[0]->id ?></a></h2>
        <form method="POST" action="/Controllers/router.php/aggiornaIntervento/<?= $dati[0]->id ?>"> 
            <input type='hidden' name='idIntervento' id='idIntervento' value="<?= $dati[0]->id ?>">
            <div class="container">
                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="nome" class="form-label">Titolo</label>
                        <input name="nome" type="text" class="form-control" id="nome" value="<?= $dati[0]->nome ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="priorita" class="form-label">Priorità</label>
                        <select name="priorita" class="form-select" id="priorita">
                            <option value="bassa" <?php if ($dati[0]->priorita == "bassa") {
                                echo "selected = 'selected'";
                                } ?>>Bassa
                            </option>
                            <option value="media" <?php if ($dati[0]->priorita == "media") {
                                echo "selected = 'selected'";
                                } ?>>Media
                            </option>    
                            <option value="alta" <?php if ($dati[0]->priorita == "alta") {
                                echo "selected = 'selected'";
                                } ?>>Alta
                            </option>
                            <option value="urgente" <?php if ($dati[0]->priorita == "urgente") {
                                echo "selected = 'selected'";
                                } ?>>Urgente
                            </option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="dataLimite" class="form-label">Da terminare entro il</label>
                        <?php
                        $dataLimite = date('Y-m-d', $dati[0]->dataLimite);
                        ?>
                        <input name="dataLimite" type="date" class="form-control" id="dataLimite" value="<?= $dataLimite ?>">
                    </div>
                    <?php
                    $utenteB = new utenteTecnico();
                    $tecnico = $utenteB->listaTecnici($pdo);
                    ?>
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="tecnico" class="form-label">Tecnico da assegnare</label>
                        <select name="tecnico" class="form-select" id="tecnico">
                            <option <?php if ($dati[0]->idTecnico) {
                                echo "selected = 'selected'";
                                } ?> value="">Non assegnare
                            </option>
                    <?php foreach ($tecnico as $tecnico) { ?>
                            <option value="<?= $tecnico->id ?>" <?php if ($dati[0]->idTecnico == "$tecnico->id") {
                                echo "selected = 'selected'";
                                } ?>><?= $tecnico->nome ?> <?= $tecnico->cognome ?> 
                            </option>
                    <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-5 col-md-7 text-center container">
                        <label for="descrizione" class="form-label">Descrizione</label>
                        <textarea name="descrizione" class="form-control" id="descrizione" rows="6"><?= $dati[0]->descrizione ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <button type="submit" class="btn btn-success">Aggiorna</button>
                    </div>
                </div>
            </div>
        </form>   
    </body>   

    <?php
    require ($_SERVER['DOCUMENT_ROOT'] . '/view/footer.php');
    ?>
    
</html>
