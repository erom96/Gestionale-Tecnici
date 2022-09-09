<?php
$utenteA = new utenteAdmin();
$admin = $utenteA->listaAdmin($pdo);
$utenteB = new utenteTecnico();
$tecnico = $utenteB->listaTecnici($pdo);
$intervento = new intervento();
$interventi = $intervento->listaInterventiTecnici($pdo, $_SESSION['id']);
$interventiDisponibili = $intervento->listaInterventiDisponibili($pdo);
?>

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="mieiTecnici-tab" data-bs-toggle="tab" data-bs-target="#mieiTecnici" type="button" role="tab" aria-controls="mieiTecnici" aria-selected="true">I miei Interventi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="interventiDisponibili-tab" data-bs-toggle="tab" data-bs-target="#interventiDisponibili" type="button" role="tab" aria-controls="interventiDisponibili" aria-selected="false">Interventi Disponibili</button>
    </li>
</ul>
<div class="tab-content" id="interventiTecnici">
    <div class="tab-pane fade show active" id="mieiTecnici" role="tabpanel" aria-labelledby="mieiTecnici-tab">
        <table class="table table-striped table-dark table-bordered">  
            <thead>
                <tr>
                    <th scope="col"> TITOLO </th>
                    <th scope="col"> PRIORITÀ</th>
                    <th scope="col"> COMPLETARE ENTRO</th>
                </tr>
            </thead>
            <tbody id="interventiTecniciAdd">
                <?php               
                foreach ($interventi as $intervento) { 
                        if(time()>$intervento->dataLimite) {
                            $redText = 'redtext';
                        } else {
                            $redText = '';
                        } ?>
                    <tr id="intervento<?=$intervento->id?>">
                        <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$intervento->id?>"><?= $intervento->nome ?></a></td>
                        <td class="align-middle"><?= $intervento->priorita ?></td>
                        <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $intervento->dataLimite) ?></td>
                <?php                 
                } ?>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="interventiDisponibili" role="tabpanel" aria-labelledby="interventiDisponibili">
        <table class="table table-striped table-dark table-bordered">  
            <thead>
                <tr>
                    <th scope="col"> TITOLO</th>
                    <th scope="col"> PRIORITÀ</th>
                    <th scope="col"> COMPLETARE ENTRO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interventiDisponibili as $interventoDisponibile) { 
                    if(time()>$interventoDisponibile->dataLimite) {
                        $redText = 'redtext';
                    } else {
                        $redText = '';
                    } 
                    $showid = "show$interventoDisponibile->id";
                ?>
                    <tr id="intervento<?=$interventoDisponibile->id?>">
                        <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$interventoDisponibile->id?>"><?= $interventoDisponibile->nome ?></a></td>
                        <td class="align-middle"><?= $interventoDisponibile->priorita ?></td>
                        <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $interventoDisponibile->dataLimite) ?></td>
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
