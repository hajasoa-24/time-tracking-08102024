<style type="text/css">
    caption{
        font-size: 22pt; margin: 10px 0 20px 0; font-weight: 700;
    }
    table.calendar{
        width:100%; border:1px solid #000;
    }
    td.day{
        width: 14%; height: 140px; border: 1px solid #000; vertical-align: top;
    }
    td.day span.day-date{
        font-size: 14pt; font-weight: 700;
    }
    th.header{
        background-color: #003972; color: #fff; font-size: 14pt; padding: 5px;
    }
    .not-month{
        background-color: #a6c3df;
    }
    td.today {
        background-color:#efefef;
    }
    td.day span.today-date{
        font-size: 16pt;
    }
</style>

<?php 
setlocale(LC_TIME,'fr_FR','french','French_France.1252','fr_FR.ISO8859-1','fra');

function build_calendar($month,$year,$dateArray) {

    // Create array containing abbreviations of days of week.
    $daysOfWeek = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
    
    // What is the first day of the month in question?
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    // How many days does this month contain?
    $numberDays = date('t',$firstDayOfMonth);
    // Retrieve some information about the first day of the
    // month in question.

    $dateComponents = getdate($firstDayOfMonth);
    // What is the name of the month in question?
    $monthName = $dateComponents['month'];
    //var_dump($firstDayOfMonth, $dateComponents); die;
    $monthName = utf8_encode(strftime('%B', $firstDayOfMonth));
    // What is the index value (0-6) of the first day of the
    // month in question.
    $dayOfWeek = $dateComponents['wday'];
    // Create the table tag opener and day headers
    $calendar = "<table class='calendar'>";
    $calendar .= "<caption>$monthName $year</caption>";
    $calendar .= "<tr>";
    // Create the calendar headers
    foreach($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    } 
    // Create the rest of the calendar
    // Initiate the day counter, starting with the 1st.
    $currentDay = 1;
    $calendar .= "</tr><tr>";
    // The variable $dayOfWeek is used to
    // ensure that the calendar
    // display consists of exactly 7 columns.
    if ($dayOfWeek > 0) { 
        $calendar .= "<td colspan='$dayOfWeek' class='not-month'>&nbsp;</td>"; 
    }
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    while ($currentDay <= $numberDays) {
        // Seventh column (Saturday) reached. Start a new row.
        if ($dayOfWeek == 7) {

            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";

        }
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayDataText = '';
        //Exploitation de données
        if(is_array($dateArray) && !empty($dateArray)){
            foreach($dateArray as $dayData){
                if($date == $dayData->etpressource_date){
                    $dayDataText .= '<div class="alert alert-success mx-1">'. $dayData->etpressource_libelle . ' : ' . '<strong>' . $dayData->etpressource_nombre . '</strong>' . ' ETP <button class="btn delete-day-ressource" data-ressource="' . $dayData->etpressource_id . '" style="position:absolute;top:0;right:0;"><i class="fa fa-trash"></i></button></div>';
                }
            }
        }

        if ($date == date("Y-m-d")){
            $calendar .= "<td class='day today' rel='$date'><span class='today-date'>$currentDay</span>" . $dayDataText . "</td>";
        }
        else{
            $calendar .= "<td class='day' rel='$date'><span class='day-date'>$currentDay</span>" . $dayDataText . "</td>";
        }
        // Increment counters
        $currentDay++;
        $dayOfWeek++;
    }
    // Complete the row of the last week in month, if necessary
    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        $calendar .= "<td colspan='$remainingDays' class='not-month'>&nbsp;</td>"; 
    }
    $calendar .= "</tr>";
    $calendar .= "</table>";

    return $calendar;

}

function build_previousMonth($month,$year,$monthString = false){

    $prevMonth = $month - 1;

    if ($prevMonth == 0) {
    $prevMonth = 12;
    }

    if ($prevMonth == 12){  
    $prevYear = $year - 1;
    } else {
    $prevYear = $year;
    }
    $dateObj = DateTime::createFromFormat('!m', $prevMonth);
   // $monthName = $dateObj->format('F'); 
    $monthName = utf8_encode(strftime('%B', strtotime($dateObj->format('Y-m-d'))));
    
    return "<div style='width: 33%; display:inline-block;'><a class='btn btn-sm btn-secondary mb-2' href='?m=" . $prevMonth . "&y=". $prevYear ."'><i class='fa fa-angle-double-left'></i> " . $monthName . "</a></div>";
}

function build_nextMonth($month,$year,$monthString = false){

    $nextMonth = $month + 1;
    if ($nextMonth == 13) {
        $nextMonth = 1;
    }
    if ($nextMonth == 1){  
        $nextYear = $year + 1;
    } else {
        $nextYear = $year;
    }
    $dateObj = DateTime::createFromFormat('!m', $nextMonth);
    //$monthName = $dateObj->format('F'); 
    $monthName = utf8_encode(strftime('%B', strtotime($dateObj->format('Y-m-d'))));
    return "<div style='width: 33%; display:inline-block;'>&nbsp;</div><div style='width: 33%; display:inline-block; text-align:right;'><a class='btn btn-sm btn-secondary mb-2' href='?m=" . $nextMonth . "&y=". $nextYear ."'>" . $monthName . " <i class='fa fa-angle-double-right'></i></a></div>";
}
?>

<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Ressources ETP</h3>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <!--<button id="add-ressource" class="btn btn-primary mx-2"><i class="fa fa-plus-circle pr-2"></i>Importer des ressources</button>-->
            <button id="add-ressource" class="btn btn-primary mx-2"><i class="fa fa-plus-circle pr-2"></i>Ajouter des ressources</button>

        </div>
    </div>
   
    <div class="row mt-3">
        <div class="col-md-12">
        <?php 
            $m = $this->input->get('m');
            $y = $this->input->get('y');
            $monthString = 'Fevrier';
            $dateArray = $ressources;

            if ($m == ""){

                $dateComponents = getdate();
                $month = $dateComponents['mon'];
                $year = $dateComponents['year'];
            } else {

                $month = $m;
                $year = $y;

            }

            echo build_previousMonth($month, $year);
            echo build_nextMonth($month,$year);
            echo build_calendar($month,$year,$dateArray);
        ?>

        </div>
    </div>
</div>

<!-- MODAL AJOUT RESSOURCE -->
<div class="modal fade" id="addRessourceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Nouvelle demande de congés" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout ressource</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div id="errmsg-addRessourceModal" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
            <p></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <form method="post" id="form_add_ressource" name="form_add_ressource" action="<?= site_url('etp/addRessource') ?>">

            <div class="form-group row">
                <label for="ressource_campagne" class="col-sm-3 col-form-label">Campagne </label>
                <div class="col-sm-9">
                    <select name="ressource_campagne" id="ressource_campagne" class="form-control" required>
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="ressource_mission" class="col-sm-3 col-form-label">Mission </label>
                <div class="col-sm-9">
                    <select name="ressource_mission" id="ressource_mission" class="form-control" required>
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="ressource_date" class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-4">
                    <input type="date" required="true" name="ressource_date_du" class="form-control" id="ressource_date_du">
                </div>
                <div class="col-sm-1 text-center">Au</div>
                <div class="col-sm-4">
                    <input type="date" required="true" name="ressource_date_au" class="form-control" id="ressource_date_au" >
                </div>
            </div>

            <div id="ressource_date_list" class="form-group row"></div>

            <input type="hidden" id="action-addRessourceModal" name="action-addRessourceModal" value="">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" id="send_import" value="send_import" class="btn btn-primary">Ajouter</button>
            </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL AJOUT RESSOURCE -->

<!-- MODAL IMPORT RESSOURCE -->
<div class="modal fade" id="importRessourceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Nouvelle demande de congés" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Nouvel import</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div id="errmsg-addRessourceModal" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
            <p></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <form method="post" id="form_add_ressource" name="form_add_ressource" action="<?= site_url('etp/importRessouce') ?>" enctype="multipart/form-data">

               <div class="form-group row">
                    <label for="file_ressouce" class="col-sm-4 col-form-label">Fichier d'import</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" name="file_ressouce" id="file_ressource" accept=".csv" />
                    </div>
              </div>

              <div class="form-group row">
                    <label for="file_ressouce_template" class="col-sm-4 col-form-label"></label>
                    <div class="col-sm-8">
                        <button class="btn btn-sm btn-dark"><i class="fa fa-download"></i> Télécharger le template</button>
                    </div>
              </div>

              <input type="hidden" id="action-importRessourceModal" name="action-importRessourceModal" value="">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="send_import" value="send_import" class="btn btn-primary">Importer</button>
              </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL IMPORT RESSOURCE -->

<script type="text/javascript">

    //GLOBAL
    var typeConge = "<?= TYPECONGE_CONGE ?>";
    var typePermission = "<?= TYPECONGE_PERMISSION ?>";
    var congeAValiderSup = "<?= A_VALIDER_SUP?>";
    var congeAValiderDir = "<?= A_VALIDER_DIR?>";
    var congeATraiterRh = "<?= A_TRAITER_RH?>";
    var congeValide = "<?= VALIDE?>";
    var congeRefuse = "<?= REFUSE?>";
    var reposMaladie = "<?= REPOS_MALADIE?>";
    var assistanceMaternelle = "<?= ASSISTANCE_MATERNELLE?>";
    var congeAutres = "<?= AUTRES?>";

    $(document).ready(function(){
        
        var modalAddRessource = new bootstrap.Modal(document.getElementById('addRessourceModal'));

        //initialisation datatable

        var modalControl = {
            'init' : function(){
                $('#importRessourceModal input').val('');
                $('.form-conge, .form-permission, .form-date').hide();
                $('#permission_heuredatedebut, #permission_heuredatefin').val('00:00');
                $('#action-importRessourceModal').val('add');
                $('#congeToDelete').val();
                $('#congeToDeleteEtat').val();
            },
            'addModal' : function(){
                this.init();
                $('#form-addconge-verif').val('sent');
                $("#importRessourceModal").modal('show');
            },
            'editModal' : function(data){
                this.init();
                $('#action-importRessourceModal').val('edit');
                $('#conge_type').val(data.conge_type);
                $('#edit-conge-id').val(data.conge_id);
                $('#edit-conge-user').val(data.conge_user);
                $('#conge_type').trigger('change');
                
                let datedebut = moment(data.conge_datedebut);
                let datefin = moment(data.conge_datefin);

                $('#conge_datedebut').val(datedebut.format('YYYY-MM-DD'));
                $('#conge_datefin').val(datefin.format('YYYY-MM-DD'));
                $('#conge_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#conge_heuredatefin').val(datefin.format('HH:mm'));
                $('#permission_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#permission_heuredatefin').val(datefin.format('HH:mm'));

                $('#conge_datefin').trigger('change');

                //$('#conge_duree').val(data.conge_duree);
                $('#conge_motif').val(data.conge_motif);
                $('#importRessourceModal .modal-title').html('Edition de demande');
                $('#form_add_ressource').prop('action', '<?= site_url('conges/saveEditConge/') ?>' + data.conge_id);
                $('#form-addconge-verif').val('sent');
                

                $("#importRessourceModal").modal('show');
            },
            'deleteModal' : function(data){
                this.init();
                $('#congeToDelete').val(data.conge_id);
                $('#congeToDeleteEtat').val(data.conge_etat);
                $("#deleteCongeModal").modal('show');
            },
            'suiviEtatModal' : function(data){
                this.init();
                //TODO Appel Ajax pour récupérer la liste des histoetatconge de la personne concernée
                $.ajax({
                    url : "<?= site_url('conges/getHistoEtatConge') ?>",
                    type : 'POST',
                    data : {'conge' : data.conge_id},
                    dataType : 'json',
                    success : function(response){
                        let tableBody = '';
                        if(!response.error){
                            //Formater le tbody
                            
                             response.data.forEach(elt => {
                                tableBody += '<tr><th>'+moment(elt.histoetatconge_date).format('DD/MM/YYYY HH:mm')+'</th><td>'+elt.etatconge_libelle+'</td><td>'+elt.validateur+'</td></tr>';
                            });
                            
                        }
                        $('#suiviCongeModal table tbody').html(tableBody);
                    }
                })
                
                $("#suiviCongeModal").modal('show');
            }
        };

        $('#showHideValidatedConge').on('change', function(){
            let showHideValidated = $(this).is(':checked');
            $.ajax({
                url : '<?= site_url('conges/showValidatedConges')?>',
                data : {'showHideValidated' : showHideValidated},
                dataType : 'json',
                type : 'POST',
                success : function(response){
                    if(!response.err){
                        table.ajax.reload();
                    }
                }
            })

        })

        $('#add-ressource').on('click', function(){
            $('#ressource_campagne').html('');
            //On récupère la liste des campagnes via ajax
            $.ajax({
                url : '<?= site_url('apietp/campagne/') . $top['userid'] ?>',
                method : 'get',
                dataType : 'json',
                success : function(resp){
                    let options = '<option value=""></option>';
                    resp.data.forEach(function(cmp){
                        options += '<option value="'+cmp.campagne_id+'">'+cmp.campagne_libelle+'</option>';
                    })
                    $('#ressource_campagne').html(options);
                    modalAddRessource.show();
                } 
            })
            
        })

        $('#ressource_campagne').on('change', function(){
            $('#ressource_mission').html('');
            let campagne = $(this).val();
            //On récupère la liste des missions via ajax
            $.ajax({
                url : '<?= site_url('apietp/mission/') ?>' + campagne,
                method : 'get',
                dataType : 'json',
                success : function(resp){
                    let options = '<option value=""></option>';
                    resp.data.forEach(function(mission){
                        options += '<option value="'+mission.mission_id+'">'+mission.mission_libelle+'</option>';
                    })
                    $('#ressource_mission').html(options);
                } 
            })
        })

        $('#ressource_date_au').on('change', function(){
            let dateDu = $('#ressource_date_du').val();
            let dateAu = $('#ressource_date_au').val();
            if(!dateDu) 
                alert(' "Date Du" ne peut être vide !')
            else 
                loadRessourceFormByDate(dateDu, dateAu);
        })

        $(document).on('click', '.delete-day-ressource', function(){
            let ressourceId = $(this).data('ressource');
            if(confirm("Etes-vous sur de bien vouloir libérer cette ressource ?")){
                $.ajax({
                    url : '<?= site_url('apietp/ressource/') ?>' + ressourceId,
                    method : 'delete',
                    dataType : 'json',
                    success : function(resp){
                        location.reload();
                    } 
                })
            }
        })
      
    })

    function loadRessourceFormByDate(du, au)
    {
        //let listElement = '<label for="ressource_date" class="col-sm-2 offset-sm-2 col-form-label">%LABEL%</label><div class="col-sm-2"><input type="text" class="form-control"/></div>';
        //let temp = '<label for="ressource_date" class="col-sm-2 offset-sm-2 col-form-label">%LABEL%</label><div class="col-sm-2"><input type="text" class="form-control"/></div>';
        var listElement = '';
        var start = new Date(du);
        var end = new Date(au);
        var loop = new Date(start);
        while(loop <= end){          
            let dateString = loop.toLocaleDateString('fr-FR', { weekday:"short", year:"numeric", month:"short", day:"numeric"})
            let dateOutput = moment(loop).format('YYYY-MM-DD');
            listElement += '<div class="row mb-3"><label for="ressource_date" class="col-sm-4 offset-sm-3 col-form-label">'+dateString+'</label><div class="col-sm-4"><input type="text" class="form-control" name="ressource_day_nombre[]"/><input type="hidden" name="ressource_day[]" value="'+dateOutput+'" /></div></div> ';

            var newDate = loop.setDate(loop.getDate() + 1);
            loop = new Date(newDate);
        }
        
        $('#ressource_date_list').html(listElement);
    }
</script>