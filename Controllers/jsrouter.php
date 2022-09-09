<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/database/database.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/Models/utenteAdmin.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/Models/utenteTecnico.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/Models/intervento.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/Models/commento.php');
require ($_SERVER['DOCUMENT_ROOT'].'/Models/gestoreMail.php');

class jsrouter {

    public function requests() {
        global $pdo;
        switch ($_POST) {
            case isset($_POST['creaCommento']):
                $commento = new commento();
                $utenteB = new utenteTecnico();
                $last_id = $commento->creaCommento($pdo, $_POST['creaCommento']);
                $datiCommento = $commento->datiCommento($pdo, $last_id);
                if($datiCommento[0]->idTecnico) {                   
                   $tecnicoCommento = $utenteB->trovaTecnico($pdo, $datiCommento[0]->idTecnico);
                }
                if($datiCommento[0]->idAdmin) {
                   $utenteA = new utenteAdmin();
                   $adminCommento = $utenteA->trovaAdmin($pdo, $datiCommento[0]->idAdmin);
                }
                $commentino = new commento();
                $colore = $commentino->importanzaCommento($datiCommento[0]->importanza);
                ?>
                <article id="<?=$last_id?>">
                    <?php if($tecnicoCommento) { ?> <p> <b> <?=$tecnicoCommento[0]->nome?> <?=$tecnicoCommento[0]->cognome?> </b> &emsp;&emsp; <?php } else { ?> <p> <b> <?=$adminCommento[0]->nome?> <?=$adminCommento[0]->cognome?> </b> &emsp;&emsp; <?php } ?>
                    <i style="font-size: 12px;"><?=date('d/m/Y - H:i:s',$datiCommento[0]->dataCreazione)?></i> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; Importanza: <i style="<?=$colore?>"><?=$datiCommento[0]->importanza?> </i></p>
                    <p> <?=$datiCommento[0]->descrizione?></p>
                </article>
                <div class="row">
                    <div class="push-md-9 col-md-12 text text-md-center">
                    <hr>
                </div>
                </div> <br>
                <?php
                break;
            case isset($_POST['terminaIntervento']):
                $intervento = new intervento();
                $utenteB = new utenteTecnico();
                $success = $intervento->chiudiIntervento($pdo, $_POST['terminaIntervento']);
                $result = $intervento->trovaIntervento($pdo, $_POST['terminaIntervento']);
                $array = [
                    'successIntervento' => $success,
                    'dataTermine' => date('d/m/Y', $result[0]->dataTermine),
                ];
                $dataTermine = json_encode($array);
                echo $dataTermine;
                break;
            case isset($_POST['prendiIntervento']):
                session_start();
                $intervento = new intervento();
                $utenteB = new utenteTecnico();
                $success = $utenteB->prendiIntervento($pdo, $_SESSION['id'], $_POST['prendiIntervento']);
                $nomeUtente = $_SESSION['utenteB'];
                $array = [
                    'success' => $success,
                    'nomeUtente' => $nomeUtente,
                    'idIntervento' => $_POST['prendiIntervento'],
                ];
                $tecnico = json_encode($array);
                echo $tecnico;
                break;
            case isset($_POST['showid']):
                session_start();
                $spazi = "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
                $spazi1 = "&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;";
                $id=$_POST['showid'];
                $intervento = new intervento();
                $utenteB = new utenteTecnico();
                $interventi = $intervento->trovaIntervento($pdo, $id); 
                if (time() > $interventi[0]->dataLimite) {
                    $redText = 'redtext';
                } else {
                    $redText = '';
                }
                ?>
                  
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title w-100" id="exampleModalLabel"><?=$interventi[0]->nome?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>                  
                    <div class="modal-body">
                        <div id="messaggioTerminaIntervento" class="alert alert-success" style="display: none;">
                        Intervento Chiuso!
                        </div>
                        <div id="messaggioAssegnaIntervento" class="alert alert-success" style="display: none;">
                        Intervento Assegnato!
                        </div>
                        <div class="d-flex">                   
                            <?php 
                            if($interventi[0]->idTecnico) {
                                $nomeTecnico = $utenteB->trovaTecnico($pdo, $interventi[0]->idTecnico);
                            }
                            ?>
                            <div class="w-25 d-inline-block"><?php if(time()>$interventi[0]->dataLimite) { echo 'Scaduto'; } else { echo 'Scade'; } ?> il <b class='<?=$redText?>'><?=date('d/m/Y',$interventi[0]->dataLimite)?></b></div>
                            <div class="w-75 d-inline-block"><b id="terminatoIl"><?php if($interventi[0]->dataTermine) { ?> </b> Terminato il <b> <?=date('d/m/Y',$interventi[0]->dataTermine)?> <?php } else { ?> <?=$spazi?> <?php } ?> </b><?=$spazi1?>Assegnato a <?php if($nomeTecnico) { ?><b><?=$nomeTecnico[0]->nome?> <?=$nomeTecnico[0]->cognome?></b> <?php } else { session_start(); ?><b id="tecnicoGrassetto"> nessuno &emsp; </b> <?php if($_SESSION['utenteB']) { ?> <button type="button" id="prendiIntervento" value="<?=$id?>" tecnico="<?=$_SESSION['utenteB']?>" class="btn btn-dark">Prendi Incarico</button>  <?php } } ?></div>
                        </div>
                        <br><br>
                        <div>
                            <textarea disabled class='form-control' rows="15"><?=$interventi[0]->descrizione?></textarea>
                        </div>
                        <br><br>
                        <div>
                            <?php
                            if($_SESSION['utenteB'] && $interventi[0]->stato === 'non iniziato') {
                        
                            } else { ?>                             
                                <div id="commentoInviato" class="alert alert-success" style="display: none;">
                                Commento Aggiunto!
                                </div>
                                <form id="datiCommento">
                                    <div>
                                        <textarea id="messaggioTextArea" name="messaggio" required class='form-control w-75' rows='2' placeholder="Scrivi un commento..."></textarea> 
                                    </div>
                                <br>
                                <div class="col-md-2">               
                                    <label for="importanza" class="form-label">Importanza</label>
                                    <select name="importanza" class="form-select" id="importanza">
                                        <option value="bassa">Bassa</option>
                                        <option value="media">Media</option>    
                                        <option value="alta">Alta</option>
                                        <option value="critica">Critica</option>
                                    </select>
                                </div>
                                <div class='d-flex d-inline justify-content-end'>
                                    <button id="creaCommento" value="<?=$interventi[0]->id?>" type='button' class='btn btn-success'>Commenta</button>
                                </div>
                                </form>
                            <?php                     
                            } ?>
                        </div>
                        <br>
                    </div>
                    <hr>
                    <?php 
                    $commento = new commento();
                    $commenti = $commento->trovaInterventoCorrelato($pdo, $interventi[0]->id);
                    ?>
                    <div>
                        <h4><b>Commenti</b></h4>
                        <br>
                        <div id="sezioneCommenti">
                            <?php 
                            foreach ($commenti as $commento) { 
                                if($commento->idTecnico) {                   
                                    $tecnicoCommento = $utenteB->trovaTecnico($pdo, $commento->idTecnico);
                                }
                                if($commento->idAdmin) {
                                    $utenteA = new utenteAdmin();
                                    $adminCommento = $utenteA->trovaAdmin($pdo, $commento->idAdmin);
                                }  
                                $commentino = new commento();
                                $colore = $commentino->importanzaCommento($commento->importanza);
                                ?>
                                <article id="<?=$commento->id?>">
                                    <?php if($tecnicoCommento) { ?> <p> <b> <?=$tecnicoCommento[0]->nome?> <?=$tecnicoCommento[0]->cognome?> </b> &emsp;&emsp; <?php } else { ?> <p> <b> <?=$adminCommento[0]->nome?> <?=$adminCommento[0]->cognome?> </b> &emsp;&emsp; <?php } ?>
                                    <i style="font-size: 12px;"><?=date('d/m/Y - H:i:s',$commento->dataCreazione)?></i> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; Importanza: <i style="<?=$colore?>"><?=$commento->importanza?> </i></p>
                                    <p> <?=$commento->descrizione?></p>
                                </article>
                                <div class="row">
                                    <div class="push-md-9 col-md-12 text text-md-center">
                                    <hr>
                                    </div>
                                </div> <br>
                            <?php
                            } ?>
                        </div>
                    </div>
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                        <?php 
                        if($_SESSION['id'] === $interventi[0]->idTecnico || $_SESSION['utente']) {
                            if(!$interventi[0]->dataTermine) { ?>
                                <button id="terminaIntervento" value="<?=$id?>" type="button" class="btn btn-primary">Termina Intervento</button>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
                break;
            case $_POST['filter'] == 1:
                session_start();
                $intervento = new intervento();
                $interventi = $intervento->filtraIntervento($pdo, 1);
                $utenteB = new utenteTecnico();
                $iMieiInterventi = $intervento->filtraMieiInterventi($pdo, 1, $_SESSION['id']);
                
                ?>
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
                            <?php foreach ($interventi as $interventi) {
                                if(time()>$interventi->dataLimite) {
                                    $redText = 'redtext';
                                } else {
                                    $redText = '';
                                }
                                ?>
                                <tr>
                                    <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$interventi->id?>"><?= $interventi->nome ?></a></td>
                                    <td class="align-middle"><?= $interventi->priorita ?></td>
                                    <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $interventi->dataLimite) ?></td>
                                    <td class="align-middle"><?= $interventi->stato ?></td>
                                    <?php
                                    if ($interventi->idTecnico) {
                                        $nomeTecnico = $utenteB->trovaTecnico($pdo, $interventi->idTecnico);
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
                                                <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $interventi->id ?>">
                                                    AGGIORNA
                                                </a>
                                            </div>
                                            <div class="col-md-5">
                                                <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $interventi->id ?>">
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
                                <th scope="col"> TITOLO </th>
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
                                }
                                ?>
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
                <?php
                break;
            case $_POST['filter'] == 2:
                session_start();
                $intervento = new intervento();
                $interventi = $intervento->filtraIntervento($pdo, 2);
                $utenteB = new utenteTecnico();
                $iMieiInterventi = $intervento->filtraMieiInterventi($pdo, 2, $_SESSION['id']);
                ?>
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
                            <?php foreach ($interventi as $interventi) {
                                if(time()>$interventi->dataLimite) {
                                    $redText = 'redtext';
                                } else {
                                    $redText = '';
                                }
                                ?>
                                <tr>
                                    <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$interventi->id?>"><?= $interventi->nome ?></a></td>
                                    <td class="align-middle"><?= $interventi->priorita ?></td>
                                    <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $interventi->dataLimite) ?></td>
                                    <td class="align-middle"><?= $interventi->stato ?></td>
                                    <?php
                                    if ($interventi->idTecnico) {
                                        $nomeTecnico = $utenteB->trovaTecnico($pdo, $interventi->idTecnico);
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
                                                <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $interventi->id ?>">
                                                    AGGIORNA
                                                </a>
                                            </div>
                                            <div class="col-md-5">
                                                <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $interventi->id ?>">
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
                                <th scope="col"> TITOLO </th>
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
                                    }
                                    ?>
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
                <?php
                break;
            case $_POST['filter'] == 3:
                session_start();
                $intervento = new intervento();
                $interventi = $intervento->filtraIntervento($pdo, 3);
                $utenteB = new utenteTecnico();
                $iMieiInterventi = $intervento->filtraMieiInterventi($pdo, 3, $_SESSION['id']);
                ?>
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
                            <?php foreach ($interventi as $interventi) {                               
                                if(time()>$interventi->dataLimite) {
                                    $redText = 'redtext';
                                } else {
                                    $redText = '';
                                }
                                ?>
                                <tr>
                                    <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$interventi->id?>"><?= $interventi->nome ?></a></td>
                                    <td class="align-middle"><?= $interventi->priorita ?></td>
                                    <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $interventi->dataLimite) ?></td>
                                    <td class="align-middle"><?= $interventi->stato ?></td>
                                    <?php
                                    if ($interventi->idTecnico) {
                                        $nomeTecnico = $utenteB->trovaTecnico($pdo, $interventi->idTecnico);
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
                                                <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $interventi->id ?>">
                                                    AGGIORNA
                                                </a>
                                            </div>
                                            <div class="col-md-5">
                                                <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $interventi->id ?>">
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
                                <th scope="col"> TITOLO </th>
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
                                }
                                ?>
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
                <?php
                break;
            case $_POST['filter'] == 4:
                session_start();
                $intervento = new intervento();
                $interventi = $intervento->listaTuttiInterventi($pdo);
                $utenteB = new utenteTecnico();
                $iMieiInterventi = $intervento->iMieiInterventi($pdo, $_SESSION['id']);
                ?>
                <div class="tab-pane fade show active redtext" id="tutti" role="tabpanel" aria-labelledby="tutti-tab">
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
                            <?php foreach ($interventi as $interventi) { 
                                    if(time()>$interventi->dataLimite) {
                                        $redText = 'redtext';
                                    } else {
                                        $redText = '';
                                    }
                                    ?>
                                    <tr>
                                        <td id='listaInterventi' class="align-middle"><a class="cliccabile" id="<?=$interventi->id?>"><?= $interventi->nome ?></a></td>
                                        <td class="align-middle"><?= $interventi->priorita ?></td>
                                        <td class="align-middle <?=$redText?>"><?= date('d/m/Y', $interventi->dataLimite) ?></td>
                                        <td class="align-middle"><?= $interventi->stato ?></td>
                                        <?php
                                        if ($interventi->idTecnico) {
                                            $nomeTecnico = $utenteB->trovaTecnico($pdo, $interventi->idTecnico);
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
                                                    <a class="btn btn-success" href="/view/aggiornaIntervento.php/<?= $interventi->id ?>">
                                                        AGGIORNA
                                                    </a>
                                                </div>
                                                <div class="col-md-5">
                                                    <a class="btn btn-danger" onclick="return confirm('Sei sicuro?')" href="/Controllers/router.php/eliminaIntervento/<?= $interventi->id ?>">
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
                                <th scope="col"> TITOLO </th>
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
                                }
                                ?>
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
                <?php
                break;
        }
    }
}

$jsrouter = new jsrouter();
$jsrouter->requests();
