<style>
    .badgesmg {
        display: none;
        background-color: red;
        color: white;
        border-radius: 40%;
        padding: 5px;
        position: absolute;
        top: 60;
        margin-left: 50;
        float: right;
    }
</style>
<nav id="sidebar">
    <h1><a href="<?= site_url('dashboard/index') ?>" class="logo"><img src="<?=base_url('assets/images/setex_logo_vector-02-02.png')?>" alt="setex.fr" width="100%" height="auto"></a></h1>
    
    <ul class="list-unstyled components mb-5" id="sidebar-menu">

        <?php if($role == ROLE_ADMINRH): ?>
            <li class="<?= ($currentMenu == "dashboard" || $currentMenu == "absencesanormales" ) ? "active" : "" ?>">
                <a href="#submenu-dashboard" data-bs-toggle="collapse" ><span class="fa fa-tachometer"></span> Dashboard</a>
                <ul id="submenu-dashboard" class="list-unstyled components collapse <?= ($currentMenu == "dashboard" || $currentMenu == "absencesanormales") ? "show" : "" ?>" aria-labelledby="submenu-dashboard" data-bs-parent="#sidebar-menu">
                    <li class="pl-4  <?= ($currentMenu == "dashboard") ? "active" : "" ?>" >
                        <a href="<?= site_url('dashboard/tdb') ?>"> Suivi des présences</a>
                    </li>
                    <li class="pl-4  <?= ($currentMenu == "absencesanormales") ? "active" : "" ?>" >
                        <a href="<?= site_url('dashboard/absencesanormales') ?>"> Absences anormales</a>
                    </li>
            
                </ul>
            </li>
        <?php else: ?>
            
            <li class="<?= ($currentMenu == "dashboard") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_DIRECTION || $role == ROLE_CADRE2 || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                <a href="<?= site_url('dashboard/tdb') ?>"><span class="fa fa-tachometer"></span> Dashboard</a>
            </li>
            
        <?php endif; ?>

        <li class="<?= ($currentMenu == "progression" || $currentMenu == "tempsreel" || $currentMenu == "presence" || $currentMenu == "retard") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_DIRECTION || $role == ROLE_CADRE2 || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-suivi" data-bs-toggle="collapse" ><span class="fa fa-clock-o"></span> Suivi</a>
            <ul id="submenu-suivi" class="list-unstyled components collapse <?= ($currentMenu == "progression" || $currentMenu == "tempsreel" || $currentMenu == "presence" || $currentMenu == "retard") ? "show" : "" ?>" aria-labelledby="submenu-suivi" data-bs-parent="#sidebar-menu">
                <li class="pl-4  <?= ($currentMenu == "progression") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/progression') ?>"> Ma Progression</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "tempsreel") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_DIRECTION || $role == ROLE_CADRE2 || $role == ROLE_CLIENT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/tempsreel') ?>"> Temps réel</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "presence") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_DIRECTION || $role == ROLE_CADRE2 || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('presence/suiviPresence') ?>"> Présence</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "retard") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_DIRECTION || $role == ROLE_CADRE2 || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('presence/suiviRetard') ?>"> Retard</a>
                </li>
            </ul>
        </li>


        <li class="<?= ($currentMenu == "pointage-securite" || $currentMenu == "pointage-transport" || $currentMenu == "pointage-medical" || $currentMenu == "pointage-autres") ? "active" : "" ?>"  style="<?php echo ( ($this->session->userdata('user')['issecurite']) && $this->session->userdata('user')['issecurite'] == '1') || $role == ROLE_ADMINRH  ? 'display:block' : 'display: none'; ?>" >
            <a href="#submenu-pointage" data-bs-toggle="collapse"><span class="fa fa-hand-pointer-o"></span> Pointages</a>
            <ul id="submenu-pointage" class="list-unstyled components collapse <?= ($currentMenu == "pointage-securite" || $currentMenu == "pointage-transport" || $currentMenu == "pointage-medical" || $currentMenu == "pointage-autres") ? "show" : "" ?>" aria-labelledby="submenu-historique" data-bs-parent="#sidebar-menu">
                <li class="pl-4  <?= ($currentMenu == "pointage-securite") ? "active" : "" ?>">
                    <a href="<?= site_url('pointage/pointageSecurite') ?>"> Agents de sécurité</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "pointage-transport") ? "active" : "" ?>" >
                    <a href="<?= site_url('pointage/pointageTransport') ?>"> Agents de transport</a>
                </li>

                <li class="pl-4  <?= ($currentMenu == "pointage-medical") ? "active" : "" ?>" >
                    <a href="<?= site_url('pointage/pointageMedical') ?>"> Service Médical </a>
                </li>

                <li class="pl-4  <?= ($currentMenu == "pointage-autres") ? "active" : "" ?>" >
                    <a href="<?= site_url('pointage/pointageAutres') ?>"> Autres </a>
                </li>

            </ul>
            
        </li>


        </li>

        <li class="<?= ($currentMenu == "historique" || $currentMenu == "historiqueagents" || $currentMenu == "histopresence" || $currentMenu == "historetards") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-historique" data-bs-toggle="collapse"><span class="fa fa-history"></span> Historiques</a>
            <ul id="submenu-historique" class="list-unstyled components collapse <?= ($currentMenu == "historique" || $currentMenu == "historiqueagents" || $currentMenu == "histopresence" || $currentMenu == "historetards") ? "show" : "" ?>" aria-labelledby="submenu-historique" data-bs-parent="#sidebar-menu">
                <li class="pl-4  <?= ($currentMenu == "historique") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/historique') ?>"> Mon historique</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "historiqueagents") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/historiqueAgents') ?>"> Historique des agents</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "histopresence") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>" >
                    <a href="<?= site_url('presence/histoPresence') ?>"> Historique des présences</a>

                </li>
                <li class="pl-4  <?= ($currentMenu == "historetards") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>" >
                    <a href="<?= site_url('retard/histoRetards') ?>"> Historique des retards</a>

                </li>
            </ul>
            
        </li>

        
        <li class="<?= ($currentMenu == "recue" || $currentMenu == "envoye" || $currentMenu == "listes" ) ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-messages" data-bs-toggle="collapse"><span class="fa fa-commenting"></span> Messages<span id="badges" class="position-absolute  translate-middle badge rounded-pill bg-danger badgesmg">!</span></a>
            <ul id="submenu-messages" class="list-unstyled components collapse <?= ($currentMenu == "envoye" || $currentMenu == "recue" || $currentMenu == "listes" ) ? "show" : "" ?>" aria-labelledby="submenu-messages" data-bs-parent="#sidebar-menu">
                <li class="pl-4  <?= ($currentMenu == "recue") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/recue') ?>"  class="messageButton"> Boite de réception</a>
                </li>

                <li class="pl-4  <?= ($currentMenu == "envoye") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/envoye') ?>"> Envoyer un message</a>
                </li>

                <li class="pl-4  <?= ($currentMenu == "listes") ? "active" : "" ?>" style="<?php echo ($role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_ADMINRH || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_CLIENT || $role == ROLE_REPORTING) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('dashboard/listes') ?>"> Elément Envoyés</a>
                </li>
            </ul>
       
        </li>


        <li class="" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_CLIENT || $role == ROLE_REPORTING || $role == ROLE_ADMINRH) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-planning" data-bs-toggle="collapse"><span class="fa fa-calendar"></span> Planning</a>
            <ul id="submenu-planning" class="list-unstyled components collapse " aria-labelledby="submenu-planning" data-bs-parent="#sidebar-menu">
                <?php if(is_array($listcampagne)) : ?>
                    <?php foreach($listcampagne as $campagne) : ?>
                        <li class="pl-4 " style="">
                            <a href="<?= site_url('planning/suiviPlanning/campagne/' . $campagne->campagne_id) ?>"> <?= $campagne->campagne_libelle ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if(is_array($listservice)) : ?>
                    <?php foreach($listservice as $service) : ?>
                        <li class="pl-4 " style="">
                            <a href="<?= site_url('planning/suiviPlanning/service/' . $service->service_id) ?>"> <?= $service->service_libelle ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>
        </li>

        <li class="<?= ($currentMenu == "utilisateur" || $currentMenu == "campagne" || $currentMenu == "service" || $currentMenu == "mission" || $currentMenu == "process" || $currentMenu == "proprio" || $currentMenu == "affectationcampagne") ? "active" : "" ?>" style="<?php echo ($role == ROLE_ADMIN ) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-admin" data-bs-toggle="collapse"><span class="fa fa-cogs"></span> Administration</a>
            <ul id="submenu-admin" class="list-unstyled components collapse <?= ($currentMenu == "utilisateur" || $currentMenu == "campagne" || $currentMenu == "service" || $currentMenu == "mission" || $currentMenu == "process" || $currentMenu == "proprio" || $currentMenu == "affectationcampagne") ? "show" : "" ?>" aria-labelledby="submenu-admin" data-bs-parent="#sidebar-menu">
                <li class="pl-4  <?= ($currentMenu == "utilisateur") ? "active" : "" ?>">
                    <a href="<?= site_url('user/listUtilisateur') ?>"> Utilisateurs</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "campagne") ? "active" : "" ?>">
                    <a href="<?= site_url('campagne/listCampagne') ?>"> Campagnes</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "service") ? "active" : "" ?>">
                    <a href="<?= site_url('service/listService') ?>"> Services</a>
                </li>

                <!-- Gestion des menus pour ETP et Primes -->
                <li class="pl-4  <?= ($currentMenu == "proprio") ? "active" : "" ?>">
                    <a href="<?= site_url('proprio/listproprio') ?>"> Propriétaire</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "mission") ? "active" : "" ?>">
                    <a href="<?= site_url('mission/listmission') ?>"> Missions</a>
                </li>
                <li class="pl-4  <?= ($currentMenu == "process") ? "active" : "" ?>">
                    <a href="<?= site_url('process/listprocess') ?>"> Process</a>
                </li>
            </ul>
        </li>


        <li class="<?= ($currentMenu == "mesconges" || $currentMenu == "conges-a-valider" || $currentMenu == "conges-a-traiter" || $currentMenu == "soldes-et-droits" || $currentMenu == "histo-soldes-et-droits") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT || $role == ROLE_ADMINRH || $role == ROLE_CLIENT) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-admin" data-bs-toggle="collapse"><span class="fa fa-calendar"></span> Congés</a>
            <ul id="submenu-admin" class="list-unstyled components collapse <?= ($currentMenu == "mesconges" || $currentMenu == "conges-a-valider" || $currentMenu == "conges-a-traiter" || $currentMenu == "soldes-et-droits" || $currentMenu == "histo-soldes-et-droits") ? "show" : "" ?>" aria-labelledby="submenu-admin" data-bs-parent="#sidebar-menu">

                <li class="pl-4 <?= ($currentMenu == "mesconges" ) ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('conges/mesConges') ?>"> Mon solde et congés pris</a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "conges-a-valider") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION || $role == ROLE_COSTRAT || $role == ROLE_CLIENT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('conges/congesAValider') ?>"> Congés à valider</a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "conges-a-traiter") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_ADMINRH ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('conges/congesATraiter') ?>"> Congés à traiter</a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "soldes-et-droits") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_ADMINRH ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('conges/gestionSoldesDroits') ?>"> Soldes et droits</a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "histo-soldes-et-droits") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_ADMINRH ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('conges/histoSoldesDroits') ?>"> Historiques des soldes et des droits</a>
                </li>


            </ul>
        </li>

        

        <li class="<?= ($currentMenu == "ip") ? "active" : "" ?>" style="<?php echo ($this->session->userdata('user')['istech']) && $this->session->userdata('user')['istech'] == '1'  ? 'display:block' : 'display: none'; ?>" >
            <a href="<?= site_url('ip/listIp') ?>"><span class="fa fa-list"></span> Liste IP</a>
        </li>


        <li class="<?= ($currentMenu == "dooneesressource" || $currentMenu == "etpressource" || $currentMenu == "validationressource" || $currentMenu == "suivietp") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT || $role == ROLE_ADMINRH ) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-etp" data-bs-toggle="collapse"><span class="fa fa-calendar"></span> ETP</a>

            <ul id="submenu-etp" class="list-unstyled components collapse <?= ($currentMenu == "dooneesressource" || $currentMenu == "etpressource" || $currentMenu == "validationressource" || $currentMenu == "suivietp") ? "show" : "" ?>" aria-labelledby="submenu-etp" data-bs-parent="#sidebar-menu">

                <li class="pl-4 <?= ($currentMenu == "dooneesressource") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('etp/donneesRessource') ?>"> Mon suivi </a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "etpressource") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('etp/ressource') ?>"> Ressources</a>
                </li>
                <li class="pl-4 <?= ($currentMenu == "validationressource") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('etp/validationRessource') ?>"> Validation des Ressources</a>
                </li>
                <li class="pl-4 <?= ($currentMenu == "suivietp") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT || $role == ROLE_ADMINRH) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('etp/suivietp') ?>"> Suivi des ETP</a>
                </li>
                
            </ul>
        </li>



        <?php if ($isAuthorized): ?>
            <li class="<?= ($currentMenu == "compte-rendu" || $currentMenu == "reportHomeland") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2) ? 'display:block' : 'display: none'; ?>">
                <a href="#submenu-homeland" data-bs-toggle="collapse"><span class="fa fa-clock-o"></span> Homeland</a>
                <ul id="submenu-homeland" class="list-unstyled components collapse <?= ($currentMenu == "compte-rendu" || $currentMenu == "reportHomeland") ? "show" : "" ?>" aria-labelledby="submenu-homeland" data-bs-parent="#sidebar-menu">
                    <li class="<?= ($currentMenu == "compte-rendu") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP) ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('homeland/suiviTemps') ?>" id="compterendu" class="submenu-item"> Prodution Homeland</a>
                    </li>
                    
                    <li class="<?= ($currentMenu == "reportHomeland") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || ROLE_AGENT ) ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('homeland/reportHomeland') ?>" id="suivi-productions" class="submenu-item"> Suivi des productions</a>
                    </li>
                    
                </ul>
            </li>
        <?php endif; ?>



        <?php if ($isRC): ?>
            <li class="<?= ($currentMenu == "gestion-agence" || $currentMenu == "tableau-agence") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2) ? 'display:block' : 'display: none'; ?>">
                <a href="#submenu-agence" data-bs-toggle="collapse"><span class="fa fa-clock-o"></span> RELAIS COLIS</a>
                <ul id="submenu-agence" class="list-unstyled components collapse <?= ($currentMenu == "gestion-agence" || $currentMenu == "tableau-agence") ? "show" : "" ?>" aria-labelledby="submenu-agence" data-bs-parent="#sidebar-menu">
                    <li class="<?= ($currentMenu == "gestion-agence") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2) ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('agence/gestionAgence') ?>" id="gestionagence" class="submenu-item"> Gestion des agences</a>
                    </li>
                    
                    <li class="<?= ($currentMenu == "tableau-agence") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 ) ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('agence/tableauAgence') ?>" id="tableauagence" class="submenu-item"> Calendrier</a>
                    </li>
                    
                </ul>
            </li>
        <?php endif; ?>


        <li class="<?= ($currentMenu == "it-check" || $currentMenu == "it-suivis" ) ? "active" : "" ?>"  style="<?php echo ( ($this->session->userdata('user')['istech']) && $this->session->userdata('user')['istech'] == '1')  ? 'display:block' : 'display: none'; ?>" >
            <a href="#submenu-taches" data-bs-toggle="collapse"><span class="fa fa-hand-pointer-o"></span> IT</a>
                <ul id="submenu-taches" class="list-unstyled components collapse <?= ($currentMenu == "it-check" || $currentMenu == "it-suivis") ? "show" : "" ?>" aria-labelledby="submenu-taches" data-bs-parent="#sidebar-menu">
                    <li class="pl-4  <?= ($currentMenu == "it-check") ? "active" : "" ?>" style="<?php echo (($this->session->userdata('user')['istech']) && $this->session->userdata('user')['istech'] == '1') && ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE)   ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('tache/taches') ?>"> Tâches à faire </a>
                    </li>
                    <li class="pl-4  <?= ($currentMenu == "it-suivis") ? "active" : "" ?>" style="<?php echo (($this->session->userdata('user')['iscadreIT']) && ($this->session->userdata('user')['iscadreIT'] =='1')) && ($role == ROLE_CADRE || $role == ROLE_CADRE2) ? 'display:block' : 'display: none'; ?>">
                        <a href="<?= site_url('tache/suivitachescadre') ?>"> Suivi des tâches IT </a>
                    </li>

                </ul>
        </li>


        <li class="<?= ($currentMenu == "suivitransport" || $currentMenu == "assignationaxe") ? "active" : "" ?>"  style="<?php echo ( ($this->session->userdata('user')['usersuppl_isRespTransport']) && $this->session->userdata('user')['usersuppl_isRespTransport'] == '1')  ? 'display:block' : 'display: none'; ?>" >
            <a href="#submenu-transport" data-bs-toggle="collapse"><span class="fa fa-car"></span> Transport</a>
                <ul id="submenu-transport" class="list-unstyled components collapse <?= ($currentMenu == "suivitransport" || $currentMenu == "assignationaxe") ? "show" : "" ?>" aria-labelledby="submenu-transport" data-bs-parent="#sidebar-menu">

                    <li class="pl-4  <?= ($currentMenu == "suivitransport") ? "active" : "" ?>">
                        <a href="<?= site_url('transport/suiviTransport') ?>"> Suivis des transports </a>
                    </li>

                    <li class="pl-4  <?= ($currentMenu == "assignationaxe") ? "active" : "" ?>">
                        <a href="<?= site_url('transport/assignation') ?>">Gestion des axes</a>
                    </li>

                </ul>
        </li>

        <!-- gestion des profils , critères et CALCUL PRIME --> 
        <li class="<?= ($currentMenu == "gestionprofil" || $currentMenu == "gestioncritere" || $currentMenu == "primejournaliere" || $currentMenu == "suiviprime") ? "active" : "" ?>" style="<?php echo ($role == ROLE_AGENT || $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT || $role == ROLE_ADMINRH ) ? 'display:block' : 'display: none'; ?>">
            <a href="#submenu-prime" data-bs-toggle="collapse"><span class="fa fa-calendar"></span> Prime</a>

            <ul id="submenu-prime" class="list-unstyled components collapse <?= ($currentMenu == "gestionprofil" || $currentMenu == "gestioncritere" || $currentMenu == "primejournaliere" || $currentMenu == "suiviprime") ? "show" : "" ?>" aria-labelledby="submenu-etp" data-bs-parent="#sidebar-menu">

                <li class="pl-4 <?= ($currentMenu == "gestionprofil") ? "active" : "" ?>" style="<?php echo  ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('primeprofil/list') ?>"> Gestion des profils  </a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "gestioncritere") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('primecritere/listCritere') ?>"> Gestion des critères </a>
                </li>
                
                <li class="pl-4 <?= ($currentMenu == "primejournaliere") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_AGENT || $role == ROLE_SUP ||$role == ROLE_CADRE ) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('prime/primejournaliere') ?>"> Suivi de production</a>
                </li>

                <li class="pl-4 <?= ($currentMenu == "suiviprime") ? "active" : "" ?>" style="<?php echo ( $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_DIRECTION  || $role == ROLE_COSTRAT || $role == ROLE_ADMINRH) ? 'display:block' : 'display: none'; ?>">
                    <a href="<?= site_url('primeproduction/production') ?>"> Suivi des primes</a>
                </li>
                
            </ul>
        </li>

        
	   <?php if($role == ROLE_ADMINRH || $role == ROLE_ADMIN ): ?>
            <li class="<?= ($currentMenu == "jourferie") ? "active" : "" ?>">
                <a href="<?= site_url('jourferie/ferie') ?>"> <span class="fa fa-money"></span>Jour férie</a>
            </li>
        <?php else: ?>
        <?php endif; ?>


        
        <li class="<?= ($currentMenu == "profil") ? "active" : "" ?>">
            <a href="<?= site_url('user/profil') ?>"><span class="fa fa-user"></span> Mon Profil</a>
        </li>
        
        <li>
            <a href="<?= site_url('auth/doLogout')?>"><span class="fa fa-sign-out"></span> Déconnexion</a>
        </li>
        
    </ul>
    <!--<div>
    <?=  var_dump($this->session->userdata('user'));?>
    </div>-->
  
</nav>
