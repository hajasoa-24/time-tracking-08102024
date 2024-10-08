<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Suivi des ETP</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier validationressource/modalValidation 
             * */ ?>
            
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-validationressource" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Sub</th>
                        <th>Proprio</th>
                        <th>Client</th>
                        <th>Mission</th>
                        <th>Facturation</th>
                        <?php foreach($listMois as $id => $mois) : ?>
                        <th><?= $mois ?></th>
                        <?php endforeach; ?>
                        <th>Totaux</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $totalFacture = []; 
                        $etpNonFacture = [];
                        $totalNonFacture = []; 

                    ?>
                    <?php foreach($listETP as $etp) : ?>
                        <?php $totalLigne = 0; ?>
                        <tr>
                            <td><?= $etp->site ?></td>
                            <td></td>
                            <td><?= $etp->proprio ?></td>
                            <td><?= $etp->client ?></td>
                            <td><?= $etp->mission ?></td>
                            <td></td>

                            <?php foreach($listMois as $id => $mois) : ?>
                                <?php 
                                    $etpCourant = 0;
                                ?>
                                <?php if(isset($etp->etpdatas[$id])) : ?>
                                    
                                    <?php foreach($etp->etpdatas[$id] as $idRessource => $listEtpMois) : ?>
                                        
                                        <?php foreach($listEtpMois as $etpMois) : ?>
                                            
                                            <?php if($etpMois->isfacture == '1' && $etpMois->role == ROLE_AGENT) : ?>
                                                
                                                <?php $etpCourant = round(($etpMois->jourstravaille / (60 * 60)) / ($etpMois->joursouvres * 8), 3); ?>
                                                <?php 
                                                    if(!isset($totalFacture[$id])) $totalFacture[$id] = 0;
                                                    $totalFacture[$id] += $etpCourant; 
                                                    $totalLigne += $etpCourant;
                                                ?>
                                            <?php else : ?>
                                                <?php
                                                $etpCourantNonFacture = round(($etpMois->jourstravaille / (60 * 60)) / ($etpMois->joursouvres * 8), 3);
                                                if(!isset($etpNonFacture[$etp->site][$etpMois->lib])){
                                                    $etpNonFacture[$etp->site][$etpMois->lib] = [];
                                                }
                                                if(!isset($etpNonFacture[$etp->site]['SUPERVISEURS/CADRES'])){
                                                    $etpNonFacture[$etp->site]['SUPERVISEURS/CADRES'] = [];
                                                }
                                                if($etpMois->role == ROLE_SUP || $etpMois->role == ROLE_CADRE){
                                                    $tempDatas = (isset($etpNonFacture[$etp->site]['SUPERVISEURS/CADRES'][$etp->mission][$id])) ? $etpNonFacture[$etp->site]['SUPERVISEURS/CADRES'][$etp->mission][$id] + $etpCourantNonFacture  : $etpCourantNonFacture;
                                                    $etpNonFacture[$etp->site]['SUPERVISEURS/CADRES'][$etp->mission][$id] = $tempDatas;
                                                }else{
                                                    if(!isset($etpNonFacture[$etp->site][$etpMois->lib][$etp->mission])){
                                                        $etpNonFacture[$etp->site][$etpMois->lib][$etp->mission] = [];
                                                    }
                                                    $tempDatas = (isset($etpNonFacture[$etp->site][$etpMois->lib][$etp->mission][$id])) ? $etpNonFacture[$etp->site][$etpMois->lib][$etp->mission][$id] + $etpCourantNonFacture  : $etpCourantNonFacture;
                                                    $etpNonFacture[$etp->site][$etpMois->lib][$etp->mission][$id] = $tempDatas;
                                                }
                                                ?>
                                            <?php endif; ?>

                                        <?php endforeach; ?>
                                        
                                    <?php endforeach; ?>

                                <?php endif; ?>

                                <td><?= $etpCourant ?></td>
                                
                            <?php endforeach; ?>
                            <th><?= $totalLigne ?></th>

                        </tr>
                    <?php endforeach; ?>
                    <tr class="totaux" style="background-color: #e7e483;">
                        <th colspan="6" class="text-right"><b>TOTAL 1</b></th>
                        <?php $totalLigne = 0; ?>
                        <?php foreach($listMois as $id => $mois) : ?>
                            <?php if(isset($totalFacture[$id])) $totalLigne += $totalFacture[$id]; ?>
                            <th><?= (isset($totalFacture[$id])) ? $totalFacture[$id] : 0 ?></th>
                        <?php endforeach; ?>
                        <th><?= $totalLigne ?></th>
                    </tr>
                    
                    <?php foreach($etpNonFacture as $site => $datasParSite) : ?>
                        <?php foreach($datasParSite as $typeRessource => $datasParRessource) : ?>
                            <?php foreach($datasParRessource as $mission => $datasMois) : ?>
                                <?php $totalLigne = 0; ?>
                                <tr>
                                    <td><?= $site ?></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= $typeRessource ?></td>
                                    <td><?= $mission ?></td>
                                    <td></td>
                                    <?php foreach($listMois as $id => $mois) : ?>
                                        <?php 
                                            if(!isset($totalNonFacture[$id])) $totalNonFacture[$id] = 0;
                                            if(!isset($datasMois[$id])) $datasMois[$id] = 0;
                                        ?>
                                        <td><?= (isset($datasMois[$id])) ? $datasMois[$id] : 0 ?></td>
                                        <?php 
                                            $totalNonFacture[$id] += $datasMois[$id];
                                            $totalLigne += $datasMois[$id]; 
                                        ?>
                                    <?php endforeach; ?>
                                    <th><?= $totalLigne ?></th>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <!-- services -->
                    <?php foreach($listETPService as $service => $etpService) : ?>
                        <?php $totalLigne = 0; ?>
                        <tr>
                            <td colspan="6"><?= $service ?></td>
                            <?php foreach($listMois as $id => $mois) : ?>            
                                <?php 
                                    $etp = (isset($etpService[$id])) ? round(($etpService[$id]->jourstravaille / (60 * 60)) / ($etpService[$id]->joursouvres * 8), 3)  : 0;
                                    if(!isset($totalNonFacture[$id])) $totalNonFacture[$id] = 0;
                                    $totalNonFacture[$id] += $etp; 
                                    $totalLigne += $etp;
                                ?>
                                <td><?= $etp ?></td>
                            <?php endforeach; ?>
                            <th><?= $totalLigne ?></th>
                        </tr>
                    <?php endforeach; ?>
                    <!-- end services -->

                    <tr class="totaux" style="background-color: #e7e483;">
                        <th colspan="6" class="text-right"><b>TOTAL 2</b></th>
                        <?php $totalLigne = 0; ?>
                        <?php foreach($listMois as $id => $mois) : ?>
                            <th><?= (isset($totalNonFacture[$id])) ? $totalNonFacture[$id] : 0 ?></th>
                            <?php if(isset($totalNonFacture[$id])) $totalLigne += $totalNonFacture[$id]; ?>
                        <?php endforeach; ?>
                        <th><?= $totalLigne ?></th>
                    </tr>
                    
                    <tr class="totaux" style="background-color: #a7e583;">
                        <th colspan="6" class="text-right"><b>TOTAL 1 + 2</b></th>
                        <?php 
                            $totalLigne = 0; 
                            $grandTotal = [];
                        ?>
                        <?php foreach($listMois as $id => $mois) : ?>
                            <?php 
                                $grandTotal[$id] = 0;
                                if(isset($totalFacture[$id])) $grandTotal[$id] += $totalFacture[$id]; 
                                if(isset($totalNonFacture[$id])) $grandTotal[$id] += $totalNonFacture[$id]; 
                            ?>
                            <th><?= $grandTotal[$id] ?></th>
                            <?php $totalLigne += $grandTotal[$id]; ?>
                        <?php endforeach; ?>
                        <th><?= $totalLigne ?></th>
                    </tr>

                    <tr class="livre-paie">
                        <td colspan="6" class="text-right">LIVRE DE PAIE</td>
                        <?php $livrePaieMois = []; ?>
                        <?php foreach($listMois as $id => $mois) : ?>
                            <?php 
                                $totalLigne = 0; 
                                $livrePaieMois[$id] = 0;
                                foreach($livrePaie as $livre){
                                    if($livre->livrepaie_month == $id){
                                        $livrePaieMois[$id] = $livre->livrepaie_valeur;
                                        $totalLigne += $livrePaieMois[$id];
                                    }
                                }
                            ?>
                            <td class="text-right"><?= $livrePaieMois[$id] ?><?php if($role == ROLE_ADMINRH): ?><a href="#" class="ml-2 setlivrepaiemois" data-month="<?= $id ?>" data-valeur="<?= $livrePaieMois[$id] ?>" data-year="<?= $year ?>"><i class="fa fa-pencil"></i></a><?php endif; ?></td>
                            <?php $totalLigne += 0; ?>
                        <?php endforeach; ?>
                        <th><?= $totalLigne ?></th>
                    </tr>
                    <!-- ECART -->
                    <tr class="ecart">
                        <th colspan="6" class="text-right table-secondary"><b>ECART</b></th>
                        <?php 
                            $totalLigne = 0; 
                            $ecart = [];
                        ?>
                        <?php foreach($listMois as $id => $mois) : ?>
                            <?php 
                                $ecart_class = '';
                                $gTotal = $grandTotal[$id];
                                $lPaie = $livrePaieMois[$id];
                                $ecart[$id] = $gTotal - $lPaie; 
                                $ecart_class = ($ecart[$id] >= 0) ? 'table-success' : 'table-danger';

                            ?>
                            <th class="<?= $ecart_class ?>"><?= $ecart[$id] ?></th>
                            <?php $totalLigne += $ecart[$id]; ?>
                        <?php endforeach; ?>
                        <th><?= $totalLigne ?></th>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL LIVRE DE PAIE -->
<div class="modal fade" id="modalLivrePaie" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLivrePaie" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Livre de Paie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form id="formLivrePaie" name="formLivrePaie" action="">

                    <div class="mb-3 row">
                        <label for="livrepaie_annee_lib" class="col-sm-4 col-form-label">Année </label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="livrepaie_annee_lib" class="form-control" value="" id="livrepaie_annee_lib"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="livrepaie_mois_lib" class="col-sm-4 col-form-label">Mois </label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="livrepaie_mois_lib" class="form-control" value="" id="livrepaie_mois_lib"/>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="livrepaie_valeur" class="col-sm-4 col-form-label">Valeur </label>
                        <div class="col-sm-8">
                            <input type="text" name="livrepaie_valeur" class="form-control" value="" id="livrepaie_valeur"/>
                        </div>
                    </div>

                    <input type="hidden" name="livrepaie_mois" id="livrepaie_mois"/> 
                    <input type="hidden" name="livrepaie_annee" id="livrepaie_annee" /> 

                    <input type="hidden" name="action" id="livrepaie_action" />
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="setlivrepaie" class="btn btn-primary">Valider</button>
            </div>

        </div>

    </div>
</div>

<!-- END MODAL LIVRE DE PAIE -->

<script type="text/javascript">
    $(document).ready(function(){
        var modalLivrePaie =  new bootstrap.Modal(document.getElementById('modalLivrePaie'));
        $(document).on('click', '.setlivrepaiemois', function(){
            let moisencours = $(this).data('month');
            let anneeencours = $(this).data('year');
            let valeur = $(this).data('valeur');


            $('#livrepaie_annee_lib').val(anneeencours);
            $('#livrepaie_mois_lib').val(moment().month(moisencours).format('MMM'));

            $('#livrepaie_annee').val(anneeencours);
            $('#livrepaie_mois').val(moisencours);
            $('#livrepaie_valeur').val(valeur);

            modalLivrePaie.show();
        })

        $(document).on('click', '#setlivrepaie', function(){

            let mois = $('#livrepaie_mois').val();
            let annee = $('#livrepaie_annee').val();
            let valeur = $('#livrepaie_valeur').val();
            let data = {'annee' : annee, 'mois': mois, 'valeur' : valeur};

            $.ajax({
                url : "<?= site_url('apietp/livrepaie/') ?>",
                datatype : 'json',
                data : data,
                type : 'post',
                success : function(resp){
                    if(!resp.err){
                        modalLivrePaie.hide();
                        location.reload();
                    }
                }
            })  
        })
    })
</script>