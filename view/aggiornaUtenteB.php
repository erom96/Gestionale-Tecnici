<html>

    <head>      
        <?php 
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        require ($_SERVER['DOCUMENT_ROOT'].'/Controllers/router.php');
        ?>       
    </head>
       
    <body>        
        <?php
        session_start();
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim(str_replace("view/aggiornaUtenteB", "", $url), '/');
        $tokens = explode('/', $url);
        $tecnico = new utenteTecnico();
        $dati = $tecnico->trovaTecnico($pdo, $tokens[1]);
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        if(!$_SESSION["utente"]) {
            header("location: /index.php");
            exit;
            }
        ?>
        
        <h2 class='text-center navbarfix'>Aggiorna Tecnico <u> <?=$dati[0]->nome?> <?=$dati[0]->cognome?> </u> </h2>
        
        <form method="POST" action="/Controllers/router.php/aggiornaTecnico/<?=$dati[0]->id?>"> 
                <div class="container">
                    <div class="row">
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="email" class="form-label">email</label>
                            <input name="email" type="email" class="form-control" id="email" value="<?=$dati[0]->email?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="password" class="form-label">Password (lascia vuoto per non modificare)</label>
                            <input name="password" type="password" class="form-control" id="password">
                        </div>                       
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="confirmPassword" class="form-label">Conferma Password (lascia vuoto per non modificare)</label>
                            <input name="confirmPassword" type="password" class="form-control" id="confirmPassword">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="nome" class="form-label">Nome</label>
                            <input name="nome" type="text" class="form-control" id="nome"  value="<?=$dati[0]->nome?>">
                        </div>
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input name="cognome" type="text" class="form-control" id="cognome"  value="<?=$dati[0]->cognome?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-4 text-center container">
                            <label for="livello" class="form-label">Livello</label>
                            <select name="livello" class="form-select" id="livello">
                                <option value="basso" <?php if($dati[0]->livello == "basso") { echo "selected = 'selected'"; } ?>>Basso</option>
                                <option value="medio" <?php if($dati[0]->livello == "medio") { echo "selected = 'selected'"; } ?>>Medio</option>    
                                <option value="alto" <?php if($dati[0]->livello == "alto") { echo "selected = 'selected'"; } ?>>Alto</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-4 text-center container">
                            <button type="submit" class="btn btn-primary">Aggiorna</button>
                        </div>
                    </div>                    
                </div>
        </form>   
    </body>   
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>        
</html>
