<html>

    <head>        
        <?php 
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        ?>       
    </head>
      
    <body>        
        <?php
        session_start();
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        if(!$_SESSION["utente"]) {
            header("location: /index.php");
            exit;
        }
        ?>
        
        <h2 class='text-center navbarfix'>Crea utente Admin</h2>
        <form method="POST" action="/Controllers/router.php/creaUtente/admin"> 
            <div class="container">
                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="email" class="form-label">email</label>
                        <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" id="password">
                    </div>
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="confirmPassword" class="form-label">Conferma Password</label>
                        <input name="confirmPassword" type="password" class="form-control" id="ConfirmPassword">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="nome" class="form-label">Nome</label>
                        <input name="nome" type="text" class="form-control" id="nome">
                    </div>
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="cognome" class="form-label">Cognome</label>
                        <input name="cognome" type="text" class="form-control" id="cognome">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-4 text-center container">
                        <label for="cellulare" class="form-label">Cellulare</label>
                        <input name="cellulare" type="text" class="form-control" id="cellulare">
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
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>       
</html>

