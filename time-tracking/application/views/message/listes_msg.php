<section class="content">
<div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card-header">
                <h3 class="card-title"><span class="initial" id="initial"></span><span id="username"></span></h3>
            </div>  
            <div class="list-group " style="height: 740px; overflow-y: scroll;">    
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status" id="spinner">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="container" style="heigt: 300px" class="messages"></div>
            </div>
        </div>

        <div class="col-md-9">
            <!--<div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Elément envoyé</h3>
                </div>
        
            <div class="table-responsive mailbox-messages">
        
                <div id="notification-container"></div>
                <div id="success-message" class="alert alert-success" style="display: none;"></div>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status" id="spinnere">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table class="table table-hover table-striped">
                    <tbody>
                        <div id="container" style="heigt: 300px"></div>
                         Les lignes de messages seront ajoutées ici dynamiquement
                    </tbody>
                </table> -->
             
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Elément envoyé</h3>
                    </div>
                        <div class="card-body p-0"  style="height: 700px; overflow-y: scroll;">
                            <div id="spinnerOne" style="display:none;">
                                <button class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                    <span role="status">Chargement...</span>
                                </button>
                            </div> 

                            <div class="mailbox-read-message ml-3">
                                <div id="messageDisplay">
                                                        
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="bouton_suppression"></div>
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
        url: '<?php echo site_url('message/select_message_user'); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            user_id: user_id, 
        },
        success: function(response) {
             console.log(response.data);
             var msg = response.data;
            if (msg.length === 0) {
                $('#container').append('<div class="alert alert-info">Aucun message personnelle à afficher.</div>');
            } else {
                    $.each(msg, function(index, info) {
       
                        $('#container').append(`
                            <div>
                                 <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                    <button type="button" class="btn position-relative messagesuser messageButton" id="expediteur-${info.message_user}" 
                                            value="${info.message_user}"
                                            data-destinatair ="${info.message_user}"
                                            data-message_id="${info.message_id}" 
                                            data-expediteur="${info.message_expediteur_id}"
                                            data-message-date="${info.message_date}"
                                    > 
                                        <span id="messageButton"></span>
                                        
                                        <span>
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


                                                            
                                    </button>
                                    <div class="container">
                                        <span>Objet: ${info.message_objet}</span>
                                    </div>
                                    <div class="container">
                                        <span id="limitedMessage">Message: ${info.message_message}</span> <!-- Limite le message ici -->
                                    </div>
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
    console.log(message_id);
    console.log(message_destinatair);
    $('#messageDisplay').empty();
    //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
    $.ajax({
        url:'<?php echo site_url('message/select_message_send'); ?>',
        type:'POST',
        dataType: 'json',                                    
        data : {
            expediteurID : <?=  $this->session->userdata('user')['id'];?>,      
            messageID : message_id,
            messageDESTINATAIR : message_destinatair,
        },
        success: function(response) {
            console.log(response.data);
            var message = response.data;
            $('#messageDisplay').empty();
   
            $.each(message, function(index, msg){
                $('#bouton_suppression').empty();
                $('#messageDisplay').append(`
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5>Objet:${msg.message_objet}</h5>
                            <h6>Destinatair:
                                <span>
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
                    </div>
                `)
                $('#bouton_suppression').append(`
                 <button type="button" class="btn btn-default supprimer_msg" id="${msg.message_id}" data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</button> 
                `)
            })
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
$(document).ready(function(){
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


})     

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

$(document).on('click',  '.confirmer_modifier', function() {
       // Récupérer l'ID du bouton cliqué
    var messageId = $(this).data('message-id');
    var message = $('#message-text').val();
    var objet = $('#message-objet').val();

    // Afficher la valeur dans la console (ou faire autre chose avec)
    console.log('message_id :', messageId);
    console.log('Nouveau message :', message);
  
  
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
            console.log(response.message);
               
                    // Afficher le message de succès
                $('#success-message').text(response.message).fadeIn().delay(3000).fadeOut(); // Affiche le message pendant 3 secondes
                $('#ModalMODIF').modal('hide');
                location.reload();window.load();
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX : ' + error);
        }
    }); 
});
/**Fin du fonction modification */


/**fonction pour supprimer une message*/
$(document).on('click',  '.supprimer_msg', function() {
       // Récupérer l'ID du bouton cliqué
    var messageId = $(this).attr('id');

// Afficher la valeur dans la console (ou faire autre chose avec)
console.log('message_id :', messageId);
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
                console.log(response.message);
                    // Mettre à jour l'interface : enlever l'élément du DOM
                $(`#message-${messageId}`).remove(); // Assurez-vous que chaque message a un ID unique

                // Afficher un message de succès
                const successMessage = $('<div class="alert alert-success" role="alert">Message supprimé avec succès !</div>');
                $('#notification-container').append(successMessage);

                // Optionnel : Masquer le message après quelques secondes
                setTimeout(() => {
                    successMessage.fadeOut();
                }, 3000);

                // Fermer la modale après le succès
                $('#exampleModal').modal('hide');
                location.reload();
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

                    /*$.each(user, function(index, usr) {
                        usr_id = usr.usr_id;
                        usr_role = usr.usr_role;
                        //console.log(usr_id);

                       
                        $('#spinner').show();
                           
                            $.ajax({
                                url: '<?php echo site_url('message/get_message'); ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    userId: usr_id,
                                    usrRole: usr_role,
                                },
                                success: function(response) {
                                    console.log(response.messages); // Affiche les messages
                                    infosmg = response.messages;
                                    role_msg = response.messages_role;
                                    console.log(role_msg);
                                    
                                    //nb_msg = response.message_counts;

                                    // Vérifier si les messages sont vides
                                    if (infosmg.length === 0) {
                                        $('#container').append('<div class="alert alert-info">Aucun message personnelle à afficher.</div>');
                                    } else {
                                        $.each(infosmg, function(index, info) {
                                            var usr_mane = info.message_expediteur_id;
                                           
                                            // Fonction pour limiter le message à 2 mots
                                            function limitMessage(msg) {
                                                const words = msg.split(' ');
                                                return words.slice(0, 2).join(' ');
                                            }

                                            $('#container').append(`
                                                <div>
                                                    <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                                        <button type="button" class="btn position-relative expediteur2 messageButton" id="expediteur-${info.message_expediteur_id}" 
                                                            value="${info.message_expediteur_id}"
                                                            data-message_id="${info.message_id}" 
                                                            data-message="${info.message_message}" 
                                                            data-objet="${info.message_objet}" 
                                                            data-expediteur="${info.message_expediteur_id}"
                                                            data-message-date="${info.message_date}"
                                                            > 
                                                            <span id="messageButton"></span>
                                                            <span>${info.message_expediteur_name}</span>
                                                            

                                                            <!-- Ajout de la badge -->
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg" style="display: ${info.message_status == 0 ? 'block' : 'none'};">
                                                            <span class="visually-hidden">unread messages</span>
                                                        </span>


                                                        </button>
                                                        <div class="container">
                                                            <span>Objet: ${limitMessage(info.message_objet)}</span>
                                                        </div>
                                                        <div class="container">
                                                            <span id="limitedMessage">Message: ${limitMessage(info.message_message)}</span> <!-- Limite le message ici -->
                                                        </div>
                                                    </a>
                                                </div>
                                            `);
                                        });
                                    }

                                    // Affichage message rôle de l'utilisateur dans l'id #container_id
                                    if (role_msg.length === 0) {
                                        $('#container_id').append('<div class="alert alert-info">Aucun message à afficher pour votre rôle.</div>');
                                    } else {
                                        $.each(role_msg, function(index, info) {
                                            var usr_mane = info.message_expediteur_id;
                                            console.log(info.message_status);
                                            // Fonction pour limiter le message à 2 mots
                                            function limitMessage(msg) {
                                                const words = msg.split(' ');
                                                return words.slice(0, 2).join(' ');
                                            }

                                            $('#container_id').append(`
                                                <div>
                                                    <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                                        <button type="button" class="btn position-relative expediteur messageButton" id="expediteur-${info.message_expediteur_id}" 
                                                            value="${info.message_expediteur_id}" 
                                                            data-message_id="${info.message_id}"
                                                            data-message="${info.message_message}" 
                                                            data-objet="${info.message_objet}" 
                                                            data-expediteur="${info.message_expediteur_id}"
                                                            data-message-date="${info.message_date}"
                                                        >
                                                            <span id="messageButton"></span>
                                                            <span>${info.message_expediteur_name}</span>

                                                            <!-- Ajout de la badge -->
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg" style="display: ${info.message_status == 0 ? 'block' : 'none'};">
                                                            <span class="visually-hidden">unread messages</span>
                                                        </span>


                                                        </button>
                                                        <div class="container">
                                                            <span>Objet: ${limitMessage(info.message_objet)}</span>
                                                        </div>
                                                        <div class="container">
                                                            <span id="limitedMessage">Message: ${limitMessage(info.message_message)}</span> <!-- Limite le message ici -->
                                                        </div>
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
                            });

                    })*/
                        
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