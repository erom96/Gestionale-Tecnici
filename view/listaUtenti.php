<?php
$utenteA = new utenteAdmin();
$admin = $utenteA->listaAdmin($pdo);
$utenteB = new utenteTecnico();
$tecnico = $utenteB->listaTecnici($pdo);
?>

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="true">Admin</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tecnici-tab" data-bs-toggle="tab" data-bs-target="#tecnici" type="button" role="tab" aria-controls="tecnici" aria-selected="false">Tecnici</button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">

    <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
        <table class="table table-striped table-dark table-bordered"> 
            <thead>
                <tr>
                    <th scope="col"> NOME </th>
                    <th scope="col"> COGNOME</th>
                    <th scope="col"> CELLULARE</th>
                    <th scope="col" class="col-md-2"> </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin as $admin) { ?>
                    <tr>
                        <td class="align-middle"><?= $admin->nome?></td>
                        <td class="align-middle"><?= $admin->cognome?></td>
                        <td class="align-middle"><?= $admin->cellulare?></td>
                        <td> 
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <a class="btn btn-success" href="/view/aggiornaUtenteA.php/<?=$admin->id?>">
                                        AGGIORNA
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaAdmin/<?=$admin->id?>">
                                        ELIMINA
                                    </a>
                                </div>
                            </div>
                        </td>
            </tbody> 
                <?php             
                } ?>
        </table>
    </div>
    
    <div class="tab-pane fade" id="tecnici" role="tabpanel" aria-labelledby="tecnici-tab">
        <table class="table table-striped table-dark table-bordered">  
            <thead>
                <tr>
                    <th scope="col"> NOME </th>
                    <th scope="col"> COGNOME</th>
                    <th scope="col"> LIVELLO</th>
                    <th scope="col"> ULTIMO ACCESSO</th>
                    <th scope="col" class="col-md-2"> </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tecnico as $tecnico) { ?>
                    <tr>
                        <td class="align-middle"><?= $tecnico->nome?></td>
                        <td class="align-middle"><?= $tecnico->cognome?></td>
                        <td class="align-middle"><?= $tecnico->livello?></td>
                        <td class="align-middle"><?php if(!$tecnico->ultimoAccesso){ ?>
                            Mai connesso <?php } else {
                                echo date('d/m/Y H:i:s', $tecnico->ultimoAccesso);
                            } ?>                          
                        </td>
                        <td> 
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <a class="btn btn-success" href="/view/aggiornaUtenteB.php/<?=$tecnico->id?>">
                                        AGGIORNA
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaTecnico/<?=$tecnico->id?>">
                                        ELIMINA
                                    </a>
                                </div>
                            </div>
                        </td>
            </tbody> 
                <?php
                } ?>
        </table>
    </div> 
</div>  
