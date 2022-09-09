<html>
    
    <head>
        <?php
        session_start();
        require ($_SERVER['DOCUMENT_ROOT'] . '/public/bootstrap.php');
        ?>
    </head>

    <body>
        <?php
        require ($_SERVER['DOCUMENT_ROOT'] . '/view/navbar.php');

        if ($_SESSION["utente"]) {
            header("location: /view/dashboardA.php");
            exit;
        } else if ($_SESSION["utenteB"]) {
            header("location: /view/dashboardB.php");
            exit;
        } else { ?>
            <div class="text-center navbarfix">
                <h1> Benvenuto nel Gestionale! <br> <br> </h1>
                <h4> Seleziona che utente sei per continuare <br> <br> <br> <br> <br> </h4>
            </div>

            <div class="text-center">
                <a href="/view/loginAdmin.php">  <button type="button" class="btn btn-primary">Admin</button></a>
                <div class="space">
                </div>
                <a href="/view/loginTecnico.php">  <button type="button" class="btn btn-primary distance">Tecnico</button></a>
            </div>
    </body>

        <?php 
        }

    require ($_SERVER['DOCUMENT_ROOT'] . '/view/footer.php');
    ?>

</html>