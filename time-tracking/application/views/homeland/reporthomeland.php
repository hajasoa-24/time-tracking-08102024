<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Production Homeland</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 float-end">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="filtreProdHomelandDu">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreProdHomelandDu" id="filtreProdHomelandDu" placeholder="Du" value="<?= $filtre['Du'] ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="filtreProdHomelandAu">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreProdHomelandAu" id="filtreProdHomelandAu" placeholder="Au" value="<?= $filtre['Au'] ?>">
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                </form>
        </div> 

        <div class="col-md-12 mt-4">
            <!-- <div class="h6" style="padding-bottom: 10px;">Afficher/cacher : 
                <button type="button" class="btn btn-sm btn-secondary">Selectionner tout</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Tout déselectionner</button>
            </div>  -->

			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="toogleappel" data-table="prodappelhomeland-table" value="appel">
				<label class="form-check-label" for="toogleappel">Appel</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="tooglemail" data-table="prodmailhomeland-table" value="mail">
				<label class="form-check-label" for="tooglemail">Mail</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="toogleaffectation" data-table="prodaffectationhomeland-table" value="affectation">
				<label class="form-check-label" for="toogleaffectation">Affectation</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="toogleautretaches" data-table="prodautretacheshomeland-table" value="autrestaches">
				<label class="form-check-label" for="toogleautretaches">Autres tâches</label>
			</div>
			<div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="tooglecompta" data-table="prodcomptahomeland-table" value="compta">
                <label class="form-check-label" for="tooglecompta">Comptabilité</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="tooglejuridique" data-table="prodjuridiquehomeland-table" value="juridique">
                <label class="form-check-label" for="tooglejuridique">Juridique</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="tooglepeded" data-table="prodpededhomeland-table" value="peded">
                <label class="form-check-label" for="tooglepeded">PED/ED</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="toogleimma" data-table="prodimmahomeland-table" value="imma">
                <label class="form-check-label" for="toogleimma">Immatriculation</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="tooglesinistre" data-table="prodsinistrehomeland-table" value="sinistre">
                <label class="form-check-label" for="tooglesinistre">Sinistre</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="toogletechnique" data-table="prodtechniquehomeland-table" value="technique">
                <label class="form-check-label" for="toogletechnique">Technique</label>
            </div>
			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="tooglemajhbo" data-table="prodmajhbohomeland-table" value="majhbo">
				<label class="form-check-label" for="tooglemajhbo">MAJ HBO</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input toggletable" type="checkbox" id="toogledispatch" data-table="proddispatchhomeland-table" value="dispatch">
				<label class="form-check-label" for="toogledispatch">Dispatch</label>
			</div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggletable" type="checkbox" id="toogleparametrage" data-table="prodparametragehomeland-table" value="parametrage">
                <label class="form-check-label" for="toogledispatch">Parametrage</label>
            </div>

        </div>
    </div>
    
     <!--  tableau récapitulatif  -->
    <div class="row mt-5">
        <div class="col-md-12  title-page">
            <h3>Récapitulatif global</h3>
        </div>
        <div class="col-md-12">
            

            <table id="prodhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Appel</th>
                        <th>Mail</th>
                        <th>Affectation</th>
                        <th>Autres tâches</th>
                        <th>Comptabilité</th>
                        <th>Juridique</th>
                        <th>PED/ED</th>
                        <th>Immatriculation</th>
                        <th>Sinistre</th>
                        <th>Technique</th>
                        <th>MAJ HBO</th>
                        <th>DISPATCH</th>
                        <th>Paramétrage</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau récapitulatif  -->

    <!--  tableau détaillé pour appel homeland  -->

    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Appel</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodappelhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Nom du client</th>
                        <th>Adresse</th>
                        <th>Contact</th>
						<th>Commentaire</th>
						<th>Mesure prise</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- END tableau détaillé pour appel homeland  -->

    <!--  tableau détaillé pour mail homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Mail</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodmailhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Catégorie</th>
                        <th>Nom du client</th>
                        <th>Adresse</th>
                        <th>Contact</th>
						<th>Commentaire</th>
                        <th>Mesure prise</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour appel homeland  -->

    <!--  tableau détaillé pour affectation homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Affectation</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodaffectationhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Type d'affectation</th>
                        <th>ID dossier/ numéro de facture </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div> 
    <!--  END tableau détaillé pour affectation homeland  -->


    <!--  tableau détaillé pour Autres tâches homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Autres tâches</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodautretacheshomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Lien de la demande </th>
                        <th>Motif de la demande</th>
                        <th>Durée</th>
                        <th>Nombre de traitement</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div> 
    <!--  END tableau détaillé pour Autres tâches homeland  -->


    <!--  tableau détaillé pour comptabilité homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Comptabilité</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodcomptahomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
                        <th>Durée</th>
                        <th>Nombre de traiement</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour comptabilité homeland  -->


    <!--  tableau détaillé pour juridique homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Juridique</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodjuridiquehomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
						<th>Durée</th>
						<th>Nombre de traitement</th>
                        <th>Référence du dossier</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>



    <!--  tableau détaillé pour ped/ed homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>PED/ED</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodpededhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
						<th>Durée</th>
						<th>Nombre de traitement</th>
						<th>Nombre de Production</th>
                        <th>Nom de la vente/Nom du copropriétaire vendeur</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour ped/ed homeland  -->


    <!--  tableau détaillé pour immatriculation homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Immatriculation</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodimmahomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
                        <th>Durée</th>
                        <th>Nombre de traiement</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour immatriculation homeland  -->


    <!--  tableau détaillé pour Sinistre homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Sinistre</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodsinistrehomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
						<th>Durée</th>
						<th>Nombre de traitement</th>
                        <th>Numéro de dossier</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour Sinistre homeland  -->

    <!--  tableau détaillé pour technique homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Technique</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodtechniquehomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
						<th>Début</th>
						<th>Fin</th>
                        <th>Catégorie</th>
						<th>Durée</th>
						<th>Nombre de Traitement</th>
                        <th>Adresse du copropriétaire</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour technique homeland  -->


    <!--  tableau détaillé pour maj hbo homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>MAj/HBO</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodmajhbohomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
                        <th>type de maj effectué</th>
						<th>Lien</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour maj hbo homeland  -->


    <!--  tableau détaillé pour dispatch homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Dispatch</h3>
        </div>
        <div class="col-md-12">
            
            <table id="proddispatchhomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
						<th>Agent</th>
						<th>Date</th>
                        <th>Lien</th>
                        <!-- <th>Fin</th>
                        <th>Durée</th> -->
                        <th>Nombre de traitement</th> 
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div> 
    <!--  END tableau détaillé pour dispatch homeland  -->


    <!--  tableau détaillé pour parametrage homeland  -->
    <div class="row mt-5 categorie-container">
        <div class="col-md-12  title-page">
            <h3>Paramétrage</h3>
        </div>
        <div class="col-md-12">
            
            <table id="prodparametragehomeland-table" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Catégorie</th>
                        <th>Durée</th>
                        <th>Nombre de Traitement</th>
                        <th>Adresse copro</th>
                        <th>Lien</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!--  END tableau détaillé pour parametrage homeland  -->


</div>

<script type="text/javascript">
    $(document).ready(function(){
        /**
         * Parametrage affichage des tableaux
         */

         /* Initialisation */
         $('.toggletable').each(function(){
            let table = $(this).data('table');
            let tableContainer = $('#'+table).closest('.categorie-container');
            if(this.checked){
                $(tableContainer).show();
            }else{
                $(tableContainer).hide();
            }
         })

         /* Event on click sur les checkbox de tableaux*/
        $(document).on('change', '.toggletable', function(){
            let table = $(this).data('table');
            let tableContainer = $('#'+table).closest('.categorie-container');
            if(this.checked){
                $(tableContainer).show();
            }else{
                $(tableContainer).hide();
            }
        })
         /* ======== END Parametrage Affichage des tableaux ============ */

        //initialisation datatable
        $("#prodhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportHomeland"); ?>",
            columns : [
                {
                    data : 'agent',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'appel',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'mail',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'affectation',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'autrestaches',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'comptabilite',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'juridique',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'peded',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'immatriculation',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'sinistre',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'technique',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'majhbo',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'dispatch',
                    render : function(data, type, row){

                        return data
                    }
                },
                {
                    data : 'parametrage',
                    render : function(data, type, row){

                        return data
                    }
                }

            ]
        });

        /* Datatable pour Appel */
        $("#prodappelhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportAppelHomeland"); ?>",
            columns : [
                {
					data : 'usr_prenom',
					render : function(data, type, row){
                        return data
                    }
				},
				{ data : 'appelhomeland_date',
					render : function(data, type, row){
						var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
						return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
					}
				},
				{
                    data : 'categorieappel_libelle',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'appelhomeland_nomclient',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'appelhomeland_adresse',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'appelhomeland_contact',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'appelhomeland_commentaire',
                    render : function(data, type, row){
                        return data
                    }
                },
                { 
                    data : 'mesureprise_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        /* END Datatable pour Appel */


        /* Datatable pour Mail */
        $("#prodmailhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportMailHomeland"); ?>",
            columns : [
                {
                    data : 'usr_prenom',
                    render : function(data, type, row){
                        return data
                    }
                },
				{ data : 'mailhomeland_date',
					render : function(data, type, row){
						var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
						return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
					}
				},
				{
                    data : 'categoriemail_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'mailhomeland_nomclient',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'mailhomeland_adresse',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'mailhomeland_contact',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'mailhomeland_commentaire',
                    render : function(data, type, row){
                        return data
                    }
                },
                { 
                    data : 'mesureprise_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        /* END Datatable pour Mail */



        /* Datatable pour Affectation */
        $("#prodaffectationhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportAffectationHomeland"); ?>",
            columns : [
                {
                    data : 'usr_prenom',
                    render : function(data, type, row){
                        return data
                    }
                },
				{ data : 'affectationhomeland_date',
					render : function(data, type, row){
						var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
						return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
					}
				},
				{
                    data : 'affectation_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'affectationhomeland_iddossier',
                    render : function(data, type, row){
                        return data
                    } 
                },
                
            ]
        });
        /* END Datatable pour Affectation */



        /* Datatable pour Autres tâches */
        $("#prodautretacheshomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportAutretachesHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'autretacheshomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'autretacheshomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'autretacheshomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'autretacheshomeland_lien',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'autretacheshomeland_motif',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'autretacheshomeland_duree',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'autretacheshomeland_nbtraitement',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        /* END Datatable pour Autres tâches  */

        /* Datatable pour compta  */
        $("#prodcomptahomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportComptaHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'comptahomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'comptahomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'comptahomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categoriecompta_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'comptahomeland_duree',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'comptahomeland_nbtraitement',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });

        /* END Datatable pour compta  */


        /* Datatable pour juridique  */
        $("#prodjuridiquehomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportJuridiqueHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'juridiquehomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'juridiquehomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'juridiquehomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categoriejuridique_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
					data : 'juridiquehomeland_duree',
					render : function(data, type, row){
						return data
					}
				},
                {
					data : 'juridiquehomeland_nbtraitement',
					render : function(data, type, row){
						return data
					}
				},
                {
                    data : 'juridiquehomeland_refdossier',
                    render : function(data, type, row){
                        return data
                    }
                }

            ]
        });
        
        /* END Datatable pour juridique  */



        /* Datatable pour ped/ed  */
        $("#prodpededhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportPededHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'pededhomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'pededhomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'pededhomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categoriepeded_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
					data : 'pededhomeland_duree',
					render : function(data, type, row){
						return data
					}
				},
                {
					data : 'pededhomeland_nbtraitement',
					render : function(data, type, row){
						return data
					}
				},
                {
					data : 'pededhomeland_nbproduction',
					render : function(data, type, row){
						return data
					}
				},
                {
                    data : 'pededhomeland_nomvente',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'pededhomeland_commentaire',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        
        /* END Datatable pour ped/ed  */

        /* Datatable pour immatriculation  */
        $("#prodimmahomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportImmaHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'immahomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'immahomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'immahomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categorieimma_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'immahomeland_duree',
                    render : function(data, type, row){
                        return data
                    } 
                },
                { 
                    data : 'immahomeland_nbtraitement',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        
        /* END Datatable pour immatriculation  */


        /* Datatable pour sinistre  */
        $("#prodsinistrehomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportSinistreHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'sinistrehomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'sinistrehomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'sinistrehomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categoriesinistre_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'sinistrehomeland_duree',
                    render : function(data, type, row){
                        return data
                    }
                },
				{
                    data : 'sinistrehomeland_nbtraitement',
                    render : function(data, type, row){
                        return data
                    }
                },
				{
					data : 'sinistrehomeland_numdossier',
					render : function(data, type, row){
						return data
					}
				}

            ]
        });
        
        /* END Datatable pour sinistre  */


        /* Datatable pour technique  */
        $("#prodtechniquehomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportTechniqueHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'techniquehomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
                    data : 'techniquehomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
                    data : 'techniquehomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categorietechnique_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
				{
					data : 'techniquehomeland_duree',
					render : function(data, type, row){
						return data
					}
				},
				{
					data : 'techniquehomeland_nbtraitement',
					render : function(data, type, row){
						return data
					}
				},
				{
                    data : 'techniquehomeland_adresse',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        
        /* END Datatable pour technique  */


        /* Datatable pour maj hbo  */
        $("#prodmajhbohomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportMajhboHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'majhbohomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
				{
					data : 'majhbohomeland_typemaj',
					render : function(data, type, row){
						return data
					}

				},
				{
					data : 'majhbohomeland_lien',
					render : function(data, type, row){
						return data
					}

				}
			]
        });
        
        /* END Datatable pour maj hbo  */

        /* Datatable pour dispatch  */
        $("#proddispatchhomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportDispatchHomeland"); ?>",
            columns : [
				{
					data : 'usr_prenom',
					render : function(data, type, row){
						return data
					}
				},
				{ data : 'dispatchhomeland_date'},
                   
                { data : 'dispatchhomeland_lien'},
                { data : 'dispatchhomeland_nbtraitement'},

               
            ]
        });
        
        /* END Datatable pour dipatch  */


        /* Datatable pour paramétrage  */
        $("#prodparametragehomeland-table").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("homeland/getReportParametrageHomeland"); ?>",
            columns : [
                {
                    data : 'usr_prenom',
                    render : function(data, type, row){
                        return data
                    }
                },
                { data : 'parametragehomeland_date',
                    render : function(data, type, row){
                        var myDate = moment(data, 'YYYY-MM-DD HH:mm:ss');
                        return myDate.isValid() ? myDate.format('DD-MM-YYYY') : ''
                    } 
                },
                {
                    data : 'parametragehomeland_debut',
                    render : function(data, type, row){
                        return data
                    } 
                },
                {
                    data : 'parametragehomeland_fin',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'categorieparametrage_libelle',
                    render : function(data, type, row){
                        return data
                    } 
                },
                {
                    data : 'parametragehomeland_duree',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'parametragehomeland_nbtraitement',
                    render : function(data, type, row){
                        return data
                    }
                },
                {
                    data : 'parametragehomeland_adresse',
                    render : function(data, type, row){
                        return data
                    } 
                },
                {
                    data : 'parametragehomeland_lien',
                    render : function(data, type, row){
                        return data
                    } 
                }
                
            ]
        });
        
        /* END Datatable pour paramétrage  */

    })
</script>
