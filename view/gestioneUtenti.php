<html>
    
    <head>       
        <?php 
        session_start();
        if(!$_SESSION["utente"]) {
            header("location: /index.php");
            exit;
        }
        require ($_SERVER['DOCUMENT_ROOT'].'/Controllers/router.php');
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        ?>        
    </head>
    
    <body>        
        <?php
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        ?>
        
        <div class="text-center navbarfix">
            <h1> Utenti Registrati <br> <br> </h1>
            
            <?php
            require ($_SERVER['DOCUMENT_ROOT'].'/view/listaUtenti.php');
            ?>
        </div>      
    </body>
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>
</html>