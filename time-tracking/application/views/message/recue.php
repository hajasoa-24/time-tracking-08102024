
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

/* En-tête des messages */
.card-header {
    background-color: #33A8FF; /* Couleur de fond de l'en-tête */
    color: white; /* Couleur du texte */
}

/* Listes de messages */
.list-group {
    margin-top:8px;
    background-color: white; /* Fond blanc pour les messages */
    border-radius: 8px; /* Coins arrondis */
    box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Ombre légère */
}

/* Élément de message */
.list-group-item {
    padding: 15px; /* Espacement intérieur */
    border-bottom: 1px solid #e0e0e0; /* Ligne séparatrice */
    transition: background-color 0.3s; /* Animation pour le survol */
}

.list-group-item:hover {
    background-color: #f5f5f5; /* Couleur de fond au survol */
}

/* Lecture des messages */
.card-body {
    background-color: #ffffff; /* Fond blanc pour la lecture des messages */
    padding-bottom: 8px;
}

/* Spinner */
.spinner-border {
    margin: 0 auto; /* Centrer le spinner */
}

/* Message affiché */
.mailbox-read-message {
    padding: 15px; /* Espacement intérieur */
    line-height: 1.6; /* Hauteur de ligne pour un meilleur espacement */
}

/* Informations utilisateur */
#userInfo {
    font-size: 0.9em; /* Taille de police plus petite */
    color: #555; /* Couleur gris pour les informations */
}

</style>
<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="me-2 fs-3 text-white">Messages pour vous</span>
                        <i class="fa fa-user " aria-hidden="true"></i>
                    </h3>
                </div>
                <div class="list-group" style="height: 750px; overflow-y: scroll;">
                    <div id="container" style="height: 300px;"></div>
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
                    <h3 class="card-title text-white">Lire message</h3>
                    <span class="text-white" id="Destinatair_nom"></span>
                </div>
                <div class="card-body p-0" style="height: 750px; overflow-y: scroll;">
                    <div class="mailbox-read-message ml-3 p-2">
                        <div id="spinner1" style="display:none;" class="position-absolute top-50 start-50 translate-middle">
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
    </div>
</div>



<script>
    var tab_message ='';
    var message ='';
    var usercampagne = '';

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
        $.ajax({
            url: '<?php echo site_url('message/getUserCampagne'); ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                console.log('Response from getUserCampagne:', response);
                var donne = response.data;

                $('#spinner').show();
                $('#spinner_id').show();

                // Créer une variable pour stocker les IDs des campagnes
                let usercampagnes = [];

                // Vérifiez si 'donne' est un tableau et s'il a des éléments
                if (Array.isArray(donne) && donne.length > 0) {
                    $.each(donne, function(index, info) {
                        console.log('Campagne ID:', info.campagne_id);
                        usercampagnes.push(info.campagne_id || ''); // Ajouter les IDs, même s'ils sont vides
                    });
                } else {
                    // Si 'donne' est vide, assigner 0
                    usercampagnes = [0]; // ou usercampagnes = '0'; selon vos besoins
                }

                // Toujours exécuter le deuxième AJAX
                $.ajax({
                    url: '<?php echo site_url('message/get_message_user'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        CampagneUser: usercampagnes.join(',') // Joindre les IDs par une virgule ou envoyer '0'
                    },
                    success: function(response) {
                        console.log('Response from get_message_user:', response);
                        // Gérer la réponse
                        if (response.data && Array.isArray(response.data)) {
                            let infosmg = response.data;
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
                                    $('#container').append(`
                                        <div>
                                            <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                                <button type="button" class="btn btn-light w-100 text-start position-relative showmessage messageButton" 
                                                        id="expediteur-${info.message_expediteur_id}" 
                                                        value="${info.message_expediteur_id}" 
                                                        data-message_id="${info.message_id}" 
                                                        data-dest_id="${info.message_user ? info.message_user : 0 }"
                                                        data-role_id="${info.message_role_id ? info.message_role_id : 0 }" 
                                                        data-campagne_id="${info.message_campagne ? info.message_campagne : 0}" 
                                                        data-message="${info.message_message}" 
                                                        data-objet="${info.message_objet}" 
                                                        data-expediteur="${info.message_expediteur_id}" 
                                                        data-message-date="${info.message_date}">
                                                    <span>${info.message_expediteur_name}</span>
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg2" style="display: ${isUserRead ? 'none' : 'block'};">
                                                        <span class="visually-hidden"></span>
                                                    </span>
                                                    <div class="container">
                                                        <span>Objet: ${limitMessage(info.message_objet)}...</span>
                                                    </div>
                                                    <div class="container">
                                                        <span id="limitedMessage">Message: ${limitMessage(info.message_message)}...</span>
                                                    </div>
                                                </button>      
                                            </a>
                                        </div>
                                    `);
                                });
                            }
                        } else {
                            $('#container').append('<div class="alert alert-danger">Erreur lors de la récupération des messages.</div>');
                        }    
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', error);
                    },
                    complete: function() {
                        $('#spinner').hide();
                        $('#spinner_id').hide();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            },
        });
    });



    $(document).ready(function() {
        var message = [];
        var userName = '';
                    
        $(document).ready(function() {
            var username = $('#userInfo').data('username');
                //console.log(username); // Affiche la valeur dans la console
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
            });
                    // Afficher la variable dans le span
            $('#username').text(username);
                    // Extraire la première lettre et l'afficher
                var firstLetter = username.charAt(0);
            $('#initial').text(firstLetter);           
        });
    });


    /**Bouton clicke pour afficher les messages specifiques */
    $(document).on('click', '.showmessage', function() {
        hasNewMessage = false;
            //console.log(hasNewMessage);
        var nom = $(this).val();
            //console.log(nom);
        const message = $(this).data('message'); // Récupérer le message correspondant
        $(this).find('#nb_msg2').hide();
        $('#spinner1').show();

        var messageDate = $(this).data('message-date'); // Récupérer la valeur de message_date
        var message_id =  $(this).data('message_id');
        var dest_id = $(this).data('dest_id');
        var role_id =  $(this).data('role_id');
        var campagne_user = $(this).data('campagne_id');
        $('#messageDisplay').empty();
            //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
        $.ajax({
            url:'<?php echo site_url('message/get_message_user_update_message_lus'); ?>',
            type:'POST',
            dataType: 'json',                                    
            data : {
                message_id : message_id,
                id_destinatair : usr_id,
                role_destinatair : role_id,
                CampagneUser : campagne_user,
                id_expediteur : nom,
                date_msg : messageDate,
            },
            success: function(response) {
                console.log(response.data);
                var base_url = '<?php echo base_url('uploads/'); ?>';
                const message = response.data;

                $('#messageDisplay_specifique').empty();
                $('#messageDisplay').empty();
                $('#Destinatair_nom').empty();

                const campagnes = new Set();
                const roles = new Set();
                const utilisateurs = new Set();

                $.each(message, function(index, msg){
                    if (msg.message_fichier_path) {
                        var nomDuFichier = msg.message_fichier_path.split('\\').pop().split('/').pop();
                    } else {
                        var nomDuFichier = ''; 
                    }
                    $('#messageDisplay').append(`
                        <div class="card-body p-3 mb-3">
                            <div class="mailbox-read-info">
                                <h5>Objet : ${msg.message_objet}</h5>
                                <h6>Expéditeur : ${msg.message_expediteur_name}
                                <span class="mailbox-read-time float-right">${msg.message_date}</span></h6>
                            </div>
                            <div class="mailbox-read-message mt-8">
                                <p ><span class="ml-4 fs-5" style="height: 100px" id="messagedisplay">${msg.message_message}</span></p>
                            </div>
                            <div class="mailbox-attachment-info">
                                ${nomDuFichier ? `
                                    <a href="${base_url}${nomDuFichier}" class="mailbox-attachment-name" target="_blank">
                                        <i class="fa fa-paperclip"></i> ${nomDuFichier}
                                    </a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <a href="${base_url}${nomDuFichier}" class="btn btn-default btn-sm float-right" download>
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            <span style="color: primary;">Télécharger</span>
                                        </a>
                                    </span>
                                ` : `
                                    <div></div>
                                `}
                            </div>                                                                                         
                        </div>
                        <hr class="border border-dark border-2 opacity-50">
                    `)

                    if (msg.campagne_libelle) campagnes.add(msg.campagne_libelle);
                    if (msg.role_libelle) roles.add(msg.role_libelle);
                    if (msg.usr_nom) utilisateurs.add(msg.usr_nom + ' ' + msg.usr_prenom);
                });
                campagnes.forEach(campagne => {
                $('#Destinatair_nom').append(`<h3 class="text-white">${campagne}</h3>`);
                });

                roles.forEach(role => {
                    $('#Destinatair_nom').append(`<h3 class="text-white">${role}</h3>`);
                });

                utilisateurs.forEach(utilisateur => {
                    $('#Destinatair_nom').append(`<h3 class="text-white">${utilisateur}</h3>`);
                });
            
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

</script>

</section>





<script>
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

            /**--------------------------------------------------------------------------------- */
             //  // Affichage message specifique de l'utilisateur dans l'id #container
            /*if (infosmg.length === 0) {
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
                /*if (role_msg.length === 0) {
                    $('#container_id').append('<div class="alert alert-info">Aucun message pour votre rôle.</div>');
                } else {
                    $.each(role_msg, function(index, info) {
                        var usr_mane = info.message_expediteur_id;
                        //console.log(info.message_status);
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
                }*/
               /**---------------------------------------------------------------------------------------------------- */

               /**Bouton clicke pour afficher les messages pour le Roles*/
/*$(document).on('click', '.expediteur', function() {
    hasNewMessage = false;
    var nom = $(this).val();
    //console.log(nom);
    const message = $(this).data('message'); // Récupérer le message correspondant
    $(this).find('#nb_msg').hide();
    $('#spinner1').show();

    var messageDate = $(this).data('message-date'); // Récupérer la valeur de message_date
    var message_id =  $(this).data('message_id'); 
    var role_id =  $(this).data('role_id');
    $('#messageDisplay').empty();
    //$('#messageDisplay').text(message); // Afficher le message dans l'élément HTML
    $.ajax({
        url:'<?php echo site_url('message/verifierStatutMessage'); ?>',
        type:'POST',
        dataType: 'json',                                    
        data : {
            message_id : message_id,
            id_destinatair : usr_id,
            role_destinatair : role_id,
            id_expediteur : nom,
            date_msg : messageDate,
        },
        success: function(response) {
            var base_url = '<?php echo base_url('uploads/'); ?>';


           // console.log(response.messages_role);
            console.log(response);
            const message = response.messages_role;

            $('#messageDisplay').empty();
            $('#messageDisplay_specifique').empty();
            
            $.each(message, function(index, msg) { 
                //var nomDuFichier = msg.message_fichier_path.split('\\').pop().split('/').pop();
                if (msg.message_fichier_path) {
                    var nomDuFichier = msg.message_fichier_path.split('\\').pop().split('/').pop();
                } else {
                    var nomDuFichier = ''; 
                }
                $('#messageDisplay').append(`
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5>Objet: ${msg.message_objet}</h5>
                            <h6>Expéditeur: ${msg.message_expediteur_name}
                            <span class="mailbox-read-time float-right">${msg.message_date}</span></h6>
                        </div>
                        <div class="mailbox-read-message">
                            <p><span class="ml-4 mt-4" style="height: 100px" id="messagedisplay">${msg.message_message}</span></p>
                        </div>
                        <div class="mailbox-attachment-info">
                            ${nomDuFichier ? `
                                <a href="${base_url}${nomDuFichier}" class="mailbox-attachment-name" target="_blank">
                                    <i class="fa fa-paperclip"></i> ${nomDuFichier}
                                </a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <a href="${base_url}${nomDuFichier}" class="btn btn-default btn-sm float-right" download>
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        <span style="color: primary;">Télécharger</span>
                                    </a>
                                </span>
                            ` : `
                                <div></div>
                            `}
                        </div>                                            
                    </div >
                `);
            });
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error);
        },
        complete: function() {
            // Cacher le spinner après la requête (qu'elle soit réussie ou en erreur)
            $('#spinner1').hide();
        }
    })
});*/

    /*$(document).ready(function() {
        $.ajax({
            url: '<?php echo site_url('message/getUserCampagne'); ?>',
            type: 'POST',
            dataType: 'json',         
            success: function(response) {
            console.log(response.data)
            var donne = response.data;
                $('#spinner').show();
                $('#spinner_id').show();
            $.each(donne, function(index, info) {
                console.log(info.campagne_id);
                usercampagne = info.campagne_id;
                $.ajax({
                    url: '<?php echo site_url('message/get_message_user'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        CampagneUser : usercampagne || ' ',
                    },
                    success: function(response) {
                        //console.log(response.messages);
                        infosmg = response.messages;
                        role_msg = response.messages_role;
                        //console.log(role_msg);                   
                        //nb_msg = response.message_counts;
                    console.log(response.data); // Pour vérifier la structure complète de la réponse
                    if (response.data && Array.isArray(response.data)) {
                        infosmg = response.data;
                            //console.log(infosmg);
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
                                $('#container').append(`
                                    <div>
                                        <a class="h-5 list-group-item list-group-item-action" href="#list-item-1">
                                            <button type="button" class="btn btn-light w-100 text-start position-relative showmessage messageButton" id="expediteur-${info.message_expediteur_id}" 
                                                    value="${info.message_expediteur_id}" 
                                                    data-message_id="${info.message_id}" 
                                                    data-role_id="${info.message_role_id}" 
                                                    data-campagne_id="${info.message_campagne}" 
                                                    data-message="${info.message_message}" 
                                                    data-objet="${info.message_objet}" 
                                                    data-expediteur="${info.message_expediteur_id}" 
                                                    data-message-date="${info.message_date}">
                                                <span id="messageButton"></span>
                                                <span>${info.message_expediteur_name}</span>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nb_msg2" style="display: ${isUserRead ? 'none' : 'block'};">
                                                    <span class="visually-hidden"></span>
                                                </span>
                                                <div class="container">
                                                    <span>Objet: ${limitMessage(info.message_objet)}...</span>
                                                </div>
                                                <div class="container">
                                                    <span id="limitedMessage">Message: ${limitMessage(info.message_message)}...</span>
                                                </div>
                                            </button>      
                                        </a>
                                    </div>
                                `);
                            });
                        }
                    } else {
                        $('#container').append('<div class="alert alert-danger">Erreur lors de la récupération des messages.</div>');
                    }    
                },
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
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            },            
        });
    })*/

        
    /*$(document).ready(function() {
        $('.expediteur').on('click', function() {
            var expediteurId = $(this).val(); // Récupérer la valeur du bouton
                //console.log('ID de l\'expéditeur:', expediteurId);
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
                        //console.log(response.data);
                    tab_message = response.data;

                        // Vider le conteneur avant d'afficher les nouveaux messages
                    $('#message-container').empty();

                    $.each(tab_message, function(index, msg) {
                        message = msg.message_message;
                            //console.log(message);
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
    });*/
 
   /* $(document).ready(function() {
        $.ajax({
            url: '<?php echo site_url('message/getUserCampagne'); ?>',
            type: 'POST',
            dataType: 'json',
                        
            success: function(response) {
            console.log(response.data)
            var donne = response.data;
            $.each(donne, function(index, info) {
                console.log(info.campagne_id);
                usercampagne = info.campagne_id;
                console.log(usercampagne);
                
            })
            },            
        });
    })*/

</script>