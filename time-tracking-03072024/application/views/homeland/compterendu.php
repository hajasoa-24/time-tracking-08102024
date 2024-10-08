<div class="container">
	<div class="alert alert-dismissible alert-success" id="success-alert" style="display: none;">
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		<strong>Succès</strong> Votre action a réussi.
	</div>
	<div id="error-alert" class="alert alert-dismissible alert-danger" style="display: none;">
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		<strong>Erreur!</strong> <span id="error-message"></span>
	</div>

	<div class="toast" id="dispatch-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
		<div class="toast-header">
			<strong class="me-auto">Success</strong>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
		<div class="toast-body">
			Le nombre de dispatch est de : <span id="counter-message" style="font-weight: bold;"></span>
		</div>
	</div>

	<h2>PRODUCTION HOMELAND : <span id="selected-pole"></span></h2>
	<form id="insert-form" method="post">
		<div class="row">
			<div class="col-md-3">
				<nav id="compte" class="active">
					<ul class="nav flex-column">
						<?php foreach ($poles as $pole) : ?>
							<div class="category-item mb-2">
								<button type="button" class="btn btn-outline-primary btn-sm pole-button" id="pole-<?php echo $pole['polehomeland_id']?>">
									<a class="nav-link category-button" href="#form-<?php echo $pole['polehomeland_id']?>"><?php echo $pole['polehomeland_libelle']; ?></a>
								</button>
							</div>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>

			<div class="col-md-9">
				<div id="specific-fields" class="mt-4">
					<div class="form-categorie tr_categorieappel" id="form-1" data-url="<?php echo site_url("Homeland/insertAppelHomeland") ?>">
						<div class="row">
							<div class="col-md-5">
								<h3>Catégorie</h3>
								<div class="category-list">
									<?php foreach ($appels as $appel) : ?>
										<div class="category-item">
											<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-appel-<?php echo $appel['categorieappel_id']; ?>" value="<?php echo $appel['categorieappel_id']; ?>">
											<label class="btn btn-outline-primary" for="categorie-appel-<?php echo $appel['categorieappel_id']; ?>"><?php echo $appel['categorieappel_libelle']; ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="col-md-7">
								<div class="mb-3">
									<label for="nomclient" class="form-label">Nom client :</label>
									<input type="text" class="form-control" name="nomclient" >
								</div>
								<div class="mb-3">
									<label for="adresse" class="form-label">Adresse :</label>
									<input type="text" class="form-control" name="adresse" required>
								</div>

								<div class="mb-3">
									<label for="contact" class="form-label">Contact :</label>
									<input type="text" class="form-control" name="contact" required>
								</div>

								<div class="mb-3">
									<label for="commentaire" class="form-label">Commentaire :</label>
									<input type="text" class="form-control" name="commentaire" required>
								</div>

								<div class="mb-3">
									<label for="mesure" class="form-label">Mesure Prise:</label>
									<div class="btn-group mesure-radio" role="group" aria-label="Mesure">
										<?php foreach ($mesures as $mesure) : ?>
											<?php if ($mesure['mesureprise_id'] >= 1 && $mesure['mesureprise_id'] <= 5) : ?>
												<input type="radio" class="btn-check mesure-radio btn-primary" name="mesure" id="mesure-<?php echo $mesure['mesureprise_id']; ?>" value="<?php echo $mesure['mesureprise_id']; ?>" required>
												<label class="btn btn-outline-primary" for="mesure-<?php echo $mesure['mesureprise_id']; ?>"><?php echo $mesure['mesureprise_libelle']; ?></label>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-3">
							<button type="submit" class="btn btn-primary" id="valider-appel-button">Valider</button>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categoriemail" id="form-2" data-url="<?php echo site_url("Homeland/insertMailHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($mails as $mail) : ?>
									<div class="category-item">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-mail-<?php echo $mail['categoriemail_id']; ?>" value="<?php echo $mail['categoriemail_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-mail-<?php echo $mail['categoriemail_id']; ?>"><?php echo $mail['categoriemail_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<label for="nomclient_mail" class="form-label">Nom client :</label>
								<input type="text" class="form-control" name="nomclient_mail" >
							</div>
							<div class="mb-3">
								<label for="adresse_mail" class="form-label">Adresse :</label>
								<input type="text" class="form-control" name="adresse_mail" required>
							</div>
							<div class="mb-3">
								<label for="contact_mail" class="form-label">Contact :</label>
								<input type="text" class="form-control" name="contact_mail"  required>
							</div>
							<div class="mb-3">
								<label for="commentaire_mail" class="form-label">Commentaire :</label>
								<input type="text" class="form-control" name="prenomclient_mail" required>
							</div>
							<div class="mb-3" style="display: none">
								<label for="mesure" class="form-label">Mesure Prise:</label>
								<div class="btn-group" role="group" aria-label="Mesure">
									<?php
									$idActif = 5;
									foreach ($mesures as $mesure) :
										if ($mesure['mesureprise_id'] == $idActif) :
											?>
											<input type="radio" class="btn-check mesure-radio btn-primary" name="mesure" id="mesure-<?php echo $mesure['mesureprise_id']; ?>" value="<?php echo $mesure['mesureprise_id']; ?>" checked>
											<label class="btn btn-outline-primary active" for="mesure-<?php echo $mesure['mesureprise_id']; ?>"><?php echo $mesure['mesureprise_libelle']; ?></label>
										<?php
										endif;
									endforeach;
									?>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-3">
							<button type="submit" class="btn btn-primary" id="valider-mail-button">Valider</button>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categorieaffectation" id="form-3" data-url="<?php echo site_url("Homeland/insertAffectationHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Type d'affectation</h3>
							<div class="category-list">
								<?php foreach ($affectations as $affectation) : ?>
									<div class="category-item">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-affectation-<?php echo $affectation['affectation_id']; ?>" value="<?php echo $affectation['affectation_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-affectation-<?php echo $affectation['affectation_id']; ?>"><?php echo $affectation['affectation_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<label for="dossier" class="form-label">Dossier :</label>
								<input type="text" class="form-control" name="dossier" required>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-3">
							<button type="submit" class="btn btn-primary" id="valider-affectation-button">Valider</button>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categorieautres" id="form-4" data-url="<?php echo site_url('Homeland/insertAutresTachesHomeland') ?>">
					<div class="mb-3">
						<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
						<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
						<input type="text" class="form-control heure-input" name="autres" readonly disabled>
					</div>
					<div class="mb-3">
						<label for="lien" class="form-label">Lien :</label>
						<input type="text" class="form-control" name="lien" disabled required>
					</div>
					<div class="mb-3">
						<label for="motif" class="form-label">Motif :</label>
						<input type="text" class="form-control" name="motif" disabled required>
					</div>
				</div>

				<div class="form-categorie tr_categoriecompta" id="form-5" data-url="<?php echo site_url("Homeland/insertComptabiliteHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($comptas as $compta) : ?>
									<div class="category-item">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-compta-<?php echo $compta['categoriecompta_id']; ?>" value="<?php echo $compta['categoriecompta_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-compta-<?php echo $compta['categoriecompta_id']; ?>"><?php echo $compta['categoriecompta_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="compta" readonly disabled>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categoriejuridique" id="form-6" data-url="<?php echo site_url("Homeland/insertJuridiqueHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($juridiques as $juridique) : ?>
									<div class="category-item">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-juridique-<?php echo $juridique['categoriejuridique_id']; ?>" value="<?php echo $juridique['categoriejuridique_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-juridique-<?php echo $juridique['categoriejuridique_id']; ?>"><?php echo $juridique['categoriejuridique_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="juridique" readonly disabled>
							</div>
							<div class="mb-3">
								<label for="refdossier_juridique" class="form-label">Référence du dossier :</label>
								<input type="text" class="form-control" name="refdossier_juridique" disabled required>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categoriepeded" id="form-7" data-url="<?php echo site_url("Homeland/insertPededHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($pededs as $peded) : ?>
									<div class="category-item">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-peded-<?php echo $peded['categoriepeded_id']; ?>" value="<?php echo $peded['categoriepeded_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-peded-<?php echo $peded['categoriepeded_id']; ?>"><?php echo $peded['categoriepeded_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="peded" readonly disabled>
							</div>
							<div class="mb-3">
								<label for="nomevente" class="form-label">Nom de la vente :</label>
								<input type="text" class="form-control" name="nomvente" disabled required>
							</div>
							<div class="mb-3">
								<label for="commentaire_peded" class="form-label">Commentaire :</label>
								<input type="text" class="form-control" name="commentaire_peded" disabled required>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categorieimma" id="form-8" data-url="<?php echo site_url("Homeland/insertImmaHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($immas as $imma) : ?>
									<div class="category-item mb-2">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-imma-<?php echo $imma['categorieimma_id']; ?>" value="<?php echo $imma['categorieimma_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-imma-<?php echo $imma['categorieimma_id']; ?>"><?php echo $imma['categorieimma_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="imma" readonly>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categoriesinistre" id="form-9" data-url="<?php echo site_url("Homeland/insertSinistreHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($sinistres as $sinistre) : ?>
									<div class="category-item mb-2">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-sinistre-<?php echo $sinistre['categoriesinistre_id']; ?>" value="<?php echo $sinistre['categoriesinistre_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-sinistre-<?php echo $sinistre['categoriesinistre_id']; ?>"><?php echo $sinistre['categoriesinistre_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="sinistre" readonly disabled>
							</div>

							<div class="mb-3">
								<label for="numdossier_sinistre" class="form-label">Numéro de dossier :</label>
								<input type="text" class="form-control" name="numdossier_sinistre" disabled required>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categorietechnique" id="form-10" data-url="<?php echo site_url("Homeland/insertTechniqueHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($techniques as $technique) : ?>
									<div class="category-item mb-2">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-technique-<?php echo $technique['categorietechnique_id']; ?>" value="<?php echo $technique['categorietechnique_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-technique-<?php echo $technique['categorietechnique_id']; ?>"><?php echo $technique['categorietechnique_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="technique" readonly disabled>
							</div>
							<div class="mb-3">
								<label for="adresse_technique" class="form-label">Adresse :</label>
								<input type="text" class="form-control" name="adresse_technique" disabled required>
							</div>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categoriemajhbo" id="form-11" data-url="<?php echo site_url("Homeland/insertMajhboHomeland") ?>">
					<div class="mb-3">
						<label for="typemaj" class="form-label">Type de MAJ :</label>
						<input type="text" class="form-control" name="typemaj"  required>
					</div>
					<div class="mb-3">
						<label for="lien" class="form-label">Lien :</label>
						<input type="text" class="form-control" name="lien" required>
					</div>
					<div class="d-flex justify-content-end mt-3">
						<button type="submit" class="btn btn-primary" id="valider-majhbo-button">Valider</button>
					</div>
				</div>

				<!-- <div class="form-categorie tr_categoriedispatch" id="form-12" data-url="<?php echo site_url("Homeland/insertDispatchHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<button type="button" class="btn btn-primary btn-block mb-2" id="debut-button" >Début</button>
							<button type="button" class="btn btn-danger btn-block mb-2" id="fin-button" style="display: none;" >Fin</button>
							<div class="mb-3 d-flex align-items-center">
								<button type="button" class="btn btn-danger ml-2" id="moins-button">-</button>
								<input type="text" class="form-control" id="calcul_dispatch" readonly>
								<button type="button" class="btn btn-success mr-2" id="plus-button">+</button>
							</div>
						</div>
					</div>
				</div> -->

				<div class="form-categorie tr_categoriedispatch" id="form-12" data-url="<?php echo site_url("Homeland/insertDispatchHomeland") ?>">
					<div class="row">
						<div class="col-md-8">
							<label for="lien_homeland">Lien</label>
							<input type="text" class="form-control" name="lien_dispatch" id="lien_homeland" required>

						</div>
						<div class="d-flex justify-content-end mt-0">
							<button type="submit" class="btn btn-primary" id="">Valider</button>
						</div>
					</div>
				</div>

				<div class="form-categorie tr_categorieparametrage" id="form-13" data-url="<?php echo site_url("Homeland/insertParametrageHomeland") ?>">
					<div class="row">
						<div class="col-md-5">
							<h3>Catégorie</h3>
							<div class="category-list">
								<?php foreach ($parametrages as $parametrage) : ?>
									<div class="category-item mb-2">
										<input type="radio" class="btn-check category-radio" name="categorie" id="categorie-parametrage-<?php echo $parametrage['categorieparametrage_id']; ?>" value="<?php echo $parametrage['categorieparametrage_id']; ?>">
										<label class="btn btn-outline-primary" for="categorie-parametrage-<?php echo $parametrage['categorieparametrage_id']; ?>"><?php echo $parametrage['categorieparametrage_libelle']; ?></label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="col-md-7">
							<div class="mb-3">
								<button class="btn btn-primary btn-lg debut-button" type="button">Début</button>
								<button class="btn btn-primary btn-lg fin-button" type="submit" style="display: none;">Fin</button>
								<input type="text" class="form-control heure-input" name="parametrage" readonly disabled>
							</div>
							<div class="mb-3">
								<label for="lien_parametrage" class="form-label">Lien :</label>
								<input type="text" class="form-control" name="lien_parametrage" disabled required>
							</div>
							<div class="mb-3">
								<label for="adcopro_parametrage" class="form-label">Adresse copro :</label>
								<input type="text" class="form-control" name="adcopro_parametrage" disabled required>
							</div>
						</div>
					</div>
				</div>

				
			</div>
		</div>
</div>
</form>
</div>
<script>
	$(document).ready(function () {
		$('.form-categorie').hide();
		$(".pole-button").click(function () {
			var poleLibelle = $(this).find(".category-button").text();
			$("#selected-pole").text(poleLibelle);
			$('.pole-button').removeClass('active');
			$(this).addClass('active');
			$('.pole-button').css('color', '');
			$(this).css('color', 'white');
		});

		function showSuccessAlert() {
			$('#success-alert').fadeIn();
			setTimeout(function () {
				$('#success-alert').fadeOut();
			}, 10000);
		}

		function convertirDureeEnMinutes(duree) {
			const [heures, minutes] = duree.split(":");
			return (parseInt(heures) * 60) + parseInt(minutes);
		}
		function showErrorAlert(errorMessage) {
			$('#error-message').text(errorMessage);
			$('#error-alert').fadeIn();
			setTimeout(function () {
				$('#error-alert').fadeOut();
			}, 10000);
		}

		function showToast(message) {
			var toast = new bootstrap.Toast(document.getElementById('dispatch-toast'));
			$('#counter-message').text(message);
			toast.show();
		}
		var dispatchCount = 0;
		var debutTime = null;
		var formattedTime = null;

		var storedDebutTime = localStorage.getItem('debutTime');
		if (storedDebutTime !== null) {
			$('#debut-button').hide();
			$('#fin-button').show();
			$('#plus-button').prop('disabled', false);
			$('#moins-button').prop('disabled', false);

			debutTime = new Date(storedDebutTime);
			debutTime.setHours(debutTime.getHours()+3)
			formattedTime = debutTime.toLocaleTimeString('en-US', { hour12: false });
		}

		function updateDispatchCount() {
			$('#compteur').text(dispatchCount);
			$('#calcul_dispatch').val(dispatchCount);
		}
		function resetForm(formId) {
			var form = $(formId);

			form.find('input, select, textarea').val('');
			form.find('input[type="radio"]').prop('checked', false);
			form.find('input[type="checkbox"]').prop('checked', false);
			form.find('input[type="hidden"]').val('');

			if (typeof tinymce !== 'undefined') {
				form.find('.tinymce-editor').each(function () {
					var editorId = $(this).attr('id');
					tinymce.get(editorId).setContent('');
				});
			}
		}
		$('#debut-button').click(async function () {
			try {
				const dateHeure = await getServerTimeEuropeParis();
				console.log('Valeur de dateHeure:', dateHeure);  // Loguer la valeur obtenue

				// Vérifier si la dateHeure est dans le format attendu "YYYY-MM-DD HH:MM:SS"
				const regex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
				if (dateHeure && regex.test(dateHeure)) {
					$('#debut-button').hide();
					$('#fin-button').show();
					$('#plus-button').prop('disabled', false);
					$('#moins-button').prop('disabled', false);

					// Stocker la date et l'heure directement au format "YYYY-MM-DD HH:MM:SS"
					localStorage.setItem('debutTime', dateHeure);
					console.log('Heure de début:', dateHeure);
				} else {
					console.error('Format de date et heure invalide.');
				}
			} catch (error) {
				console.error('Erreur : ' + error.message);
			}
		});

		$('#fin-button').click(function () {
			$('#fin-button').hide();
			$('#debut-button').show();
			$('#plus-button').prop('disabled', true);
			$('#moins-button').prop('disabled', true);

			const storedDebutTime = localStorage.getItem('debutTime');
			if (storedDebutTime) {
				let debutTime = storedDebutTime;
				console.log(debutTime);
				console.log(storedDebutTime);

				if (dispatchCount === 0) {
					const errorMessage = "Vous devez avoir fait au moins un dispatch.";
					showErrorAlert(errorMessage);
					event.preventDefault();
					return;
				}

				const tempDispatchCount = dispatchCount;
				$.ajax({
					type: 'POST',
					url: $('#form-12').data('url'),
					data: {
						debut: debutTime,
						nbtraitement: dispatchCount
					},
					success: function (response) {
						showToast('' + tempDispatchCount);
						showSuccessAlert();
					},
					error: function (error) {
						const errorMessage = 'Une erreur vient de se produire';
						showErrorAlert(errorMessage);
					}
				});

				// Réinitialiser les valeurs
				localStorage.removeItem('debutTime');
				debutTime = null;
				dispatchCount = 0;
				updateDispatchCount();
			}
		});

		$('#plus-button').click(function () {
			let storedDebutTime = localStorage.getItem('debutTime');
			if (storedDebutTime) {
				let debutTime = storedDebutTime;
				dispatchCount++;
				updateDispatchCount();
			}
		});

		$('#moins-button').click(function () {
			let storedDebutTime = localStorage.getItem('debutTime');
			if (storedDebutTime && dispatchCount > 0) {
				let debutTime = storedDebutTime;
				dispatchCount--;
				updateDispatchCount();
			}
		});


		$('.nav-link.category-button').click(function (event) {
			event.preventDefault();
			var formId = $(this).attr('href');
			$('.form-categorie').hide();
			$(formId).show();
		});
		const debutButtons = document.querySelectorAll('.debut-button');
		const finButtons = document.querySelectorAll('.fin-button');
		const heureInputs = document.querySelectorAll('input[type="text"].heure-input');
		const categoryRadios = document.querySelectorAll('.category-radio');

		async function getServerTimeEuropeParis() {
			try {
				const response = await fetch('<?php echo site_url('Homeland/getServerTimeEuropeParis')?>');
				if (!response.ok) {
					throw new Error('Impossible de récupérer l\'heure depuis le serveur.');
				}
				const dateHeure = await response.json();
				return dateHeure;
			} catch (error) {
				console.error('Erreur : ' + error.message);
				return null;
			}
		}


		heureInputs.forEach(input => {
			input.style.display = 'none';
		});

		debutButtons.forEach(async (button, index) => {
			button.addEventListener('click', async () => {
				const parentDiv = button.closest('.form-categorie');
				const heureInput = parentDiv.querySelector('.heure-input');
				const textInputs = parentDiv.querySelectorAll('input[type="text"]');
				const categoriesExist = Array.from(categoryRadios).some(radio => radio.closest('.form-categorie') === parentDiv);
				const categorieSelected = Array.from(categoryRadios)
					.filter(radio => radio.closest('.form-categorie') === parentDiv)
					.some(radio => radio.checked);

				if (categoriesExist && !categorieSelected) {
					showErrorAlert('Veuillez d\'abord sélectionner une catégorie.');
					return;
				}

				Array.from(categoryRadios)
					.filter(radio => radio.closest('.form-categorie') === parentDiv)
					.forEach(radio => {
						radio.disabled = true;
					});

				const dateHeure = await getServerTimeEuropeParis();
				console.log('Heure du serveur :', dateHeure);

				if (dateHeure && dateHeure.includes(' ')) {
					heureInput.value = dateHeure;
					button.style.display = 'none';
					finButtons[index].style.display = 'block';
					textInputs.forEach(textInput => {
						textInput.disabled = false;
					});
				} else {
					console.error('Heure du serveur invalide.');
				}
			});
		});

		function handleFormSubmission(formId, successMessage, formDataFunction) {
			var selectedCategory = null;
			var selectedMesure = null;
			var categoryRadio = $(formId + ' .category-radio');
			if (categoryRadio.length > 0) {
				categoryRadio.change(function () {
					selectedCategory = $(this).val();
				});
			}
			$(formId + ' .mesure-radio').change(function () {
				selectedMesure = $(this).val();
			});
			$(formId + ' button[type="submit"]').click(function (event) {
				event.preventDefault();
				$(this).prop('disabled', true);
				function enableSubmitButton() {
					$(formId + ' button[type="submit"]').prop('disabled', false);
					isFormProcessing = false;
				}
				setTimeout(enableSubmitButton, 2000);
				if (selectedCategory === null && categoryRadio.length > 0) {
					const errorMessage = "Veuillez sélectionner une catégorie.";
					showErrorAlert(errorMessage);
					return;
				}
				var requiredFields = $(formId + ' [required]');
				for (var i = 0; i < requiredFields.length; i++) {
					if (!requiredFields[i].value) {
						const errorMessage = "Veuillez remplir tous les champs obligatoires.";
						showErrorAlert(errorMessage);
						return;
					}
				}
				var mesureRadioGroup = $(formId + ' .mesure-radio');

				if (mesureRadioGroup.length > 0) {
					var mesureRadioSelected = false;
					mesureRadioGroup.each(function () {
						if ($(this).is(':checked')) {
							mesureRadioSelected = true;
						}
					});

					if (!mesureRadioSelected) {
						const errorMessage = "Veuillez sélectionner une mesure prise.";
						showErrorAlert(errorMessage);
						return;
					}
				}

				var requiredFields = $(formId + ' [required]');
				for (var i = 0; i < requiredFields.length; i++) {
					if (!requiredFields[i].value) {
						const errorMessage = "Veuillez remplir tous les champs obligatoires.";
						showErrorAlert(errorMessage);
						return;
					}
				}
				var formDataToSend = formDataFunction(formId);
				$.ajax({
					type: 'POST',
					url: $(formId).data('url'),
					data: formDataToSend,
					success: function (response) {
						resetForm(formId);
						window.location.reload();
						showSuccessAlert();
						$(formId + ' .debut-button').show();
						$(formId + ' .fin-button').hide();
					},
					error: function () {
						showErrorAlert();
					}
				});
			});
		}
		handleFormSubmission('#form-1', 'Appel enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				nomClient: $(formId + ' input[name="nomclient"]').val(),
				commentaire: $(formId + ' input[name="commentaire"]').val(),
				adresse: $(formId + ' input[name="adresse"]').val(),
				contact: $(formId + ' input[name="contact"]').val(),
				mesurePrise: $(formId + ' .mesure-radio:checked').val()
			};
		});
		handleFormSubmission('#form-2', 'Mail enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				nomClient: $(formId + ' input[name="nomclient_mail"]').val(),
				commentaire: $(formId + ' input[name="prenomclient_mail"]').val(),
				adresse: $(formId + ' input[name="adresse_mail"]').val(),
				contact: $(formId + ' input[name="contact_mail"]').val(),
				mesurePrise: $(formId + ' .mesure-radio:checked').val(),
			};
		});
		handleFormSubmission('#form-3', 'Affectation enregistrée avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				dossier: $(formId + ' input[name="dossier"]').val(),
			};
		});
		handleFormSubmission('#form-4', 'Autres enregistré avec succès.', function (formId) {
			return {
				lien: $(formId + ' input[name="lien"]').val(),
				motif: $(formId + ' input[name="motif"]').val(),
				debut: $(formId + ' input[name="autres"]').val(),
			};
		});
		handleFormSubmission('#form-5', 'Comptabilité enregistrée avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				debut: $(formId + ' input[name="compta"]').val(),
			};
		});
		handleFormSubmission('#form-6', 'Juridique enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				refdossier: $(formId + ' input[name="refdossier_juridique"]').val(),
				debut: $(formId + ' input[name="juridique"]').val(),
			};
		});
		handleFormSubmission('#form-7', 'Peded enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				nomvente: $(formId + ' input[name="nomvente"]').val(),
				commentaire: $(formId + ' input[name="commentaire_peded"]').val(),
				debut: $(formId + ' input[name="peded"]').val(),
			};
		});
		handleFormSubmission('#form-8', 'Imma enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				debut: $(formId + ' input[name="imma"]').val(),
			};
		});
		handleFormSubmission('#form-9', 'Sinistre enregistré avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				numdossier: $(formId + ' input[name="numdossier_sinistre"]').val(),
				debut: $(formId + ' input[name="sinistre"]').val(),
			};
		});
		handleFormSubmission('#form-10', 'Données techniques enregistrées avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				adresse: $(formId + ' input[name="adresse_technique"]').val(),
				debut: $(formId + ' input[name="technique"]').val(),
			};
		});
		handleFormSubmission('#form-11', 'MAJHBO enregistré avec succès.', function (formId) {
			return {
				typemaj: $(formId + ' input[name="typemaj"]').val(),
				lien: $(formId + ' input[name="lien"]').val(),
			};
		});

		handleFormSubmission('#form-13', 'Données paramétrages enregistrées avec succès.', function (formId) {
			return {
				categorieId: $(formId + ' .category-radio:checked').val(),
				lien: $(formId + ' input[name="lien_parametrage"]').val(),
				debut: $(formId + ' input[name="parametrage"]').val(),
				adresse: $(formId + ' input[name="adcopro_parametrage"]').val(),
			};
		});

		handleFormSubmission('#form-12', '', function (formId) {
			return {
				lien_dispatch: $(formId + ' input[name="lien_dispatch"]').val(),
			};
		});


	});
</script>
<style>
	.pole-button.active .category-button {
		color: white;
	}

	.category-list {
		max-height: 500px;
		overflow-y: auto;
		margin-top: 10px;
	}
	.category-list::-webkit-scrollbar {
		width: 3px;
	}

	.category-list::-webkit-scrollbar-thumb {
		background-color: #007BFF;
		border-radius: 10px;
	}

	.category-list::-webkit-scrollbar-track {
		background-color: #f1f1f1;
	}
	.category-list {
		scrollbar-width: thin;
	}

	.category-list::-webkit-scrollbar-thumb {
		background-color: #007BFF;
	}
	.category-item label.btn {
		width: 100%;
		text-align: left;
	}
	.category-item input.btn-check {
		display: none;
	}

	.category-item input.btn-check:checked + label.btn {
		background-color: #007BFF;
		color: white;
	}

	.category-item label.btn {
		border: 1px solid #ccc;
		border-radius: 0;
	}
	.category-item button {
		width: 100%;
	}

</style>
