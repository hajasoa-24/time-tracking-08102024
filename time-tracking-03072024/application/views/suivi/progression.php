<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Progression</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="progress" id="progress" style="height: 40px;">
                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width:0%"></div>
            </div>
            <span id="progress-bar-begin-lib" class="float-start"></span>
            <span id="progress-bar-end-lib" class="float-end"></span> 
        </div>
        <!-- <div class="col-md-12 mt-2">
            <div>Vous avez travaillé pendant <span id="progress-total-work" style="font-weight:bold"></span> aujourd'hui</div>
            <div>Pauses prises <span id="progress-total-pause" style="font-weight:bold"></span></div>
        </div> -->

        <div class="col-md-6">
            
        </div>
    </div>
</div>

<div class="row col-md-9">
    
    <div class="row mt-4">
        <div class="col-md-12  title-page">
            <h3>Détails des pauses</h3>
        </div>
    </div>
    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-pause" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Type de pause</th>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var total_pause = "0";
    $(document).ready(function(){
        /**
         * Fonction permettant de charger dynamiquement les données depuis les tables t_shift et t_pause dans le progressbar
         */
        //Refresh chargement tt les 30s
        loadProgressBar();
        /*setInterval(function(){ loadProgressBar();}, 30000);*/

        
    });

    //initialisation datatable
    var pauseProgressTable = $("#list-pause").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("dashboard/getListPause"); ?>",
            columns : [
                { data : "typepause_libelle" },
                { 
                    data : "pause_begin",
                    render : function(data, type, row){
                        var momentDate = moment(data);
                        return momentDate.format("HH:mm");
                    } 
                },
                { 
                    data : "pause_end",
                    render : function(data, type, row){
                        var momentDate = moment(data);
                        if(momentDate.isValid()){
                            return momentDate.format("HH:mm");
                        }else {
                            return '';
                        }
                        
                    } 
                },
                { 
                    data : 'pause_duree',
                    render : function(data, type, row){
                        if(data){
                            duree = moment(data, 'HH:mm:ss').format('HH:mm');
                        } else{
                            duree = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
                        }
                        return duree;
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display){
                var api = this.api(), data;
                total_duree = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    moment(total_duree).format('HH:mm')
                );    
            },
        });

    function loadProgressBar()
    {
        $.ajax({
            url : '<?=  site_url('dashboard/getProgressData')?>',
            dataType : 'json',
            data : {},
            type : 'POST',
            success : function(resp){
                //console.log(resp.dayTimeline);
                var dayTimeline = resp.dayTimeline;
                console.log(dayTimeline);
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

                    $('#progress').html(timeline);

                    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl)
                    });
                }

                if(resp.debutProd){
                    $('#progress-bar-begin-lib').html(resp.debutProd);
                }
                if(resp.endDay){
                    $('#progress-bar-end-lib').html(resp.endDay);
                }
                if(resp.totalWork){
                    $('#progress-total-work').html(resp.totalWork);
                }
                if(resp.totalPause){
                    $('#progress-total-pause').html(resp.totalPause);
                }
            }
        })
    }
</script>