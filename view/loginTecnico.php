<html>

    <head>        
        <?php 
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        ?>       
    </head>
    
    <body> 
        <?php
        session_start();
        if ($_SESSION["utente"] || $_SESSION["utenteB"]) {
            header("location: /index.php");
            exit;
        }
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        ?>
        
        <h2 class='text-center navbarfix'>Effettua il login da <u>Tecnico</u></h2>
        <form action='/Controllers/router.php/view/loginTecnico/verifyLogin' class='navbarfix text-center col-8 formfix' method='POST'>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input name='email' type="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input name='password' type="password" class="form-control" id="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </body>   
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>
        
</html>
