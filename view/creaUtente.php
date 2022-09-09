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
        
        <h2 class='text-center navbarfix'>Che utente vuoi creare?</h2>       
        <div class="navbarfix text-center">
            <a href="/view/creaUtenteA.php"><button type="button" class="btn btn-primary">Admin</button></a>
            <div class="space">
                
            </div>
            <a href="/view/creaUtenteB.php"><button type="button" class="btn btn-primary distance">Tecnico</button></a>
            </div>                 
    </body>   
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>        
</html>