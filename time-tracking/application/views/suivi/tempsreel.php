<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Temps réel</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="table-tempsreel" class="table" >
                <thead>
                    <tr>
                        <th>Entrée</th>
                        <th>Agent</th>
                        <?php if($role == ROLE_CLIENT): ?>
                        <th>Pseudo Francais</th>
                        <?php endif; ?>
                        <?php if($role != ROLE_CLIENT): ?>
                        <th>Campagne</th>
                        <th>Service</th>
                        <?php endif; ?>
                        <th>Progression</th>
                        <th>Prod</th>
                        <th>NB.Pause</th>
                        <?php if($role != ROLE_CLIENT): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-6">
            
        </div>
    </div>
</div>

<!-- MODAL DE VALIDATION DE FIN DE SHIFT AGENT -->
    <div class="modal fade" id="modalConfirmFinShiftAgent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalConfirmFinShiftAgent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de fin de shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Etes-vous sur de bien vouloir terminer ce shift ?
                <form id="confirmFinShiftAgentForm" name="confirmFinShiftAgentForm" action="<?= site_url('control/endShiftAgent') ?>" method="post">
                  <input type="hidden" name="shiftToEndID" id="shiftToEndID"/>
                  <input type="hidden" name="shifEndedBy" id="shifEndedBy"/>
               </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmFinShiftAgent" class="btn btn-primary">Terminer le shift</button>
            </div>
            </div>
        </div>
        </div>
    <!-- END MODAL DE VALIDATION DE FIN DE SHIFT AGENT -->

<script type="text/javascript">
    var total_details = '00:00';
    $(document).ready(function(){
        //initialisation datatable
       var table = $("#table-tempsreel").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("dashboard/getTempsReel"); ?>",
            columns : [
                {
                    data : 'shift_begin',
                    render : function(data, type, row){
                        return (moment(data).isValid()) ? moment(data).format('HH:mm') : ''
                    }
                },
                {
                    data : 'usr_prenom',
                },
                <?php if($role == ROLE_CLIENT): ?>
                    { data : "usr_pseudo" },
                <?php endif; ?>

                <?php if($role != ROLE_CLIENT): ?>
                { data : 'list_campagne',
                    visible : true,
                    render : function(data, type, row){
                        let limit = 30;
                        if(data){
                            if(data.length <= limit){
                                return data
                            }else{
                                let text = data.slice(0, 10) + ' ...';
                                return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + data + '">' + text + '</span>' 
                            } 
                        }else{
                            return ''
                        }
                    } 
                },
                { data : 'list_service',
                    visible : true,
                    render : function(data, type, row){
                        let limit = 30;
                        if(data){
                            if(data.length <= limit){
                                return data
                            }else{
                                let text = data.slice(0, 10) + ' ...';
                                return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + data + '">' + text + '</span>' 
                            } 
                        }else{
                            return ''
                        }
                    } 
                },
                <?php endif; ?>
                {
                    data : null,
                    width : '50%',
                    render : function(data, type, row){
                        let progressbar = loadProgressBarInTable(row);
                        return progressbar
                    }
                },
                {
                    data : 'total_work',
                },
                {
                    data : 'nb_pause',
                },
                <?php if($role != ROLE_CLIENT): ?>
                { 
                    data : null,
                    render : function(data, type, row){
                        let disabled = "";
                        let status = data.shift_status;
                        if(status == '4'){
                            disabled = 'disabled="disabled"';
                        }
                        return '<button title="Términer ce shift" data-shift="'+data.shift_id+'"   class="terminer-shift-agent btn btn-danger btn-sm mr-1"' + disabled + '><i class="fa fa-stop-circle"></i></button>';
                    } 
                }
                <?php endif; ?>
            ]
        });

        function reloadDatable () {
            table.ajax.reload();
        };
        //setInterval( reloadDatable, 2000);
    })

    function loadProgressBarInTable(resp){
        var dayTimeline = resp.dayTimeline;
        var timeline = '';
        if(dayTimeline){
            dayTimeline.forEach(function(worktime, index){
                let bgColor = (worktime.type == '2') ? 'bg-primary' : ((worktime.type == '3') ? 'bg-warning' : ((worktime.type == '4') ? 'bg-success' : 'bg-danger'));
                let inprogress = (worktime.status == 'inprogress') ? ' progress-bar-animated' : '';
                let toolTipText = ((worktime.type == '2') ? 'En travail pendant ' : (worktime.type == '3') ? 'En pause pendant ' : "Vous êtes connecté mais pas de production demarré depuis ") + worktime.dureeText;
                timeline += '<div class="progress-bar progress-bar-striped ' + bgColor + ' ' + inprogress + '" role="progressbar" style="width:'+worktime.percent+'%;" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="' + toolTipText + '">' +
                    worktime.dureeText +
                '</div>';
            });

            //$('#progress').html(timeline);

            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            });

            return '<div class="row"><div class="progress" style="height: 40px;">'+timeline+'</div></div>';
        }

        
    }

    /**
     * Click sur le bouton terminer ce shift d'un agent, pour cloturer sa journée 
     * Ouverture d'un modal de validation
     */
    $(document).on('click', '.terminer-shift-agent', function(){
        let shiftID = $(this).data('shift');
        let shiftEndedBy = $(this).data('user');
        $('#shiftToEndID').val(shiftID);
        $('#modalConfirmFinShiftAgent').modal('show');
    });

    $(document).on('click', '#confirmFinShiftAgent', function(){

        $('#confirmFinShiftAgentForm').submit();

    });



</script>