<div class="row">
    <div class="row">
        <div class="col-md-3">
            <!--<button class="btn btn-primary" id="ButtonAddProduction">Ajouter</button>-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Suivi des primes</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form name="setFilter" method="post">
                <div class="form-group row">
                    <label for="service_site" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select name="filtre_month" class="form-control">
                            <?php
                                $months = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Dec");
                                foreach ($months as $index => $month) {
                                    echo '<option value="' . ($index+1) . '" '. ( ($index+1) == $filtre['month'] ? 'selected="selected"' : '' ) . '>' . $month . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="filtre_year" class="form-control">
                            <?php
                                $year = date('Y');
                                $endYear = $year - 5;
                                for($year; $year > $endYear; $year--){
                                    echo '<option value="' . $year . '" '. ( ($year) == $filtre['year'] ? 'selected="selected"' : '' ) . '>' . $year . '</option>';
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="col-sm-2">
                        <button id="doFilterPlanning" class="btn btn-primary btn-sm">Afficher</button>
                    </div>
                    
                </div>
            </form>
        </div> 
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-prime" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Campagne</th>
                        <th>Base mensuelle</th>
                        <th>Total Bonus</th>
                        <th>Total Malus</th>
                        <th>Prime perçue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- VALIDATION AJUSTEMENT -->
<div class="modal fade" id="modalajustementprime" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalajustementprime" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formajustementprime" name="formajustementprime" method="post" action="<?= site_url('primeproduction/addBonusMalus') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Ajustement Prime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3 row">
                        <label for="primeajustement_agent" class="col-sm-4 col-form-label">Agent </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly id="primeajustement_agent" value=""/>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="primeajustement_campagne" class="col-sm-4 col-form-label">Campagne </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" readonly id="primeajustement_campagne" value=""/>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="primeajustement_type" class="col-sm-4 col-form-label">Type d'ajustement </label>
                        <div class="col-sm-8">
                          <select name="primeajustement_type" id="primeajustement_type" class="form-control" required>
                                <option value="">Selectionner un type</option>
                                <option value="<?= PRIME_BONUS ?>">BONUS</option>
                                <option value="<?= PRIME_MALUS ?>">MALUS</option>
                            </select>
                            
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="primeajustement_typebonus" class="col-sm-4 col-form-label">Type d'ajustement </label>
                        <div class="col-sm-8">
                            <select  name="primeajustement_typebonus" id="primeajustement_typebonus" class="form-control">
                                <option value="">-</option>
                                <?php foreach ($typeBonus as $bonus) { ?>
                                  <option value="<?php echo $bonus->primebonus_id ?>"><?php echo $bonus->primebonus_libelle ?></option>
                                <?php }
                                 ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="mcp_client" class="col-sm-4 col-form-label">Ajustement</label>
                        <div class="col-sm-8">
                            <input type="text" name="primeajustement_ajustement" class="form-control" value="" id="primeajustement_ajustement"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="primeajustement_commentaire" class="col-sm-4 col-form-label">Commentaires</label>
                        
                        <div class="col-sm-8">
                            <textarea rows=6 name="primeajustement_commentaire" id="primeajustement_commentaire" class="form-control"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="primeajustement_prime" id="primeajustement_prime" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="sendPrimeAjustement" class="btn btn-primary">Soumettre</button>
                </div>
            </form> 
        </div>
    </div>
</div>
<!-- END MODAL AJUSTEMENT PRIME-->

<!-- AJUSTEMENT PRIME -->
<div class="modal fade" id="modalvalidationajustement" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalvalidationajustement" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Valider des ajustements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="table-validation" class="table table-striped">
                    <thead>
                        <th>Bonus</th>
                        <th>Malus</th>
                        <th>Commentaire</th>
                        <th>Ajustée par</th>
                        <th>Date ajustement</th>
                        <th>Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
            
        </div>
    </div>
</div>
<!-- END MODAL VALIDATION AJUSTEMENT-->

<!-- MODAL DE VALIDATION ACTION -->
<div class="modal fade" id="modalconfirmvalidation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer un service" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Validation d'un ajustement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir valider cet ajustement ? </p>
              <form id="confirmDesactivateServiceForm" name="confirmDesactivateServiceForm" action="<?= site_url('primeproduction/validerAjustement') ?>" method="post">
                  <input type="hidden" name="sendValidation" value="sent">
                  <input type="hidden" name="primeAjustementToValidate" id="primeAjustementToValidate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmValidationAjustement" class="btn btn-primary">Confirmer</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION SERVICE -->

<script type="text/javascript">

    var userId = "<?= $userId ?>";
    var userRole = "<?= $userRole ?>";
    var roleCadre = "<?= ROLE_CADRE ?>";
    var roleDirection = "<?= ROLE_DIRECTION ?>";
    var ajusteurs = [<?php echo '"'.implode('","', $ajusteurs).'"' ?>];
    var validateurs = [<?php echo '"'.implode('","', $validateurs).'"' ?>];

    $(document).ready(function()
    {
        var currencyFormat = Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'MGA',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
        //initialisation datatable
        var validationTable = $("#list-prime").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("primeproduction/getPrimeMonth") . '/' . $filtre['month'] . '/' . $filtre['year']; ?>",
            columns : [
                { data : "prime_date" },
                { data : "usr_nomcomplet" },
                { data : "campagne_libelle" },
                { 
                    data : "prime_basemensuelle",
                    render : function(data, type, row){
                        
                        return currencyFormat.format(data)
                    }
                },
                { 
                    data : "bonus",
                    render : function(data, type, row){
                        return '<span class="link link-success">' + currencyFormat.format(data) + '</span>'
                    }
                },
                { 
                    data : "malus",
                    render : function(data, type, row){
                        return '<span class="link link-danger">' + currencyFormat.format(data) + '</span>'
                    }
                },
                { 
                    data : null,
                    render : function(data, type, row){
                        let bonus = (parseFloat(row.bonus)) ? parseFloat(row.bonus) : 0;
                        let malus = (parseFloat(row.malus)) ? parseFloat(row.malus) : 0;
                        let total = parseFloat(row.prime_basemensuelle) + bonus - malus;
                        return '<b>' + currencyFormat.format(total) + '</b>'
                    }
                },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        let listButton = '';
                        if(userRole == roleCadre && ajusteurs.includes(userId))
                            listButton += '<button title="ajouter un ajustement" data-campagne="'+data.campagne_libelle+'" data-agent="'+data.usr_nomcomplet+'" data-prime="'+data.prime_id+'" class="add-ajustement btn btn-secondary btn-sm mx-1" value="1"><i class="fa fa-edit"></i></button>';
                        if(userRole == roleDirection || validateurs.includes(userId)) 
                            listButton += '<button title="Valider ajustement" data-prime="'+data.prime_id+'" class="validate-ajustement btn btn-primary btn-sm mx-1" value="0"><i class="fa fa-check-square-o"></i></button>';
                        return listButton;
                    }
                }
            ]

        });

        $(document).on('click', '.add-ajustement', function(){
            $('#primeajustement_prime').val('');
            $('#primeajustement_agent').val('');
            $('#primeajustement_campagne').val('');
            let id = $(this).data('prime');
            let agent = $(this).data('agent');
            let campagne = $(this).data('campagne');
            $('#primeajustement_prime').val(id);
            $('#primeajustement_agent').val(agent);
            $('#primeajustement_campagne').val(campagne);
            let data = {};
            $('#modalajustementprime').modal('show');
        });

        $(document).on('click', '.validate-ajustement', function(){
            let prime = $(this).data('prime');
            let data = {'prime':prime};
            $('#table-validation tbody').html('');
            $('#primeAjustementToValidate').val('');            
            $.ajax({
                url : '<?= site_url('primeproduction/getPrimeAjustementByPrime')?>',
                data : data,
                dataType : 'json',
                type : 'POST',
                success : function(response){
                    if(!response.err){
                        $('#table-validation tbody').html(response.htmlData);
                    }
                }
            })
            $('#modalvalidationajustement').modal('show');
        });

        $(document).on('click', '.send-validate-ajustement', function(){
            let id = $(this).data('ajustement');
            $('#primeAjustementToValidate').val(id);
            $('#modalconfirmvalidation').modal('show');
        });

        $(document).on('click', '#confirmValidationAjustement', function(){
            $('#confirmDesactivateServiceForm').submit();
        })

        
    })

</script>