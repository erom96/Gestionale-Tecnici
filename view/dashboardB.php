<html>
    
    <head>        
        <?php 
        session_start();
        require ($_SERVER['DOCUMENT_ROOT'].'/Controllers/router.php');
        require ($_SERVER['DOCUMENT_ROOT'].'/public/bootstrap.php');
        if(!$_SESSION["utenteB"]) {
           header("location: /index.php");
           exit;
        }
        ?>        
    </head>
    
    <body>       
        <?php
        require ($_SERVER['DOCUMENT_ROOT'].'/view/navbar.php');
        ?>
        
        <div class="text-center navbarfix">
            <?php
            require ($_SERVER['DOCUMENT_ROOT'].'/view/listaInterventiTecnici.php');
            ?>
        </div>
    </body>
    
    <?php
    require ($_SERVER['DOCUMENT_ROOT'].'/view/footer.php');
    ?>
</html>
