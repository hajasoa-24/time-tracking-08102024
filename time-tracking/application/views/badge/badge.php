<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Liste des agents avec badge à exporter</h2>
        </div>
    </div>
    <div class="row mt-3">
        <form action="<?php echo site_url("badge/exportPdf"); ?>" method="post" id="combine">
            <div class="col-md-12">
                <table id="list-utilisateur" class="table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <!--  <th>ID</th> -->
                            <th></th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Matricule</th>
                            <th>Initiale</th>
                            <th>Actif</th>
                            <th>Role</th>
                            <th>Id ingress</th>
                            <th>Campagne</th>
                            <th>Service</th>
                            <th class="notexport">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="list-data">

            </div>
            <button class="btn btn-primary" type="submit">Combiner en pdf</button>
        </form>
    </div>
</div>

<script type="text/javascript">
$(window).ready(function() {
    //initialisation datatable
    $("#list-utilisateur").DataTable({
        language: {
            url: "<?= base_url("assets/datatables/fr-FR.json"); ?>"
        },
        ajax: "<?= site_url("user/getListUtilisateur"); ?>",
        columns: [{
                data: null,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="check" value=' +
                        data
                        .usr_id + '>';
                }
            },
            {
                data: "usr_nom"
            },
            {
                data: "usr_prenom"
            },
            {
                data: "usr_matricule"
            },
            {
                data: "usr_initiale"
            },
            {
                data: null,
                render: function(data, type, row) {
                    return (data.usr_actif) == "1" ?
                        '<span class="badge bg-success">Oui</span>' :
                        '<span class="badge bg-danger">Non</span>';
                }
            },
            {
                data: "role_libelle"
            },
            {
                data: "usr_ingress"
            },
            {
                data: "campagnes",
                render: function(data, type, row) {
                    let limit = 30;
                    if (data) {
                        if (data.length <= limit) {
                            return data
                        } else {
                            let text = data.slice(0, 10) + ' ...';
                            return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                data + '">' + text + '</span>'
                        }
                    } else {
                        return ''
                    }
                }
            },
            {
                data: "services",
                render: function(data, type, row) {
                    let limit = 30;
                    if (data) {
                        if (data.length <= limit) {
                            return data
                        } else {
                            let text = data.slice(0, 10) + ' ...';
                            return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                data + '">' + text + '</span>'
                        }
                    } else {
                        return ''
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return '<button type="button" title="Exporter badge" data-user="' + data
                        .usr_id +
                        '" class="download-badge btn btn-secondary btn-sm mr-1"><i class="fa fa-download"></i></button>';
                }
            }
        ]
    });

    var data = [];

    $(document).on('click', '.check', function() {
        const key = $(this).val();
        var canAdd = true;
        for (let i = 0; i < data.length; i++) {
            if (data[i] == key) {
                data.splice(i, 1);
                canAdd = false;
            }
        }
        if (canAdd) {
            data.push(key);
        }

    });

    $('#combine').on('submit', function() {
        data.forEach(element => {
            $('.list-data').append($('<input>').attr({
                type: 'hidden',
                name: 'donnee[]',
                value: element,
            }))
        });
    })
})
</script>