
<style>
.initial {
    display: inline-block;
    width: 30px; /* Ajuste la taille */
    height: 30px; /* Ajuste la taille */
    border-radius: 50%; /* Bordure ronde */
    background-color: #33A8FF; /* Couleur de fond */
    color: white; /* Couleur du texte */
    text-align: center;
    line-height: 30px; /* Aligne le texte verticalement */
    font-weight: bold; /* Met en gras */
    margin-right: 5px; /* Espacement à droite */
}
#nb_msg {
    display: none;
}
#nb_msg2 {
    display: none;
}
.card-title {
    display: flex;
    align-items: center;
}


</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
                <!--Affichage des messages pour les specifique -->
            <div class="col-md-2">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="me-2 fs-6">Messages pour vous</span>
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </h3>
                </div>  
                <div class="list-group " style="height: 750px; overflow-y: scroll;">
                    <div id="container" style="heigt: 300px"></div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" id="spinner">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!--<div id="container_id" style="heigt: 300px"></div>-->
                </div>    
            </div>
                <!--Fin d'affichage des messages pour les specifique-->

                <!--Affichage des messages roles-->
            <div class="col-md-2">
                <div class="card-header">               
                    <?php if (isset($role_data)) : ?>
                        <h3 class="card-title d-flex align-items-center">
                            <span class="me-2 fs-6">Messages  à tous les
                                <?php foreach ($role_data as $role) : ?>
                                    <?php echo $role->role_libelle; ?>
                                <?php endforeach; ?>
                            </span>
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </h3>
                    <?php endif; ?>      
                </div>  
                <div class="list-group " style="height: 750px; overflow-y: scroll;">  
                    <div id="container_msg_specifique" style="heigt: 300px"></div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" id="spinner_id">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                        <!--<div id="container_id" style="heigt: 300px"></div>-->
                </div>    
            </div>
                <!--Fin affichage des messages roles-->

            <div class="col-md-8">
            <div class="card card-primary card-outline" >
                <div class="card-header">
                    <h3 class="card-title">Lire message</h3>
                </div>
                <div class="card-body p-0"  style="height: 750px; overflow-y: scroll;">
                        <div class="mailbox-read-message ml-3">                    
                            <div id="spinner1" style="display:none;">
                                <button class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                                    <span role="status">Chargement...</span>
                                </button>
                            </div>          
                            <div id="messageDisplay"></div> 
                            <div id="messageDisplay_specifique"></div> 
                            <div id="message_lus_Display"></div>  
                            <div id="message_non_lus_Display"></div>                     
                        </div>

                    </div>

                    <div id="userInfo" data-username="<?= $top['username'] ?>"></div> 
                </div>

            </div>
            <!--<button class="import_file">Importer le fichier</button>-->
            <div id="ton_element_html"></div>
        </div>

    </div>


<script>


var tab_message ='';
var message ='';
$(document).ready(function() {
    $('.expediteur').on('click', function() {
        var expediteurId = $(this).val(); // Récupérer la valeur du bouton
        console.log('ID de l\'expéditeur:', expediteurId);
            // Afficher la variable dans le span
        $('#nomexpediteur').text(expediteurId);
        var val = expediteurId;
        $.ajax({
            url: '<?php echo site_url('message/find_message'); ?>',
            type: 'POST',
            dataType: 'json',
            data : {
                expediteur : val,
            },
            success: function(response) {
                console.log(response.data);
                tab_message = response.data;

                    // Vider le conteneur avant d'afficher les nouveaux messages
                $('#message-container').empty();

                $.each(tab_message, function(index, msg) {
                    message = msg.message_message;
                    console.log(message);
                        // Créer un élément HTML pour chaque message
                    var messageElement = $('<div class="message"></div>').text(message);
                        // Ajouter le nouvel élément au conteneur
                    $('#message-container').append(messageElement);
                })
            },
            error: function(xhr, status, error) {
                    //alert(error);
                console.error('An error occurred:', error);
            }           
        });       
    });   
});


$(document).ready(function() {
    // Fonction pour limiter le nombre de mots
    function limitWords(text, maxWords) {
        const words = text.split(' ');
        const limited = words.slice(0, maxWords).join(' ');
        return limited + (words.length > maxWords ? '...' : '');
    }

        // Récupérer le texte original
    const originalMessage = $('#messagedisplay').text();

        // Limiter à 50 mots
    const limitedText = limitWords(originalMessage, 1);

        // Afficher le texte limité
    $('#limitedMessage').text(limitedText);
});


var $user_nom ='';
var user = [];
var usr_id = <?=  $this->session->userdata('user')['id'];?> ;
var usr_role =<?=  $this->session->userdata('user')['role'];?>;
var infosmg = [];
var date_msg = '';

$(document).ready(function() {
    $('#spinner').show();
    $('#spinner_id').show();                      
        $.ajax({
            url: '<?php echo site_url('message/get_message'); ?>',
            type: 'POST',
            dataType: 'json',
           /* data: {
                    userId: usr_id,
                    usrRole: usr_role,
            },*/
            success: function(response) {
                console.log(response.messages); // Affiche les messages
                infosmg = response.messages;
                role_msg = response.messages_role;
                console.log(role_msg);
                                   
            //nb_msg = response.message_counts;

            //  // Affichage message specifique de l'utilisateur dans l'id #container
            if (infosmg.length === 0) {
                $('#container').append('<div class="alert alert-info">Aucun message personnel.</div>');
            } else {
                    $.each(infosmg, function(index, info) {
                        var usr_mane = info.message_expediteur_id;
                        var usr_expediteur_id = info.message_expediteur_id;
                        const message_lus = info.message_lus.split(',');
                        const isUserRead = message_lus .includes(usr_id.toString());

                        // Fonction pour limiter le message à 2 mots
                        function limitMessage(msg) {
                            const words = msg.split(' ');
                            return words.slice(0, 2).join(' ');
                        }

                        $('#container').append(`
                            <div>
                                <a class="h-5 list-group-item list-group-item-action" href="#">
                                    <button type="button" class="btn btn-light w-100 text-start position-relative expediteur2 messageButton" id="expediteur-${info.message_expediteur_id}" 
                                            value="${info.message_expediteur_id}" 
                                            data-message_id="${info.message_id}"
                                            data-message="${info.message_message}" 
                                            data-objet="${info.message_objet}" 
                                            data-expediteur="${info.message_expediteur_id}"
                                            data-message-date="${info.message_date}"
                                    >
                                            <span id="messageButton"></span>
                                            <span  class="fs-5">${info.message_expediteur_name}</span>

                                                <!-- Ajout de la badge -->
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg2"  style="display: ${isUserRead ? 'none' : 'block'}; >
                                                <span class="visually-hidden"></span>
                                            </span>
                                    
                                        <div class="container2">
                                            <span class="fs-6">Objet: ${limitMessage(info.message_objet)}</span>
                                        </div>
                                        <div class="container2">
                                            <span id="limitedMessage" class="fs-6">Message: ${limitMessage(info.message_message)}</span> <!-- Limite le message ici -->
                                        </div>
                                    </button>
                                </a>
                        
                            </div>
                        `);
                    });
                }

                // Affichage message rôle de l'utilisateur dans l'id #container
                if (role_msg.length === 0) {
                    $('#container_id').append('<div class="alert alert-info">Aucun message pour votre rôle.</div>');
                } else {
                    $.each(role_msg, function(index, info) {
                        var usr_mane = info.message_expediteur_id;
                        console.log(info.message_status);
                        const message_lus = info.message_lus.split(',');
                        const isUserRead = message_lus .includes(usr_id.toString());
                            // Fonction pour limiter le message à 2 mots
                        function limitMessage(msg) {
                            const words = msg.split(' ');
                            return words.slice(0, 2).join(' ');
                        }

                        $('#container_msg_specifique').append(`
                            <div>
                                <a class="h-5 list-group-item list-group-item-action" href="#">
                                    <button type="button" class="btn btn-light w-100 text-start position-relative expediteur messageButton" id="expediteur-${info.message_expediteur_id}" 
                                            value="${info.message_expediteur_id}" 
                                            data-message_id="${info.message_id}"
                                            data-message="${info.message_message}" 
                                            data-objet="${info.message_objet}" 
                                            data-expediteur="${info.message_expediteur_id}"
                                            data-message-date="${info.message_date}"
                                    >
                                            <span id="messageButton"></span>
                                            <span  class="fs-5">${info.message_expediteur_name}</span>

                                                <!-- Ajout de la badge -->
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg"  style="display: ${isUserRead ? 'none' : 'block'}; >
                                                <span class="visually-hidden"></span>
                                            </span>
                                    
                                        <div class="container2">
                                            <span class="fs-6">Objet: ${limitMessage(info.message_objet)}</span>
                                        </div>
                                        <div class="container2">
                                            <span id="limitedMessage" class="fs-6">Message: ${limitMessage(info.message_message)}</span> <!-- Limite le message ici -->
                                        </div>
                                    </button>
                                </a>
                            </div>
                        `);
                    });
                }
            },
            /*success: function(response) {
                console.log(response.data); // Pour vérifier la structure complète de la réponse
                if (response.data && Array.isArray(response.data)) {
                    infosmg = response.data;

                    if (infosmg.length === 0) {
                        $('#container').append('<div class="alert alert-info">Aucun message.</div>');
                    } else {
                        $.each(infosmg, function(index, info) {
                            const usr_expediteur_id = info.message_expediteur_id;
                            const message_lus = info.message_lus ? info.message_lus.split(',') : [];
                            const isUserRead = message_lus.includes(usr_id.toString());

                            function limitMessage(msg) {
                                const words = msg.split(' ');
                                return words.slice(0, 2).join(' ');
                            }

                            $('#container').prepend(`
                                <div>
                                    <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                        <button type="button" class="btn position-relative expediteur2 messageButton" id="expediteur-${info.message_expediteur_id}" 
                                                value="${info.message_expediteur_id}" 
                                                data-message_id="${info.message_id}" 
                                                data-message="${info.message_message}" 
                                                data-objet="${info.message_objet}" 
                                                data-expediteur="${info.message_expediteur_id}" 
                                                data-message-date="${info.message_date}">
                                            <span id="messageButton"></span>
                                            <span>${info.message_expediteur_name}</span>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg2" style="display: ${isUserRead ? 'none' : 'block'};">
                                                <span class="visually-hidden"></span>
                                            </span>
                                        </button>
                                        <div class="container">
                                            <span>Objet: ${limitMessage(info.message_objet)}...</span>
                                        </div>
                                        <div class="container">
                                            <span id="limitedMessage">Message: ${limitMessage(info.message_message)}...</span>
                                        </div>
                                    </a>
                                </div>
                            `);
                        });
                    }
                } else {
                    $('#container').append('<div class="alert alert-danger">Erreur lors de la récupération des messages.</div>');
                }
            },*/
            error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
            },
            complete: function() {
                // Masquer le spinner une fois la requête terminée (qu'elle soit réussie ou non)
                $('#spinner').hide();
                $('#spinner_id').hide();
            }
        });
    })


/*function checkForNewMessages2(usr_expediteur_id) { 
    $.ajax({ 
    url: '<?= site_url('message/check_new_messages') ?>',
    method: 'GET',
    data: {
        userId: user_id,
        userRole: user_role,
        usr_expediteur_id: usr_expediteur_id,
    },
    success: function(data) {
        const result = JSON.parse(data);
        
        const newMessages = result.newMessages; // Nouveaux messages non lus role
        const unreadMessages = result.unreadMessages; // Messages non lus id_user message specifique  

        // Vérifier si des nouveaux messages existent
        if (newMessages.length > 0) {
            hasNewMessage2 = true;
            updateBadge2(); // Mettre à jour le badge
        }

        // Vous pouvez aussi traiter les messages non lus ici si nécessaire
        if (unreadMessages.length > 0) {
            // Traiter les messages non lus
            hasNewMessage3 = true;
            updateBadge();
            console.log('Messages non lus:', unreadMessages);
        }
    },
    error: function(xhr, status, error) {
        console.error('Erreur lors de la vérification des nouveaux messages :', error);
    }
});

}

function updateBadge() {
    const badge = $('#nb_msg');
    if (hasNewMessage3) {
        badge.show(); // Affiche le badge
    }
}
function updateBadge2() {
    const badge = $('#nb_msg2');
    if (hasNewMessage2) {
        badge.show(); // Affiche le badge
    }
}
// Vérifie les nouveaux messages toutes les 5 secondes
setInterval(() => {
    $('.messageButton').each(function() {
        const usr_expediteur_id = $(this).data('expediteur');
        checkForNewMessages2(usr_expediteur_id);
    });
}, 600000);
*/



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
                           
            });
                  // Afficher la variable dans le span
            $('#username').text(username);
                   // Extraire la première lettre et l'afficher
            var firstLetter = username.charAt(0);
            $('#initial').text(firstLetter);
              
    });
  
});


/**Bouton clicke pour afficher les messages pour le Roles*/
$(document).on('click', '.expediteur', function() {
    var nom = $(this).val();
    console.log(nom);
    const message = $(this).data('message'); // Récupérer le message correspondant
    $(this).find('#nb_msg').hide();
    $('#spinner1').show();

    var messageDate = $(this).data('message-date'); // Récupérer la valeur de message_date
    var message_id =  $(this).data('message_id'); 
    $('#messageDisplay').empty();
    //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
    $.ajax({
        url:'<?php echo site_url('message/verifierStatutMessage'); ?>',
        type:'POST',
        dataType: 'json',                                    
        data : {
            message_id : message_id,
            id_destinatair : usr_id,
            role_destinatair : usr_role,
            id_expediteur : nom,
            date_msg : messageDate,
        },
        success: function(response) {
            console.log(response.messages_role);
            const message = response.messages_role;

            $('#messageDisplay').empty();
            $('#messageDisplay_specifique').empty();
            
            $.each(message, function(index, msg){
                $('#messageDisplay').append(`
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5>Objet:${msg.message_objet}</h5>
                            <h6>Expéditeur: ${msg.message_expediteur_name}
                            <span class="mailbox-read-time float-right">${msg.message_date}</span></h6>
                        </div>
                        <div class="mailbox-read-message">
                            <p ><span class="ml-4 mt-4" style="height: 100px" id="messagedisplay">${msg.message_message}</span></p>
                        </div>                                             
                    </div>
                `)
            })
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
        complete: function() {
            // Cacher le spinner après la requête (qu'elle soit réussie ou en erreur)
            $('#spinner1').hide();
        }

    })
});

/**Bouton clicke pour afficher les messages specifiques */
$(document).on('click', '.expediteur2', function() {
    hasNewMessage = false;
    console.log(hasNewMessage);
    
    var nom = $(this).val();
    //console.log(nom);
    const message = $(this).data('message'); // Récupérer le message correspondant
    $(this).find('#nb_msg2').hide();
    $('#spinner1').show();

    var messageDate = $(this).data('message-date'); // Récupérer la valeur de message_date
    var message_id =  $(this).data('message_id');
    $('#messageDisplay').empty();
    //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
    $.ajax({
        url:'<?php echo site_url('message/verifierStatutMessageUserId'); ?>',
        type:'POST',
        dataType: 'json',                                    
        data : {
            message_id : message_id,
            id_destinatair : usr_id,
            role_destinatair : usr_role,
            id_expediteur : nom,
            date_msg : messageDate,
        },
        success: function(response) {
            console.log(response.data);
     
            const message_specifique = response.data;

            $('#messageDisplay_specifique').empty();
            $('#messageDisplay').empty();

            $.each(message_specifique, function(index, msg){
                $('#messageDisplay').append(`
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5>Objet:${msg.message_objet}</h5>
                            <h6>Expéditeur: ${msg.message_expediteur_name}
                            <span class="mailbox-read-time float-right">${msg.message_date}</span></h6>
                        </div>
                        <div class="mailbox-read-message">
                            <p ><span class="ml-4 mt-4" style="height: 100px" id="messagedisplay">${msg.message_message}</span></p>
                        </div>                                             
                    </div>
                `)
            })
           
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
        complete: function() {
            // Cacher le spinner après la requête (qu'elle soit réussie ou en erreur)
            $('#spinner1').hide();
        }

    })
});


/*function select_message_envoye(usr_nom){
console.log(usr_nom);
    $.ajax({
        url: '<?= site_url('message/get_message_envoye') ?>',
        type:'POST',
        dataType: 'json',
        data: {
            username_expediteur:usr_nom,
        },
        success: function(response) {                                 
            console.log(response);
            const msg_lus = response.messages_lu;
            const msg_non_lus = response.messages_non_lu;
           
            $.each(msg_lus, function(index, info){
                                    
                        // Fonction pour limiter le message à 2 mots
                function limitMessage(msg) {
                    const words = msg.split(' '); 
                    return words.slice(0, 2).join(' '); 
                }
                    //affichage message lus   
                $('#container2').append(`
                    <div>
                        <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                            <button type="button" class="btn position-relative msg_lus messageButton" id="expediteur-${info.message_expediteur_id}" 
                                value="${info.message_expediteur_id}" 
                                data-message="${info.message_message}" 
                                data-objet="${info.message_objet}" 
                                data-expediteur="${info.message_expediteur_id}"> 
                                <span id="messageButton"></span>
                                <span>${info.message_expediteur_id}</span>

                                <!-- Ajout de la badge -->
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" id="nb_msg">
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

            $.each(msg_non_lus, function(index, info){
                                    
                    // Fonction pour limiter le message à 2 mots
                function limitMessage(msg) {
                    const words = msg.split(' '); 
                    return words.slice(0, 2).join(' '); 
                }
                    //affichage message lus                    
                $('#container3').append(`
                    <div>
                        <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                            <button type="button" class="btn position-relative msg_non_lus messageButton" id="expediteur-${info.message_expediteur_id}" 
                                value="${info.message_expediteur_id}" 
                                data-message="${info.message_message}" 
                                data-objet="${info.message_objet}" 
                                data-expediteur="${info.message_expediteur_id}"> 
                                <span id="messageButton"></span>
                                <span>${info.message_expediteur_id}</span>

                                <!-- Ajout de la badge -->
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning" id="nb_msg">
                                <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                            <div class="container">
                                <span>Objet: ${limitMessage(info.message_objet)}</span>
                            </div>
                            <div class="container">
                                span id="limitedMessage">Message: ${limitMessage(info.message_message)}</span> <!-- Limite le message ici -->
                            </div>
                        </a>
                    </div>
                `);
            });
                                      
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
    });
}*/
/*let hasNewMessage = false;

if (Notification.permission !== "granted") {
    Notification.requestPermission();
}

function notifyUser(messageCount) {
    if (Notification.permission === "granted") {
        const notification = new Notification("Nouveaux messages", {
            body: `${messageCount} nouveau(x) message(s) reçu(s)`,
            // icon: 'path/to/icon.png' // Optionnel
        });

        notification.onclick = function() {
            window.focus(); // Ramener l'utilisateur sur la page
        };
    }
}

// Vérifie la permission pour les notifications
if (Notification.permission !== "granted") {
    Notification.requestPermission();
}

function notifyUser(messageCount) {
    if (Notification.permission === "granted") {
        const notification = new Notification("Nouveaux messages", {
            body: `${messageCount} nouveau(x) message(s) reçu(s)`,
            // icon: 'path/to/icon.png' // Optionnel, chemin vers une icône
        });

        notification.onclick = function() {
            window.focus(); // Ramène l'utilisateur sur la page
        };
    }
}

function checkForNewMessages() { 
    $.ajax({
        url: '<?= site_url('message/check_new_messages') ?>',
        method: 'GET',
        data: {
            userId: usr_id, 
        },
        success: function(data) {
            const messages = JSON.parse(data);
            if (messages.length > 0) {
                hasNewMessage = true;
                updateBadge();
                notifyUser(messages.length); // Notifier l'utilisateur
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la vérification des nouveaux messages :', error);
        }
    });
}

function updateBadge() {
    const badge = $('#badge');
    if (hasNewMessage) {
        badge.show(); // Affiche le badge
    }
}

$('.messageButton').click(function() {
    console.log(hasNewMessage);
    hasNewMessage = false; // Réinitialise l'état
    updateBadge(); // Met à jour le badge
    // Afficher les messages ici (ex: ouvrir un modal, rediriger, etc.)
});

// Vérifie les nouveaux messages toutes les 5 secondes
setInterval(checkForNewMessages, 5000);*/


</script>
<script>
$(document).on('click', '.import_file', function() {
    $.ajax({
        url: '<?= site_url('message/mark') ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#ton_element_html').html(response.content); // Afficher le contenu dans l'élément HTML
            } else {
                console.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX : ' + error);
        }
    });
});

  //select_message_envoye(usr_id);
                                    // Afficher le spinner avant de faire la seconde requête
                        /*$('#spinner').show();
                        /**fonction pour recupere le nom de l'expediteur */
                        /*$.ajax({
                            url:'<?php echo site_url('message/get_message'); ?>',
                            type:'POST',
                            dataType: 'json',
                            data : {
                                userId : usr_id,
                                usrRole : usr_role,
                            },
                            success: function(response) {
                                console.log(response.messages); // Affiche les messages
                                infosmg = response.messages;
                                role_msg = response.messages_role;
                              
                                console.log(response.message_counts);
                                nb_msg = response.message_counts


                                if(response === 0) {
                                    $('#container').append('<div class="alert alert-info">Aucun message à afficher.</div>');
                                }else{

                                }
                                $.each(infosmg, function(index, info){
                                    var usr_mane = info.message_expediteur_id;
                                    
                                        // Fonction pour limiter le message à 2 mots
                                    function limitMessage(msg) {
                                        const words = msg.split(' '); 
                                        return words.slice(0, 2).join(' '); 
                                    }
                       
                                    $('#container').append(`
                                        <div>
                                            <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                                <button type="button" class="btn position-relative expediteur messageButton" id="expediteur-${info.message_expediteur_id}" 
                                                    value="${info.message_expediteur_id}" 
                                                    data-message="${info.message_message}" 
                                                    data-objet="${info.message_objet}" 
                                                    data-expediteur="${info.message_expediteur_id}"> 
                                                    <span id="messageButton"></span>
                                                    <span>${info.message_expediteur_name}</span>

                                                    <!-- Ajout de la badge -->
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg">
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
                                
                                //affichage message role de l'utilisateure dans l'id #container_id

                                $.each(role_msg, function(index, info){
                                    var usr_mane = info.message_expediteur_id;
                                    
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
                                                    data-message="${info.message_message}" 
                                                    data-objet="${info.message_objet}" 
                                                    data-expediteur="${info.message_expediteur_id}"
                                                    data-message-date="${info.message_date}"
                                                >
                                                    
                                                    <span id="messageButton"></span>
                                                    <span>${info.message_expediteur_name}</span>

                                                    <!-- Ajout de la badge -->
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg">
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

                                let hasNewMessage = false;

                                if (Notification.permission !== "granted") {
                                    Notification.requestPermission();
                                }

                                function notifyUser(messageCount) {
                                    if (Notification.permission === "granted") {
                                        const notification = new Notification("Nouveaux messages", {
                                            body: `${messageCount} nouveau(x) message(s) reçu(s)`,
                                            // icon: 'path/to/icon.png' // Optionnel
                                        });

                                        notification.onclick = function() {
                                            window.focus(); // Ramener l'utilisateur sur la page
                                        };
                                    }
                                }

                                function checkForNewMessages() {
                                    $.ajax({
                                        url: '<?= site_url('message/check_new_messages') ?>',
                                        method: 'GET',
                                        data: {
                                            userId: usr_id, 
                                        },
                                        success: function(data) {
                                            const messages = JSON.parse(data);
                                            if (messages.length > 0) {
                                                hasNewMessage = true;
                                                updateBadge();
                                                notifyUser(messages.length); // Notifier l'utilisateur
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Erreur lors de la vérification des nouveaux messages :', error);
                                        }
                                    });
                                }

                                function updateBadge() {
                                    const badge = $('#badge');
                                    if (hasNewMessage) {
                                        badge.show(); // Affiche le badge
                                    }
                                }

                                $('.messageButton').click(function() {
                                    console.log(hasNewMessage);
                                    hasNewMessage = false; // Réinitialise l'état
                                    updateBadge(); // Met à jour le badge
                                    // Afficher les messages ici (ex: ouvrir un modal, rediriger, etc.)
                                });

                                // Vérifie les nouveaux messages toutes les 5 secondes
                                setInterval(checkForNewMessages, 5000);
                                

                            /*},
                            error: function(xhr, status, error) {
                                console.error('An error occurred:', error);
                            },
                            complete: function() {
                                // Masquer le spinner une fois la requête terminée (qu'elle soit réussie ou non)
                                $('#spinner').hide();
                            }
                        })*/
</script>

</section>
<style>
