<html>
    
    <head>        
        <?php 
        session_start();
        require ($_SERVER['DOCUMENT_ROOT'].'/Controllers/router.php');
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        ?>        
    </head>
    
    <body>        
        <?php
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        if(!$_SESSION["utente"]) {
           header("location: /index.php");
           exit;
        }
        ?>
        
        <div class="text-center navbarfix">
            <?php
            require ($_SERVER['DOCUMENT_ROOT'].'/view/listaInterventi.php');
            ?>
        </div>
    </body>
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>
</html>