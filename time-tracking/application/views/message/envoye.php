
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
            <form action="<?php echo site_url('message/insertMessage'); ?>" method="post"></form>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Composé un message</h3>
                    </div>
                    <div id="toast" class="toast" style="display:none; position: fixed; bottom: 20px; right: 20px;">
                        <div class="toast-body">
                            Message envoyé avec succès !
                        </div>
                    </div>  
                    <div class="card-body">
                        <div class="form-group">
                            <div>
                                <label for="destinataire">Destinataire</label>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="accordion col-md-6"  id="accordionExample">
                                        <div class="accordion-item" >
                                            <h2 class="accordion-header">
                                                <button  class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Role
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show ml-3" data-coreui-parent="#accordionExample">

                                                <div class="form-check">
                                                    <?php foreach ($listRole as $role) { ?>
                                                        <div>
                                                            <input class="form-check-input" type="checkbox" value="<?php echo $role->role_id ?>" id="role_msg" name="expediteur[]">
                                                            <label for="<?php echo $role->role_libelle?>">
                                                                <?php echo $role->role_libelle?>
                                                            </label>   
                                                        </div>
                                                    <?php } 
                                                    ?>
                                                </div> 

                                            </div>
                                        </div>
                                        
                                     </div>
                                     <div class="accordion col-md-6" style="width: 50%; " id="activite">
                                        <div class="accordion-item" >
                                            <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
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
                                                <!--<input type="text" id="selected_users" class="form-control" placeholder="Sélectionnés" readonly>-->
                                                <div id="input_container"></div> <!-- Conteneur pour les nouveaux inputs -->                                            
                                            </div>
                                        </div>
                                     </div>
         
                               
                    
                            </div>

                           
                         </div>

                        <div class="form-group mt-3">
                            <input class="form-control" placeholder="Objet :"  id="objet_msg">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="message_msg" placeholder="Message :" rows="3" ></textarea>
                        </div>
                        <div class="form-group row t-2">
                            <label for="edit_user_dateembauche" class="col-sm-2 col-form-label"
                            >Date </label
                            >
                            <div class="col-sm-10">
                            <input
                                type="date"
                                name="date_msg"
                                class="form-control"
                                id="date_msg"
                                placeholder=""
                                value=""
                            />
                            </div>
                        </div>

                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input class="form-control" type="file" id="file_msg" multiple name="fichiers[]">
                            </div>
                            <button type="button" id="uploadBtn" class="btn btn-primary">Upload</button>
                        </form>
                        <div id="message"></div>

                        <div class="card-footer">
                            <div class="float-right">
                            
                                <button type="submit" id="myButton"  class="btn btn-primary" ><i class="fa fa-paper-plane"></i></button>
                            </div>
                                <!--<button id="getValues">Get Selected Values</button>-->
                        </div>

                    
                    </div>

                </div>

             </form>


            <div id="message"></div>
 
            </div>

    </div>
<div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
</section> 
        <!--Formulaire avec le fichier-->
<!--<section class="content">
    <div class="container-fluid">    
        <div class="col-md-100">
            <form  method="post" id="mainForm" enctype="multipart/form-data">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Composé un message</h3>
                    </div>
                    <div id="toast" class="toast" style="display:none; position: fixed; bottom: 20px; right: 20px;">
                        <div class="toast-body">
                            Message envoyé avec succès !
                        </div>
                    </div>  
                    <div class="card-body">
                        <div class="form-group">
                            <label for="destinataire">Destinataire</label>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="accordion col-md-6" id="accordionExample">
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
                                    <div class="accordion col-md-6" style="width: 50%;" id="activite">
                                        <div class="accordion-item">
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
                                                <div id="input_container"></div>  Conteneur pour les nouveaux inputs                                              
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
                            <label for="edit_user_dateembauche" class="col-sm-2 col-form-label">Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date_msg" class="form-control" id="date_msg" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <input class="form-control" type="file" id="file_msg" multiple name="fichiers[]">
                        </div>

                        <div id="message"></div>

                        <div class="card-footer">
                            <div class="float-right">
                                <button type="button" id="myButton" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Envoyer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
</section>-->

<script>
    console.log('<?=  $this->session->userdata('user')['id'];?>');
        // Obtenir la date d'aujourd'hui au format YYYY-MM-DD
        const today = new Date().toISOString().split('T')[0];
        // Assigner cette date à l'input
        document.getElementById('date_msg').value = today;

        var user_nom = [];
        var userName = '';
             
            $(document).ready(function() {
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
                
            });
                /**fonction pour l'envoyer du message */   
            $('#myButton').click(function () {
                const inputValues = [];

                // Collecter les valeurs des inputs
                var remainingIds = [];
                $('#input_container .user-id').each(function() {
                    remainingIds.push($(this).val());
                });

                // Afficher les valeurs collectées dans la console (pour vérification)
                //console.log("Valeurs à insérer :", remainingIds);

                var user_expediteur = userID;//id_de l'expediteure
                var user_expediteur_nom = userNom; 
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
                                   
                console.log('Utilisation de user_nom:', user_nom);
                console.log(user_expediteur);
                             
                    $.ajax({
                        url: '<?php echo site_url('message/insertMessage'); ?>',
                        type: 'POST',
                        data : {
                            userInfo : user_expediteur,
                            expediteur_nom : user_expediteur_nom,
                            role_msg : roles,
                            select_users_specifique : user_destinataire,
                            objet_msg : objet,
                            message_msg : message,
                            date_msg : date,
                            file_msg : file,
                        },
                        success: function(response) {
                            //console.log(response);

                                // Vider les inputs
                               
                                $('input[id=role_msg]:checked').prop('checked', false);
                                $('#input_container').empty();
                                $('#objet_msg').val('');
                                $('#message_msg').val('');
                                $('#date_msg').val('');
                                $('#file_msg').val('');

                                // Afficher le toast
                                $('#toast').fadeIn().delay(3000).fadeOut();
                                $('#toast').toast({ delay: 3000 }); // Initialiser avec un délai
                                $('#toast').toast('show'); // Afficher le toast
                          
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            console.error('An error occurred:', error);
                        }
                    });
                });

                /**fonction ajax avec le fichier */
                /*$(document).ready(function() {
                    $('#myButton').click(function () {
                        var formData = new FormData($('#mainForm')[0]); // Utiliser le formulaire principal

                        // Collecter les valeurs des inputs
                        var remainingIds = [];
                        $('#input_container .user-id').each(function() {
                            remainingIds.push($(this).val());
                        });

                        var user_expediteur = userID; // id de l'expéditeur
                        var user_expediteur_nom = userNom; 
                        var selectedCheckboxes = $('input[id=role_msg]:checked');
                        var selectedValues = selectedCheckboxes.map(function() {
                            return $(this).val();
                        }).get();

                        // Ajouter des données supplémentaires à formData
                        formData.append('userInfo', user_expediteur);
                        formData.append('expediteur_nom', user_expediteur_nom);
                        formData.append('role_msg', JSON.stringify(selectedValues)); // Convertir en JSON
                        formData.append('select_users_specifique', JSON.stringify(remainingIds)); // Convertir en JSON
                        console.log(Array.from(formData.entries())); 

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

                                // Afficher le toast
                                $('#toast').fadeIn().delay(3000).fadeOut();
                                $('#toast').toast({ delay: 3000 });
                                $('#toast').toast('show');
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                console.error('Une erreur s\'est produite:', error);
                            }
                        });
                    });
                });*/
                /** Fin fonction ajax avec le fichier */
            

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
        
</script>

<script>
            var userLists = [];
            
            $.ajax({
                 url: '<?= site_url('message/seacrch') ?>',
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
                        userList.usr_matricule.toLowerCase().includes(userInput)
                    );
                    console.log(userInput);
                });

                populateSelect(filteredUserList); // Réafficher les suggestions filtrées
            });
            $(document).on('change', '#user_select', function() {
            var selectedOptions = $(this).val(); // Récupérer les valeurs sélectionnées
            $('#selected_users').val(selectedOptions ? selectedOptions.join(', ') : ''); // Mettre à jour le champ de texte

    });


    $(document).on('change', '#user_select', function() {
    var selectedOptions = $(this).find('option:selected'); // Récupérer les options sélectionnées
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
    
</script>

<script>
    $(document).ready(function() {
        $('#getValues').click(function() {
            const inputValues = [];
             // Collecter les valeurs des inputs
            $('#input_container input').each(function() {
                inputValues.push($(this).val());
            });

                // Afficher les valeurs collectées dans la console (pour vérification)
                console.log("Valeurs à insérer :", inputValues);

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
   
    });

    $(document).ready(function() {
        $('#uploadBtn').click(function() {
            var formData = new FormData($('#uploadForm')[0]);
            $.ajax({
                url: '<?php echo site_url("message/upload_files"); ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#message').html(response); // Affiche le message de réponse
                },
                error: function(xhr, status, error) {
                    $('#message').html('Une erreur s\'est produite : ' + error);
                }
            });
        });
    });
$(document).ready(function() {
    // Fonction pour gérer l'état des checkboxes
    $('input[type="checkbox"]').on('change', function() {
        // Vérifier si au moins une checkbox est sélectionnée
        if ($('input[type="checkbox"]:checked').length > 0) {
            // Désactiver le champ de recherche et le select
            $('#user_search').prop('disabled', true);
            $('#user_select').prop('disabled', true);
        } else {
            // Activer le champ de recherche et le select
            $('#user_search').prop('disabled', false);
            $('#user_select').prop('disabled', false);
        }
    });

  // Gérer l'état des éléments de recherche
  $('#user_select').on('change', function() {
        if ($(this).val().length > 0) {
            // Désactiver toutes les checkboxes
            $('input[type="checkbox"]').prop('disabled', true);
            // Ajouter la classe pour griser l'accordéon
            $('#collapseOne').addClass('disabled-accordion');
        } else {
            // Activer les checkboxes si aucune sélection n'est faite
            $('input[type="checkbox"]').prop('disabled', false);
            // Retirer la classe pour rétablir l'accordéon
            $('#collapseOne').removeClass('disabled-accordion');
        }
    });

    // Gérer l'état du champ de recherche
    $('#user_search').on('input', function() {
        if ($(this).val().length > 0) {
            // Désactiver toutes les checkboxes
            $('input[type="checkbox"]').prop('disabled', true);
            // Ajouter la classe pour griser l'accordéon
            $('#collapseOne').addClass('disabled-accordion');
        } else {
            // Activer les checkboxes si le champ est vide
            $('input[type="checkbox"]').prop('disabled', false);
            // Retirer la classe pour rétablir l'accordéon
            $('#collapseOne').removeClass('disabled-accordion');
        }
    });
});
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
</style>
</body>
</html>

