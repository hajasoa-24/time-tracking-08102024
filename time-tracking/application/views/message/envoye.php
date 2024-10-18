
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<section class="content">
    <div class="container-fluid">    
        <div class="col-md-100">
            <form  method="post" id="mainForm" enctype="multipart/form-data">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title text-white">Composé un message</h3>
                    </div>
                    <div id="toast" class="toast" style="display:none; position: fixed; bottom: 20px; right: 20px;">
                        <div class="toast-body">
                            Message envoyé avec succès !
                        </div>
                    </div>  
                    <div class="card-body">
                        <div class="form-group">
                            <label for="destinataire" class="fs-5">Destinataire</label>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="accordion col-md-3" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Role
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show ml-3" data-coreui-parent="#accordionExample">
                                                <div class="form-check">
                                                    <?php foreach ($listRole as $role) { ?>
                                                        <div>
                                                            <input class="form-check-input" type="checkbox" value="<?php echo $role->role_id ?>" id="role_msg" name="expediteur[]">
                                                            <label for="<?php echo $role->role_libelle?>"><?php echo $role->role_libelle?></label>   
                                                        </div>
                                                    <?php } ?>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion col-md-5" style="width: 50%;" id="activite">
                                        <div class="accordion-item" >
                                            <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                User
                                            </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse show ml-3" data-coreui-parent="#activite">
                                               <form class="d-flex mt-2 mb-2" role="search">
                                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="activite" id="user_search">
                                                </form> 
                                            </div>
                                            <div id="collapseTwo" class="accordion-collapse collapse show ml-3 mr-2" data-coreui-parent="#activite">
                                                <select class="form-control mb-2 ml-0 w-60" id="user_select" multiple required></select>
                                                <div id="input_container"></div>  <!--Conteneur pour les nouveaux inputs-->                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion col-md-4" style="width: 50%;" id="activite">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                    Group
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse show ml-3" data-coreui-parent="#activite">
                                                <form class="d-flex mt-2 mb-2" role="search">
                                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="activite" id="campagne_search">
                                                </form>
                                            </div>
                                            <div id="collapseThree" class="accordion-collapse collapse show ml-3 mr-2" data-coreui-parent="#activite">
                                                <select class="form-control mb-2 ml-0 w-60" id="campagne_select" multiple required></select>
                                                <div id="input_campagne"></div>  <!-- Conteneur pour les nouveaux inputs -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <input class="form-control" placeholder="Objet :" id="objet_msg">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="message_msg" placeholder="Message :" rows="3"></textarea>
                        </div>
                        <div class="form-group row t-2">
                            <label for="edit_user_dateembauche" class="col-sm-2 col-form-label" >Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_msg" class="form-control" id="date_msg" readonly/>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input class="form-control" type="file" id="file_msg"   multiple name="fichiers[]">
                        </div>

                        <div id="message"></div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <button type="button" id="myButton" class="btn btn-primary">
                                    <i class="fa fa-paper-plane"></i> Envoyer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
</section>

<script>
    //console.log('<?=  $this->session->userdata('user')['id'];?>');
    //console.log('<?=  $this->session->userdata('user')['nom'];?>');
    //console.log('<?=  $this->session->userdata('user')['prenom'];?>');
    //console.log('<?= $this->session->userdata('user')['nom'] . ' ' . $this->session->userdata('user')['prenom']; ?>');
   
        // Obtenir la date d'aujourd'hui au format YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];
        // Assigner cette date à l'input
    document.getElementById('date_msg').value = today;

    var user_nom = [];
    var userName = '';
    
                /**fonction pour l'envoie du message */
    $(document).ready(function() {
        $('#myButton').click(function () {
            var formData = new FormData($('#mainForm')[0]); // Utiliser le formulaire principal

                // Collecter les valeurs des inputs
            var remainingCampagneId = [];
            $('#input_campagne .campagne-id').each(function() {
                remainingCampagneId.push($(this).val());
            });
            var campagne_destinatair = remainingCampagneId;
            var remainingIds = [];
            $('#input_container .user-id').each(function() {
                remainingIds.push($(this).val());
            });
            var user_destinataire = remainingIds;
            var user_expediteur = <?=  $this->session->userdata('user')['id']; ?>; // ID de l'expéditeur
            var user_expediteur_nom = '<?= $this->session->userdata('user')['nom'] . ' ' . $this->session->userdata('user')['prenom']; ?>';
            var selectedCheckboxes = $('input[id=role_msg]:checked');
            var selectedValues = selectedCheckboxes.map(function() {
                return $(this).val();
            }).get();
            var roles = selectedValues;
            var message = $('#message_msg').val();
            var objet = $('#objet_msg').val();
            var date = $('#date_msg').val();

                // Ajouter des données supplémentaires à formData
            formData.append('userInfo', user_expediteur);
            formData.append('expediteur_nom', user_expediteur_nom);
            formData.append('role_msg', JSON.stringify(selectedValues)); // Convertir en JSON
            formData.append('select_users_specifique', JSON.stringify(remainingIds)); // Convertir en JSON
            formData.append('campagne_dest', JSON.stringify(campagne_destinatair)); // Convertir en JSON
            formData.append('message_msg', message);
            formData.append('objet_msg', objet);
            formData.append('date_msg', date);

                // Vérifier si un fichier est sélectionné
            var fileInput = $('#file_msg')[0];
                if (fileInput.files.length > 0) {
                    var fileName = fileInput.files[0].name;
                    formData.append('file_name', fileName); // Ajouter le nom du fichier
                }

                //console.log(Array.from(formData.entries())); // Afficher les données pour le débogage
                if (!message.trim()) {
                    //console.log("Le message ne peut pas être vide");  
                    // Afficher le toast pour informer l'utilisateur
                    $('#toast').addClass('toast-danger'); // Ajoute la classe de danger
                    $('#toast').text('Le message ne peut pas être vide.'); // Message de toast 
                    $('#toast').fadeIn().delay(3000).fadeOut();
                    $('#toast').toast({ delay: 3000 }); // Initialiser avec un délai
                    $('#toast').toast('show'); // Afficher le toast
                } else {
                    $.ajax({
                        url: '<?php echo site_url('message/insertMessage'); ?>',
                        type: 'POST',
                        data: formData,
                        contentType: false, // Important pour l'envoi de FormData
                        processData: false, // Important pour l'envoi de FormData
                        success: function(response) {
                                // Vider les inputs
                            $('input[id=role_msg]:checked').prop('checked', false);
                            $('#input_container').empty();
                            $('#objet_msg').val('');
                            $('#message_msg').val('');
                            $('#date_msg').val('');
                            $('#file_msg').val('');

                                // Activer les champs des accordions
                            $('#user_search').prop('disabled', false);
                            $('#user_select').prop('disabled', false);
                                // Active les checkboxes si nécessaire
                            $('input[id=role_msg]').prop('disabled', false);

                                // Afficher le toast pour confirmation
                            $('#toast').addClass('toast-succes'); // Ajoute la classe de danger
                            $('#toast').text('Message envoyé avec succès.'); // Message de confirmation
                            $('#toast').fadeIn().delay(3000).fadeOut();
                            $('#toast').toast({ delay: 3000 }); // Initialiser avec un délai
                            $('#toast').toast('show'); // Afficher le toast
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            console.error('Une erreur s\'est produite:', error);
                        }
                    });
                }
        });
    });
            
                /** Fin fonction ajax avec le fichier */
        
</script>

<script>
    /**debut du fonction sur la recherche d'utilisateur par usr_nom,usr_prenom,usr_matricule,usr_initiale */
    var userLists = [];
            
    $.ajax({
        url: '<?= site_url('message/get_all_user') ?>',
        method: "POST",
        dataType: "json",
        success: function (response) {
            userLists = response.data;
            //console.log(userLists);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    }); 

    function populateSelect(userLists){
        var $select = $('#user_select')
            $select.empty(); // Vider le select avant de le remplir
            $.each(userLists, function(index, userList) {
                let option = $("<option>", {
                    value:  userList.usr_nom +' '+ userList.usr_prenom , 
                    text: userList.usr_nom +' '+ userList.usr_prenom + '(' +userList.usr_initiale+ '-' +userList.usr_matricule +')',
                    'data-id':userList.usr_id
            });
                $('#user_select').append(option);
        })
    }
            
    $(document).on('input','#user_search', function() {
        var userInput = $(this).val().toLowerCase(); // Récupérer la valeur et la convertir en minuscules
        var filteredUserList = $.grep(userLists, function(userList) {
            return (
                userList.usr_nom.toLowerCase().includes(userInput) ||
                userList.usr_prenom.toLowerCase().includes(userInput) || 
                userList.usr_matricule.toLowerCase().includes(userInput)||
                userList.usr_initiale.toLowerCase().includes(userInput)
            );
            //console.log(userInput);
        });
        populateSelect(filteredUserList); // Réafficher les suggestions filtrées
    });
    $(document).on('change', '#user_select', function() {
        var selectedOptions = $(this).val(); 
        $('#selected_users').val(selectedOptions ? selectedOptions.join(', ') : '');
    });

    $(document).on('change', '#user_select', function() {
        var selectedOptions = $(this).find('option:selected');
        var selectedValues = [];
        var selectedIds = [];

    // Récupérer les valeurs et les IDs
    selectedOptions.each(function() {
        var optionValue = $(this).val();
        var userId = $(this).data('id'); // Récupérer l'usr_id

        // Vérifier si l'input existe déjà
        if ($('#input_container input[value="' + optionValue + '"]').length === 0) {
            // Ajouter la valeur et l'ID aux tableaux
            selectedValues.push(optionValue);
            selectedIds.push(userId);

            // Créer un input pour chaque option sélectionnée
            $('#input_container').append(
                '<div class="input-group mb-2">' +
                    '<input type="text" class="form-control value" placeholder="Input pour ' + optionValue + '" value="' + optionValue + '" readonly>' +
                    '<input type="hidden" name="user_id[]" class="user-id" value="' + userId + '">' + // Ajouter l'usr_id en tant qu'input caché
                    '<button type="button" class="btn btn-outline-danger remove-input">X</button>' +
                '</div>'
            );

            // Afficher la valeur et l'ID dans la console
           // console.log('Ajouté: ' + optionValue + ', ID: ' + userId);
        }
    });

        // Mettre à jour le champ de texte avec les valeurs sélectionnées
    $('#selected_users').val(selectedValues.length ? selectedValues.join(', ') : ''); 

        // Optionnel : Afficher les IDs sélectionnés dans la console
    //console.log('IDs sélectionnés :', selectedIds.join(', ')); 
    });

        // Gestionnaire d'événements pour supprimer l'input
    $(document).on('click', '.remove-input', function() {
            // Afficher la valeur à supprimer dans la console
        var valueToRemove = $(this).siblings('input[type="text"]').val();
        var userIdToRemove = $(this).siblings('.user-id').val(); // Récupérer l'ID caché

            // Afficher la valeur et l'ID à supprimer dans la console
        //console.log('Supprimé: ' + valueToRemove + ', ID: ' + userIdToRemove);

            // Supprimer l'élément du DOM
        $(this).closest('.input-group').remove();
    
                // Afficher les IDs et valeurs restants après la suppression
            var remainingValues = [];
            var remainingIds = [];
        $('#input_container .user-id').each(function() {
            remainingIds.push($(this).val());
            remainingValues.push($(this).siblings('input[type="text"]').val());
        });
    
        //console.log('IDs restants :', remainingIds);
        //console.log('Valeurs restantes :', remainingValues);
    });

        // Optionnel : Récupérer les IDs restants (si nécessaire)
    $(document).on('click', '.remove-input', function() {
            var remainingIds = [];
            $('#input_container .user-id').each(function() {
            remainingIds.push($(this).val());
        });

        // Afficher les IDs restants dans la console
        //console.log('IDs restants :', remainingIds.join(', '));
    });
    /**Fin du fonction recherche d'utilisateur */
    

        /**Fonction pour fair la recherche d'une campagne */
    var ListCampagne = [];

    $.ajax({
        url: '<?php echo site_url('message/get_all_campagne'); ?>',
        method: "POST",
        dataType: "json",
        success: function(response) {
            ListCampagne = response.data;
            //console.log(ListCampagne);
            
            populateSelect_Campagne(ListCampagne);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        },
    });

    function populateSelect_Campagne(campagnes) {
        var $select = $('#campagne_select');
        $select.empty(); // Vider le select avant de le remplir

        $.each(campagnes, function(index, campagne) {
            let option = $("<option>", {
                value: campagne.campagne_libelle,
                text: campagne.campagne_libelle, // Affiche le libellé de la campagne
                'data-id': campagne.campagne_id   // Assure-toi que `id` existe
            });
            $select.append(option);
        });
    }

    $(document).on('input', '#campagne_search', function() {
        var searchTerm = $(this).val().toLowerCase(); // Récupérer la valeur et la convertir en minuscules

        var filteredCampagneList = $.grep(ListCampagne, function(campagne) {
            return campagne.campagne_libelle.toLowerCase().includes(searchTerm);
        });

        populateSelect_Campagne(filteredCampagneList); // Réafficher les suggestions filtrées
    });

    $(document).on('change', '#campagne_select', function() {
        var selectedOptions = $(this).find('option:selected'); // Récupérer les options sélectionnées
        var selectedValues_campagne = [];
        var selectedIds_campagne = [];

        selectedOptions.each(function() {
            var optionValue_campagne = $(this).val();
            var campagneId = $(this).data('id'); // Récupérer l'ID de la campagne

            // Vérifier si l'input existe déjà
            if ($('#input_campagne input[value="' + optionValue_campagne + '"]').length === 0) {
                selectedValues_campagne.push(optionValue_campagne);
                selectedIds_campagne.push(campagneId);

                // Créer un input pour chaque option sélectionnée
                $('#input_campagne').append(
                    '<div class="input-group mb-2">' +
                        '<input type="text" class="form-control" placeholder="Input pour ' + optionValue_campagne + '" value="' + optionValue_campagne + '" readonly>' +
                        '<input type="hidden" name="campagne_id[]" class="campagne-id" value="' + campagneId + '">' + // Ajouter l'ID en tant qu'input caché
                        '<button type="button" class="btn btn-outline-danger remove-input-campagne">X</button>' +
                    '</div>'
                );
                console.log('Ajouté: ' + optionValue_campagne + ', ID: ' + campagneId);
            }
        });

        // Mettre à jour le champ de texte avec les valeurs sélectionnées
        $('#selected_campagnes').val(selectedValues_campagne.length ? selectedValues_campagne.join(', ') : ''); 
    });

        // Gestionnaire d'événements pour supprimer l'input
    $(document).on('click', '.remove-input-campagne', function() {
        var valueToRemove_campagne = $(this).siblings('input[type="text"]').val();
        var userIdToRemove_campagne = $(this).siblings('.campagne-id').val();
        console.log('Supprimé: ' + valueToRemove_campagne + ', ID: ' + userIdToRemove_campagne);
        // Supprimer l'élément du DOM
        $(this).closest('.input-group').remove();
        
        // Mettre à jour le champ de texte si nécessaire
        var remainingCampagneId = [];
        $('#input_campagne .campagne-id').each(function() {
            remainingCampagneId.push($(this).val());
            console.log('IDs restants :', remainingCampagneId.join(', '));
        });
        
        $('#selected_campagnes').val(remainingCampagneId.length ? remainingCampagneId.join(', ') : '');
    });
</script>

<script>


        /**fonction pour metre disable les checkbox et l''input recherche */
    $(document).ready(function() {
        
        $('input[type="checkbox"]').on('change', function() {
        
            if ($('input[type="checkbox"]:checked').length > 0) {
                $('#user_search').prop('disabled', true);
                $('#user_select').prop('disabled', true);
                $('#campagne_search').prop('disabled', true);
                $('#campagne_select').prop('disabled', true);
            } else {
                $('#user_search').prop('disabled', false);
                $('#user_select').prop('disabled', false);
                $('#campagne_search').prop('disabled', false);
                $('#campagne_select').prop('disabled', false);
            }
        });

    
    $('#user_select').on('change', function() {
            if ($(this).val().length > 0) {
                $('input[type="checkbox"]').prop('disabled', true);
                $('#collapseOne').addClass('disabled-accordion');
                $('#campagne_search').prop('disabled', true);
                $('#campagne_select').prop('disabled', true);
            } else {
                $('input[type="checkbox"]').prop('disabled', false);
                $('#collapseOne').removeClass('disabled-accordion');
                $('#campagne_search').prop('disabled', false);
                $('#campagne_select').prop('disabled', false);
            }
        });

        $('#campagne_select').on('change', function() {
            if ($(this).val().length > 0) {
                $('input[type="checkbox"]').prop('disabled', true);
                $('#collapseOne').addClass('disabled-accordion');
                $('#user_search').prop('disabled', true);
                $('#user_select').prop('disabled', true);
            } else {
                $('input[type="checkbox"]').prop('disabled', false);
                $('#collapseOne').removeClass('disabled-accordion');
                //$('#collapseTwo').removeClass('disabled-accordion');
                $('#user_search').prop('disabled', false);
                $('#user_select').prop('disabled', false);
            }
        });

        $('#user_search').on('input', function() {
            if ($(this).val().length > 0) {
                // Désactiver toutes les checkboxes
                $('input[type="checkbox"]').prop('disabled', true);
                // Ajouter la classe pour griser l'accordéon
                $('#collapseOne').addClass('disabled-accordion');
                $('#campagne_search').prop('disabled', true);
                $('#campagne_select').prop('disabled', true);
            } else {
                // Activer les checkboxes si le champ est vide
                $('input[type="checkbox"]').prop('disabled', false);
                // Retirer la classe pour rétablir l'accordéon
                $('#collapseOne').removeClass('disabled-accordion');
                $('#campagne_search').prop('disabled', false);
                $('#campagne_select').prop('disabled', false);
            }
        });

        $('#campagne_search').on('input', function() {
            if ($(this).val().length > 0) {
                // Désactiver toutes les checkboxes
                $('input[type="checkbox"]').prop('disabled', true);
                // Ajouter la classe pour griser l'accordéon
                $('#collapseOne').addClass('disabled-accordion');
                //$('#collapseTwo').addClass('disabled-accordion');
                $('#user_search').prop('disabled', true);
                $('#user_select').prop('disabled', true);
            } else {
                // Activer les checkboxes si le champ est vide
                $('input[type="checkbox"]').prop('disabled', false);
                // Retirer la classe pour rétablir l'accordéon
                $('#collapseOne').removeClass('disabled-accordion');
                //$('#collapseTwo').removeClass('disabled-accordion');
                $('#user_search').prop('disabled', false);
                $('#user_select').prop('disabled', false);
            }
        });

            // Gérer la suppression des inputs et activer les checkboxes
        $('#input_container').on('click', '.remove-input', function() {
                // Supprimer l'élément parent de l'input
            $(this).closest('.input-group').remove();

                // Vérifier le nombre d'éléments restants
            const remainingInputs = $('#input_container .input-group').length;

                // Si des éléments restent, activer tous les checkboxes
            if (remainingInputs > 0) {
                $('input[type="checkbox"]').prop('disabled', true);
                $('#collapseOne').removeClass('disabled-accordion');
                $('#collapseThree').removeClass('disabled-accordion');
                $('#campagne_search').prop('disabled', true);
                $('#campagne_select').prop('disabled', true);
            } else {
                    // Si aucun élément ne reste, désactiver les checkboxes
                $('input[type="checkbox"]').prop('disabled', false);
                $('#collapseOne').removeClass('disabled-accordion');
                $('#campagne_search').prop('disabled', false);
                $('#campagne_select').prop('disabled', false);
            }
        });


        $('#input_campagne').on('click', '.remove-input-campagne', function() {
            // Supprimer l'élément parent de l'input
            $(this).closest('.input-group').remove();
            
            const remainingInputs_campagne = $('#input_campagne .input-group').length;

            if(remainingInputs_campagne > 0) {
                $('input[type="checkbox"]').prop('disabled', true);
                $('#collapseOne').removeClass('disabled-accordion');
                $('#collapseTwo').removeClass('disabled-accordion');
                $('#user_search').prop('disabled', true);
                $('#user_select').prop('disabled', true);
            } else {
                $('input[type="checkbox"]').prop('disabled', false);
                $('#collapseOne').removeClass('disabled-accordion');
                $('#user_search').prop('disabled', false);
                $('#user_select').prop('disabled', false);
            }
        });
    });
    /**Fin fonction pour metre disable les checkbox et l''input recherche */
</script>


<script src="<?= base_url('assets/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/adminlte.min.js?v=3.2.0'); ?>"></script>
<script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js'); ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<style>
.toast {
    background-color: #28a745; /* Couleur de fond vert */
    color: white; /* Couleur du texte */
    padding: 15px;
    border-radius: 5px;
}
.disabled-accordion {
    opacity: 0.5; /* Diminue l'opacité pour donner un effet grisâtre */
    pointer-events: none; /* Désactive les interactions avec l'accordéon */
}
.toast-danger {
    background-color: red; /* ou une autre couleur de ton choix */
    color: white; /* pour le texte */
}
.toast-succes {
    background-color: green; /* ou une autre couleur de ton choix */
    color: white; /* pour le texte */
}
.card-header{
    background-color: #33A8FF; 
}

</style>
</body>
</html>



<script>
       /* $(document).ready(function() {
                var username = $('#userInfo').data('username');
                console.log(username); // Affiche la valeur dans la console
                $.ajax({
                    url: '<?php echo site_url('message/find_Exped'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data : {
                        username_msg : username,                    
                    },
                    success: function(response) {
                        //console.log(response.data)
                        user_nom = response.data;

                        $.each(user_nom, function(index, nom) {
                            userID = nom.usr_id;
                            userNom =nom.usr_nom +' '+ nom.usr_prenom//nom.usr_id;
                            //console.log(userName);
                        })   
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        console.error('An error occurred:', error);
                    }
                });
                
            });*/
                /**fonction pour envoyer un message */   
            /*$('#myButton').click(function () {
                const inputValues = [];

                // Collecter les valeurs des inputs
                var remainingIds = [];
                $('#input_container .user-id').each(function() {
                    remainingIds.push($(this).val());
                });

                // Afficher les valeurs collectées dans la console (pour vérification)
                //console.log("Valeurs à insérer :", remainingIds);

                var user_expediteur = <?=  $this->session->userdata('user')['id'];?>;//id_de l'expediteure
                var user_expediteur_nom = '<?= $this->session->userdata('user')['nom'] . ' ' . $this->session->userdata('user')['prenom']; ?>';
                var selectedCheckboxes = $('input[id=role_msg]:checked');
                var selectedValues = selectedCheckboxes.map(function() {
                return $(this).val();
                }).get();

                var roles = selectedValues;
                var user_destinataire = remainingIds;
                var objet = $('#objet_msg').val();
                var message = $('#message_msg').val();
                var date = $('#date_msg').val();
                var file = $('#file_msg').val();                   

                //console.log('Utilisation de user_nom:', user_nom);
                //console.log(user_expediteur);

                // Vérifier si le message est vide
                if (!message.trim()) {
                    //console.log("Le message ne peut pas être vide");  
                    // Afficher le toast pour informer l'utilisateur
                    $('#toast').addClass('toast-danger'); // Ajoute la classe de danger
                    $('#toast').text('Le message ne peut pas être vide.'); // Message de toast 
                    $('#toast').fadeIn().delay(3000).fadeOut();
                    $('#toast').toast({ delay: 3000 }); // Initialiser avec un délai
                    $('#toast').toast('show'); // Afficher le toast
                } else {
                    $.ajax({
                        url: '<?php echo site_url('message/insertMessage'); ?>',
                        type: 'POST',
                        data: {
                            userInfo: user_expediteur,
                            expediteur_nom: user_expediteur_nom,
                            role_msg: roles,
                            select_users_specifique: user_destinataire,
                            objet_msg: objet,
                            message_msg: message,
                            date_msg: date,
                            file_msg: file,
                        },
                        success: function(response) {
                            // Vider les inputs
                            $('input[id=role_msg]:checked').prop('checked', false);
                            $('#input_container').empty();
                            $('#objet_msg').val('');
                            $('#message_msg').val('');
                            $('#date_msg').val('');
                            $('#file_msg').val('');

                            // Activer les champs des accordions
                            $('#user_search').prop('disabled', false);
                            $('#user_select').prop('disabled', false);
                            // Active les checkboxes si nécessaire
                            $('input[id=role_msg]').prop('disabled', false);

                            // Afficher le toast pour confirmation
                            $('#toast').text('Message envoyé avec succès.'); // Message de confirmation
                            $('#toast').fadeIn().delay(3000).fadeOut();
                            $('#toast').toast({ delay: 3000 }); // Initialiser avec un délai
                            $('#toast').toast('show'); // Afficher le toast
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            console.error('An error occurred:', error);
                        }
                    });
                }
            });*/
                // Récupérer les valeurs sélectionnées
    /*var selectedCheckboxes = $('input[id=role_msg]:checked');

    var selectedValues = selectedCheckboxes.map(function() {
      return $(this).val();
    }).get();

    var roles = selectedValues;
    

    $.ajax({
        url: '<?php echo site_url('message/handleClick'); ?>',
        type: 'POST',
        dataType: 'json',
        data : {         
            role_msg : roles,
        },
        success: function(response) {
            alert(response.message);
            console.log(response.message ,response.data);
        },
        error: function(xhr, status, error) {
            alert(error);
            console.error('An error occurred:', error);
        }
    });*/
            /*$(document).ready(function() {
            // Récupérer la valeur de l'attribut data
            var username = $('#userInfo').data('username');
            console.log(username); // Affiche la valeur dans la console
            $.ajax({
                    url: '<?php echo site_url('message/find_Exped'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data : {
                        username_msg : username,
                    },
                    success: function(response) {
                        alert(response.message);
                        console.log(response.data)
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        console.error('An error occurred:', error);
                    }
                });
            });*/

                /*$(document).ready(function() {
        $('#getValues').click(function() {
            const inputValues = [];
             // Collecter les valeurs des inputs
            $('#input_container input').each(function() {
                inputValues.push($(this).val());
            });
                // Afficher les valeurs collectées dans la console (pour vérification)
                //console.log("Valeurs à insérer :", inputValues);
                var user_destinataire = inputValues;
                //console.log(user_destinataire);
            $.ajax({
                url: '<?php echo site_url('message/test2'); ?>',
                type: 'POST',
                dataType: 'json',
                data : {
                    select_users_specifique : user_destinataire,                  
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    console.error('An error occurred:', error);
                }
            });
        });
    });*/
</script>