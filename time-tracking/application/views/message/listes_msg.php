<section class="content">
    <div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <!--<span class="initial" id="initial"></span>-->
                            <span class="card-title text-white" id="username"></span>
                        </h3>
                    </div>
                    <div class="list-group" style="height: 740px; overflow-y: scroll;">
                        <div id="container" style="height: 300px;" class="messages"></div>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status" id="spinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title text-white">Élément envoyé</h3>
                    </div>
                    <div class="card-body p-0" style="height: 700px; overflow-y: scroll;">
                        <div id="notification-container"></div>
                        <div id="success-message" class="alert alert-success" style="display: none;"></div>
                        <div id="spinnerOne" style="display:none;"  class="position-absolute top-50 start-50 translate-middle">
                            <button class="btn btn-primary" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                <span role="status">Chargement...</span>
                            </button>
                        </div>

                        <div class="mailbox-read-message ml-3">
                            <div id="messageDisplay"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <div class="inline" id="user_lus"></div>
                            <div class="inline" id="user_nonlus"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    //console.log('<?=  $this->session->userdata('user')['id'];?>');
     var username = $('#userInfo').data('username');
     var user_id = <?=  $this->session->userdata('user')['id'];?>;
     //console.log(username); // Affiche la valeur dans la console

$(document).ready(function(){
    $('#spinner').show();
    $.ajax({
        url: '<?php echo site_url('message/get_message_send_user'); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            user_id: user_id, 
        },
        success: function(response) {
            //console.log(response.data);
            var msg = response.data;
            if (msg.length === 0) {
                $('#container').append('<div class="alert alert-info">Aucun message envoyé</div>');
            } else {
                    $.each(msg, function(index, info) {
       
                        $('#container').append(`
                            <div>
                                <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                    <button type="button" class="btn btn-light w-100 text-start position-relative  messagesuser messageButton" id="expediteur-${info.message_user}" 
                                            value="${info.message_user}"
                                            data-destinatair ="${info.message_user}"
                                            data-message_id="${info.message_id}" 
                                            data-expediteur="${info.message_expediteur_id}"
                                            data-message-date="${info.message_date}"
                                            data-message_role="${info.message_role_id}"
                                > 
                                        <span id="messageButton"></span>
                                        
                                        <span class="fs-5">
                                            ${info.campagne_libelle != null ? info.campagne_libelle : '' }
                                            ${info.usr_nom != null && info.usr_prenom != null ? info.usr_nom + ' ' + info.usr_prenom : ''}
                                            ${info.message_role_id == 1 ? 'Admin' : 
                                            info.message_role_id == 2 ? 'Agent' : 
                                            info.message_role_id == 3 ? 'Superviseur' : 
                                            info.message_role_id == 4 ? 'Cadre' : 
                                            info.message_role_id == 5 ? 'Admin_rh' : 
                                            info.message_role_id == 6 ? 'Cadre2' : 
                                            info.message_role_id == 7 ? 'Direction' : 
                                            info.message_role_id == 8 ? 'Costrat' : 
                                            info.message_role_id == 9 ? 'Client' : 
                                            info.message_role_id == 10 ? 'Reporting' : ''}
                                        </span>
                                                    
                                    <div class="container">
                                        <span>Objet: ${info.message_objet}</span>
                                    </div>
                                    <div class="container">
                                        <span id="limitedMessage">Message: ${info.message_message}</span> <!-- Limite le message ici -->
                                    </div>
                                    </button>
                                </a>
                            </div>
                        `);
                    });
                }
        },
        error: function(xhr, status, error) {
        console.error('An error occurred:', error);
        },
        complete: function() {
                // Masquer le spinner une fois la requête terminée (qu'elle soit réussie ou non)
            $('#spinner').hide();
        }
    })
})

/**Bouton clicke pour afficher les messages pour le Roles*/
$(document).on('click', '.messagesuser', function() {
   
    $('#spinnerOne').show();
    var message_id =  $(this).data('message_id');
    var message_destinatair =  $(this).data('destinatair');
    var message_role =  $(this).data('message_role');
    //console.log(message_id);
    //console.log(message_destinatair);
    //console.log(message_role);
    
    $('#messageDisplay').empty();
    //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
    $.ajax({
        url:'<?php echo site_url('message/getall_message_send'); ?>',
        type:'POST',
        dataType: 'json',                                    
        data : {
            expediteurID : <?=  $this->session->userdata('user')['id'];?>,      
            messageID : message_id,
            messageDESTINATAIR : message_destinatair,
            messageROLEID : message_role,
        },
        success: function(response) {
            //console.log(response.lists_msg);
            //console.log(response.messages_lus);
            //console.log(response.messages_non_lus);
            var base_url = '<?php echo base_url('uploads/'); ?>';
            var message = response.lists_msg;
            var userLus = response.messages_lus;
            var userNonLus = response.messages_non_lus;
            $('#messageDisplay').empty();
            $('#modal_user_lus').remove(); 
            $('#modal_user_nonlus').remove();
            $.each(message, function(index, msg){
                //var nomDuFichier = msg.message_fichier_path.split('\\').pop().split('/').pop();
                if (msg.message_fichier_path) {
                    var nomDuFichier = msg.message_fichier_path.split('\\').pop().split('/').pop();
                } else {
                    var nomDuFichier = '';
                }
                $('#bouton_suppression').empty();
                $('#user_lus').empty();
                $('#user_nonlus').empty();
                $('#messageDisplay').append(`
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5>Objet:${msg.message_objet}</h5>
                            <h6>Destinatair:
                                <span>
                                    ${msg.campagne_libelle != null ? msg.campagne_libelle : '' }
                                    ${msg.usr_nom != null && msg.usr_prenom != null ? msg.usr_nom + ' ' + msg.usr_prenom : ''}
                                    ${msg.message_role_id == 1 ? 'Admin' : 
                                    msg.message_role_id == 2 ? 'Agent' : 
                                    msg.message_role_id == 3 ? 'Superviseur' : 
                                    msg.message_role_id == 4 ? 'Cadre' : 
                                    msg.message_role_id == 5 ? 'Admin_rh' : 
                                    msg.message_role_id == 6 ? 'Cadre2' : 
                                    msg.message_role_id == 7 ? 'Direction' : 
                                    msg.message_role_id == 8 ? 'Costrat' : 
                                    msg.message_role_id == 9 ? 'Client' : 
                                    msg.message_role_id == 10 ? 'Reporting' : ''}
                                </span>    
                            <span class="mailbox-read-time float-right">${msg.message_date}</span></h6>
                        </div> 
                        <p ><span class="ml-4 mt-4" style="height: 100px" id="messagedisplay">${msg.message_message}</span></p>
                                                <div class="mailbox-attachment-info">
                            ${nomDuFichier ? `
                                <a href="${base_url}${nomDuFichier}" class="mailbox-attachment-name" target="_blank">
                                    <i class="fa fa-paperclip"></i> ${nomDuFichier}
                                </a>
                            ` : `
                                <div></div>
                            `}
                        </div>                   
                    </div>
                `)
                $('#bouton_suppression').append(`
                 <button type="button" class="btn btn-default supprimer_msg" id="${msg.message_id}" data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</button> 
                `)

                    // Ajouter le contenu au tableau de la modal lors de l'ouverture
            $('#user_nonlus').empty();
            $('#user_nonlus').append(`
            ${msg.campagne_libelle != null || msg.usr_nom != null && msg.usr_prenom != null ?  
                `
                    <div></div>
                `
                :
                `
                    <div>
                        <i class="fa fa-low-vision" aria-hidden="true"></i> Non lus ${userNonLus.length}
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal_user_nonlus">Voir</button>
                    </div>
                ` 
            }
  
            `);
            })
            let modalBodyContent = '';

            // Construire le contenu du tableau à partir de userNonLus
            $.each(userLus, function(index, msg) {
                modalBodyContent += `
                    <tr>
                        <td>${msg.usr_nom}</td>
                        <td>${msg.usr_prenom}</td>
                        <td>${msg.usr_matricule}</td> 
                    </tr>
                `;
            });

            // Ajouter le contenu au tableau de la modal lors de l'ouverture
            $('#user_lus').empty();      
            $('#user_lus').append(`
                <div>
                    <i class="fa fa-eye" aria-hidden="true"></i> lus ${userLus.length}
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal_user_lus">Voir</button>
                </div>
            `);
                    // Modal pour la suppression d'un message
                const modalHtml = `
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de Suppression</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer ce message ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="button" class="btn btn-primary confirmer_supprimer" >Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                // Ajouter la modal au DOM
                $('body').append(modalHtml);

                // Modal HTML
                const modalHtml2 = `
                <div class="modal fade" id="modal_user_lus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Les personnes qui ont lu votre message. <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search_user_lu"></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table" id="lus_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Prenom</th>
                                            <th scope="col">Matricule</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lus_tbody">
                                        ${modalBodyContent}
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Ajouter la modal au DOM (une seule fois)
            $('body').append(modalHtml2);
           
                // Fonction de recherche
            $('#search_user_lu').on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                
                // Filtrer les utilisateurs lus
                $('#lus_tbody tr').filter(function() {
                    $(this).toggle(
                        $(this).find('td').first().text().toLowerCase().indexOf(searchValue) > -1 || // Nom
                        $(this).find('td').eq(1).text().toLowerCase().indexOf(searchValue) > -1 || // Prénom
                        $(this).find('td').last().text().toLowerCase().indexOf(searchValue) > -1 // Matricule
                    );
                });
            });

            let modalBodyContent2 = '';

            // Construire le contenu du tableau à partir de userNonLus
            $.each(userNonLus, function(index, msg) {
                modalBodyContent2 += `
                    <tr>
                        <td>${msg.usr_nom}</td>
                        <td>${msg.usr_prenom}</td>
                        <td>${msg.usr_matricule}</td> 
                    </tr>
                `;
            });

        

                // Modal HTML
                const modalHtml3 = `
                <div class="modal fade" id="modal_user_nonlus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Les personnes qui n'ont pas lu votre message. <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search_user"></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table" id="non_lus_table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Prenom</th>
                                            <th scope="col">Matricule</th>
                                        </tr>
                                    </thead>
                                    <tbody id="non_lus_tbody">
                                        ${modalBodyContent2}
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Ajouter la modal au DOM (une seule fois)
            $('body').append(modalHtml3);

                // Fonction de recherche
            $('#search_user').on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                
                // Filtrer les utilisateurs non lus
                $('#non_lus_tbody tr').filter(function() {
                    $(this).toggle(
                        $(this).find('td').first().text().toLowerCase().indexOf(searchValue) > -1 || // Nom
                        $(this).find('td').eq(1).text().toLowerCase().indexOf(searchValue) > -1 || // Prénom
                        $(this).find('td').last().text().toLowerCase().indexOf(searchValue) > -1 // Matricule
                    );
                });
            });

        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
        complete: function() {
            // Cacher le spinner après la requête (qu'elle soit réussie ou en erreur)
            $('#spinnerOne').hide();
        }
    })
});

/**fonction pour modifier une message*/
$(document).on('click', '.modifier_msg', function() {
        // Récupérer le message depuis l'attribut de données
    const message = $(this).data('message');
    const objet = $(this).data('objet');
        // Mettre à jour le textarea avec le message
    $('#message-text').val(message);
    $('#message-objet').val(objet);

        // Récupérer l'ID du bouton cliqué
      var messageId = $(this).attr('id');

        // Afficher la valeur dans la console
    console.log('message_id :', messageId);
    $('.confirmer_modifier').data('message-id', messageId);
});

/**Debut fonction pour supprimer une message*/
$(document).on('click',  '.supprimer_msg', function() {
       // Récupérer l'ID du bouton cliqué
    var messageId = $(this).attr('id');

        // Afficher la valeur dans la console 
        //console.log('message_id :', messageId);
    $('.confirmer_supprimer').data('message-id', messageId);
});
// Gestion du clic sur le bouton de confirmation dans la modal
$(document).on('click', '.confirmer_supprimer', function() {
    const messageId = $(this).data('message-id');

     $.ajax({
        url: '<?= site_url('message/suppression') ?>',
        type: 'POST',
        dataType: 'json',
        data: { id_message : messageId },
        success: function(response) {
                //console.log(response.message);
                    // Mettre à jour l'interface : enlever l'élément du DOM
                $(`#message-${messageId}`).remove(); 

                // Afficher un message de succès
                const successMessage = $('<div class="alert alert-success" role="alert">Message supprimé avec succès !</div>');
                $('#notification-container').append(successMessage);

                // Optionnel : Masquer le message après quelques secondes
                setTimeout(() => {
                    successMessage.fadeOut();
                }, 3000);

                // Fermer la modale après le succès
                $('#exampleModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 300); 
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX : ' + error);
        }
    });  
});
/**Fin du fonction suppression */

$(document).ready(function() {
    var message = [];
    var userName = '';
                
    $(document).ready(function() {

        var username = $('#userInfo').data('username');
        console.log(username); // Affiche la valeur dans la console
        $.ajax({
            //url: '<?php echo site_url('message/find_user'); ?>',
            type: 'POST',
            dataType: 'json',
            data : {
                username_msg : username, 
            },
                    
            success: function(response) {
                //console.log(response.data)
                user = response.data;
                //console.log(message);
                        
            },
            error: function(xhr, status, error) {
                //alert(error);
                //console.error('An error occurred:', error);
            },            
        });
                  // Afficher la variable dans le span
        $('#username').text(username);
                   // Extraire la première lettre et l'afficher
        var firstLetter = username.charAt(0);
        $('#initial').text(firstLetter);     
    });
  
});
</script>
<style>
.initial {
    display: inline-block;
    width: 30px; 
    height: 30px;
    border-radius: 50%;
    background-color: #33A8FF; 
    color: white; 
    text-align: center;
    line-height: 30px; 
    font-weight: bold; 
    margin-right: 5px; 
}

/* En-tête de carte */
.card-header {
    background-color: #33A8FF; /* Couleur de fond */
    color: white; /* Couleur du texte */
}

/* Liste de messages */
.list-group {
    margin-top:8px;
    background-color: white; /* Fond blanc pour les messages */
    border-radius: 8px; /* Coins arrondis */
    box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Ombre légère */
}

/* Élément de message */
.messages {
    padding: 15px; /* Espacement intérieur */
}

/* Message au survol */
.messages .message-item:hover {
    background-color: #f5f5f5; /* Couleur de fond au survol */
}

/* Lecture de message */
.mailbox-read-message {
    padding: 15px; /* Espacement intérieur */
    line-height: 1.6; /* Hauteur de ligne pour un meilleur espacement */
}

/* Spinner */
.spinner-border {
    margin: 0 auto; /* Centrer le spinner */
}

/* Notifications */
.alert {
    margin: 10px 0; /* Espacement vertical */
}

/* Footer de la carte */
.card-footer {
    background-color: #f8f9fa; /* Fond gris clair */
    border-top: 1px solid #e0e0e0; /* Ligne supérieure */
}

/* Utilisateur lu/non lu */
.inline {
    margin-right: 10px; /* Espacement entre les éléments */
    font-weight: bold; /* Texte en gras */
    color: #555; /* Couleur du texte */
}

</style>



<script>
    /*$(document).ready(function(){
  // Afficher le spinner avant de faire la seconde requête
  $('#spinner').show();

$.ajax({
    //url: '<?php echo site_url('message/select_message_user'); ?>',
    type: 'POST',
    dataType: 'json',
    data: {
        user_id: user_id, 
    },
    success: function(response) {
        //console.log(response.data);
        var msg = response.data;

        if(msg === 0){
            $('#container').append('<div class="alert alert-info">Aucun message envoyer.</div>');
        }else{
                // Vider le corps de la table avant d'ajouter de nouvelles données
            const tbody = $('.table-responsive .table tbody');
            tbody.empty(); // Vider les anciennes lignes

                // Boucler à travers les données reçues
            response.data.forEach(function(message) {
                // Créer une nouvelle ligne pour chaque message
                const row = `
                    <tr>
                        <td class="mailbox-subject">
                            <b>${message.message_objet}</b> - ${message.message_message}
                        </td>
                        <td class="mailbox-attachment"></td>
                        <td class="mailbox-date">${message.message_date}</td>
                        <!--<td class="mailbox-attachment">
                            <button type="button" class="btn btn-primary modifier_msg" id="${message.message_id}" data-bs-toggle="modal" 
                                    data-bs-target="#ModalMODIF"
                                    data-objet="${message.message_objet}"
                                    data-message="${message.message_message}" 
                            >
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </td>-->
                        <td class="mailbox-attachment">
                            <button type="button" class="btn btn-danger supprimer_msg" id="${message.message_id}" data-bs-toggle="modal" 
                                    data-bs-target="#exampleModal"                                           
                            >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                `;

                // Ajouter la ligne au corps de la table
                tbody.append(row);
            
            });  
        }
  

                // Ajouter la modal au DOM
                $('body').append(modalHtml);

                // Modal pour le modification d'un message
                const modaMODIF = `
                    <div class="modal fade" id="ModalMODIF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier le Message</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="recipient-name" class="col-form-label">Objet:</label>
                                        <input type="text" class="form-control" id="message-objet">
                                    </div>
                                    <div class="mb-3">
                                        <label for="message-text" class="col-form-label">Message:</label>
                                        <textarea class="form-control" id="message-text"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="button" class="btn btn-primary confirmer_modifier">Sauvegarder</button>
                            </div>
                            </div>
                        </div>
                    </div>

                `;

                // Ajouter la modal au DOM
                $('body').append(modaMODIF);
    },
    error: function(xhr, status, error) {
        //console.error('An error occurred:', error);
    },
    complete: function() {
        // Masquer le spinner une fois la requête terminée (qu'elle soit réussie ou non)
        $('#spinner').hide();
    }
});


}) */    

/*$(document).ready(function() {
    $.ajax({
        url: '<?php echo site_url('message/find_user'); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            username_msg: username, 
        },
        success: function(response) {
            //console.log(response.data);
            user_id = response.data;
            $.each(user_id, function(index, nom) {
                userID = nom.usr_id;
            });

          
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
    });        
});*/

/*$(document).on('click',  '.confirmer_modifier', function() {
       // Récupérer l'ID du bouton cliqué
    var messageId = $(this).data('message-id');
    var message = $('#message-text').val();
    var objet = $('#message-objet').val();

    // Afficher la valeur dans la console (ou faire autre chose avec)
    //console.log('message_id :', messageId);
    //console.log('Nouveau message :', message);
  
  
    $.ajax({
        url: '<?= site_url('message/modification_msg') ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            id_message : messageId ,
            objet : objet, 
            message : message,
        },
        success: function(response) {
            //console.log(response.message);
               
                // Afficher le message de succès
            $('#success-message').text(response.message).fadeIn().delay(3000).fadeOut(); // Affiche le message pendant 3 secondes
            $('#ModalMODIF').modal('hide');
            location.reload();window.load();
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX : ' + error);
        }
    }); 
});*/
/**Fin du fonction modification */
</script>