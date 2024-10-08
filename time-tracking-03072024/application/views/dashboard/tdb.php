<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Tableau de bord</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 float-end">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="dashboard-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreDashboardDu" id="dashboard-du" placeholder="Du" value="<?= $filtreDashboardDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="dashboard-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreDashboardAu" id="dashboard-au" placeholder="Au" value="<?= $filtreDashboardAu ?>">
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                </form>
        </div> 
    </div>
    
    <div id="tdb-container" class="row mt-3" >
        
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function(){
        

        $.ajax({
            url : '<?=  site_url('dashboard/getTDBDatas')?>',
            dataType : 'json',
            data : {},
            type : 'POST',
            success : function(response){
                var html = '';
                console.log(response);
                if(response.datas){
                    var datas = response.datas;
                    var htmlPerDate = '';
                    //console.log(response.datas);
                    for(var date in datas){
                        //console.log(datas[perDate]);
                        let perDate = datas[date];
                        htmlPerDate = '<div class="col-md-12 py-2 my-2 mx-2" style="background-color: #fff; font-weight: bold"><i class="fa fa-calendar"></i> <span>'+ date +'</span></div>';
                        //On boucle les elements
                        for(var item in perDate){
                            console.log(item);
                            let eltObj = perDate[item];
                            for(var i in eltObj){
                                let elt = eltObj[i];
                                let htmlElt = '<div class="col-md-4" >'+
                                                '<div class="card">'+
                                                    '<div class="card-header text-white bg-success mb-3">'+
                                                        '<h6 class="card-tittle">' + elt.libelle + '</h6>'+
                                                        '</div>'+
                                                        '<div class="card-body">'+
                                                        '<div class="row">'+
                                                            '<div class="col-md-6  mb-2"><div class="h6">Présents</div><div class="display-6 text-success show-list" data-action="present" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-present-'+i+'\'>Chargement ...</div>">' + elt.present + '</div></div>'+
                                                            '<div class="col-md-6 mb-2" ><div class="h6">Absents</div><div class="display-6 text-danger show-list" data-action="absent" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-absent-'+i+'\'>Chargement ...</div>">' + elt.absent + '</div></div>'+
                                                            '<div class="col-md-6 mb-2"><div class="h6">Incomplets</div><div class="display-6 text-warning show-list" data-action="incomplet" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-incomplet-'+i+'\'>Chargement ...</div>">' + elt.incomplet + '</div></div>'+
                                                            '<div class="col-md-6 mb-2"><div class="h6">Congés</div><div class="display-6 text-info show-list" data-action="conge" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-conge-'+i+'\'>Chargement ...</div>">' + elt.conge + '</div></div>'+
                                                            '<div class="col-md-6 mb-2"><div class="h6">OFF</div><div class="display-6 text-secondary show-list" data-action="off" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-off-'+i+'\'>Chargement ...</div>">' + elt.off + '</div></div>'+
                                                            '<div class="col-md-6 mb-2"><div class="h6">HORS SHIFT</div><div class="display-6 text-info show-list" data-action="pasencorearrive" data-date="'+elt.day+'" data-type="'+elt.type+'" data-typeid="'+elt.typeID+'" data-id="'+i+'" data-bs-toggle="tooltip" data-bs-html="true" title="<div class=\'toolltip\' id=\'tooltip-pasencorearrive-'+i+'\'>Chargement ...</div>">' + elt.pasencorearrive + '</div></div>'+
                                                            
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';
    
                                htmlPerDate += htmlElt;
                            }

                        }
                        htmlPerDate += '';

                        html += htmlPerDate;

                    }
                    
                }
                $('#tdb-container').html(html);
                $("[data-bs-toggle=tooltip]").tooltip();
                
            }
        })


    });

    $(document).on( 'mouseenter', '.show-list', function(e){
        e.stopPropagation();
        var id = $(this).data('id');
        var type = $(this).data('type');
        var typeID = $(this).data('typeid');
        var currDate = $(this).data('date');
        var action = $(this).data('action');
        var tooltip = $(this).find('.tooltip');
        $.ajax({
            url : '<?=  site_url('dashboard/getListUsersWhich')?>',
            dataType : 'json',
            data : { type : type, typeID : typeID, date : currDate, action : action },
            type : 'post',
            success : function(response){
                
                if(response.datas){
                    let htmlData = '';
                    response.datas.forEach(elt => {
                        htmlData += '<span>'+elt.usr_prenom+'</span><br/>';
                    })
                    $('#tooltip-'+action+'-'+id).html(htmlData);
                }
                
            }
        })
        
    })

</script>