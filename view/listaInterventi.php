<?php
$utenteA = new utenteAdmin();
$admin = $utenteA->listaAdmin($pdo);
$utenteB = new utenteTecnico();
$tecnico = $utenteB->listaTecnici($pdo);
$intervento = new intervento();
$interventi = $intervento->listaTuttiInterventi($pdo);
$iMieiInterventi = $intervento->iMieiInterventi($pdo, $_SESSION['id']);
?>

<div style="text-align: right; padding-right: 30px; padding-bottom: 10px"> Filtra per stato</div>

<div class="float-end">
    <div class="row">
        <div>
            <select name="filterstato" class="form-select" id="filterstato">
                <option value='4'>Tutti</option>
                <option value="1">In corso</option>
                <option value="2">Non assegnato</option>    
                <option value="3">Terminato</option>
            </select>
        </div>
    </div>
</div>
<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tutti-tab" data-bs-toggle="tab" data-bs-target="#tutti" type="button" role="tab" aria-controls="tutti" aria-selected="true">Tutti gli Interventi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="miei-tab" data-bs-toggle="tab" data-bs-target="#miei" type="button" role="tab" aria-controls="miei" aria-selected="false">I miei Interventi</button>
    </li>
</ul>
<div class="tab-content" id="interventiAdmin">
    <div class="tab-pane fade show active" id="tutti" role="tabpanel" aria-labelledby="tutti-tab">
        <table class="table table-striped table-dark table-bordered">  
            <thead>
                <tr>
                    <th scope="col"> TITOLO </th>
                    <th scope="col"> PRIORITÀ</th>
                    <th scope="col"> COMPLETARE ENTRO</th>
                    <th scope="col"> STATO</th>
                    <th scope="col"> ASSEGNATO A</th>
                    <th scope="col" class="col-md-2"> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($interventi as $intervento) { 
                    if(time()>$intervento->dataLimite) {
                        $redText = 'redtext';
                    } else {
                        $redText = '';
                    }
                ?>
                    <tr>
                        <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$intervento->id?>"><?= $intervento->nome ?></a> </td>
                        <td class="align-middle"><?= $intervento->priorita ?></td>
                        <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $intervento->dataLimite) ?></td>
                        <td class="align-middle"><?= $intervento->stato ?></td>
                        <?php
                        if ($intervento->idTecnico) {
                            $nomeTecnico = $utenteB->trovaTecnico($pdo, $intervento->idTecnico);
                            ?>
                            <td class="align-middle"><?= $nomeTecnico[0]->nome ?> <?= $nomeTecnico[0]->cognome ?></td>
                        <?php                    
                        } else { ?>
                            <td class="align-middle">non assegnato</td>    
                        <?php
                        } ?>
                        <td> 
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $intervento->id ?>">
                                        AGGIORNA
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $intervento->id ?>">
                                        ELIMINA
                                    </a>
                                </div>
                            </div>
                        </td>
                <?php                 
                } ?>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="miei" role="tabpanel" aria-labelledby="miei-tab">
        <table class="table table-striped table-dark table-bordered">  
            <thead>
                <tr>
                    <th scope="col"> TITOLO 2</th>
                    <th scope="col"> PRIORITÀ</th>
                    <th scope="col"> COMPLETARE ENTRO</th>
                    <th scope="col"> STATO</th>
                    <th scope="col"> ASSEGNATO A</th>
                    <th scope="col" class="col-md-2"> </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($iMieiInterventi as $iMieiInterventi) { 
                    if(time()>$iMieiInterventi->dataLimite) {
                        $redText = 'redtext';
                    } else {
                        $redText = '';
                    } ?>
                    <tr>
                        <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$iMieiInterventi->id?>"><?= $iMieiInterventi->nome ?></a></td>
                        <td class="align-middle"><?= $iMieiInterventi->priorita ?></td>
                        <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $iMieiInterventi->dataLimite) ?></td>
                        <td class="align-middle"><?= $iMieiInterventi->stato ?></td>
                        <?php
                        if ($iMieiInterventi->idTecnico) {
                            $nomeTecnico = $utenteB->trovaTecnico($pdo, $iMieiInterventi->idTecnico);
                        ?>
                            <td class="align-middle"><?= $nomeTecnico[0]->nome ?> <?= $nomeTecnico[0]->cognome ?></td>
                        <?php                         
                        } else { ?>
                            <td class="align-middle">non assegnato</td>    
                        <?php                              
                        } ?>
                        <td> 
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $iMieiInterventi->id ?>">
                                        AGGIORNA
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $iMieiInterventi->id ?>">
                                        ELIMINA
                                    </a>
                                </div>
                            </div>
                        </td>
                <?php                 
                } ?> 
            </tbody>
        </table>
    </div> 
</div> 

<div class="modal fade" id="modalFade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id='modalDialog'>

    </div>
</div>
