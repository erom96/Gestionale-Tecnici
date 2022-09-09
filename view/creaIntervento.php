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
        require ($_SERVER['DOCUMENT_ROOT'] . '/view/navbar.php');
        if (!$_SESSION["utente"]) {
            header("location: /index.php");
            exit;
        }
        ?>

        <h2 class='text-center navbarfix'>Crea Intervento</h2>
        <form method="POST" action="/Controllers/router.php/creaIntervento"> 
            <input type='hidden' name='admin' id='admin' value='<?= $_SESSION['id'] ?>'>
            <div class="container">
                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="nome" class="form-label">Titolo</label>
                        <input name="nome" type="text" class="form-control" id="nome">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="priorita" class="form-label">Priorità</label>
                        <select name="priorita" class="form-select" id="priorita">
                            <option value="bassa">Bassa</option>
                            <option value="media">Media</option>    
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="dataLimite" class="form-label">Da terminare entro il</label>
                        <input name="dataLimite" type="date" class="form-control" id="dataLimite">
                    </div>

                    <?php
                    $utenteB = new utenteTecnico();
                    $tecnico = $utenteB->listaTecnici($pdo);
                    ?>

                    <div class="mb-3 col-md-4 text-center container">
                        <label for="tecnico" class="form-label">Tecnico da assegnare</label>
                        <select name="tecnico" class="form-select" id="tecnico">
                            <option selected='selected' value="">Non assegnare</option>
                            <?php foreach ($tecnico as $tecnico) { ?>
                                <option value="<?= $tecnico->id ?>"><?= $tecnico->nome ?> <?= $tecnico->cognome ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-5 col-md-7 text-center container">
                        <label for="descrizione" class="form-label">Descrizione</label>
                        <textarea name="descrizione" class="form-control" id="descrizione" rows="6"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <button type="submit" class="btn btn-success">Crea</button>
                    </div>
                </div>
            </div>
        </form>   
    </body>   

    <?php
    require ($_SERVER['DOCUMENT_ROOT'] . '/view/footer.php');
    ?>
</html>
