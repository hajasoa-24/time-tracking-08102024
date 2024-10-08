<style>
      .error-border {
        border: 4px solid red !important;
      }
      .success-border {
        border: 4px solid green !important;
      }
</style>
<!-- MODAL AJOUT UTILISATEUR -->

<div
  class="modal fade"
  id="addUtilisateurModal"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  tabindex="-1"
  aria-labelledby="Ajouter un utilisateur"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
          Ajout d'un utilisateur
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <form
          method="post"
          id="form_add_utilisateur"
          action="<?= site_url('user/saveNewUtilisateur') ?>"
        >
          <div class="form-group row">
            <label for="user_nom" class="col-sm-2 col-form-label">Nom</label>
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_nom"
                class="form-control"
                id="user_nom"
                placeholder="Nom"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="user_prenom" class="col-sm-2 col-form-label"
              >Prénom</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_prenom"
                class="form-control"
                id="user_prenom"
                placeholder="Prénom"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="user_matricule" class="col-sm-2 col-form-label"
              >Matricule</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_matricule"
                class="form-control"
                id="user_matricule"
                placeholder="Matricule"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="user_initiale" class="col-sm-2 col-form-label"
              >Initiale</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_initiale"
                class="form-control"
                id="user_initiale"
                placeholder="Initiale"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"
              >Email</label
            >
            <div class="col-sm-10">
              <input
                type="email"
                name="user_email"
                class="form-control"
                id="inputEmail3"
                placeholder="Email"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="date_embauche" class="col-sm-2 col-form-label"
              >Date d'embauche</label
            >
            <div class="col-sm-10">
              <input
                type="date"
                name="user_dateembauche"
                class="form-control"
                id="date_embauche"
                placeholder=""
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="list_campagne" class="col-sm-2 col-form-label"
              >Campagne</label
            >
          </div>

          <div id="campagne-list"></div>

          <div class="form-group row">
            <label for="list_service" class="col-sm-2 col-form-label"
              >Service</label
            >
          </div>

          <div id="service-list"></div>

          <div class="form-group row">
            <label for="listRole" class="col-sm-2 col-form-label">Role</label>
            <div class="col-sm-10">
              <select id="user_role" class="form-control" name="user_role">
                <option value="">-</option>
                <?php foreach ($listRole as $role) { ?>
                <option value="<?php echo $role->role_id ?>">
                  <?php echo $role->role_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="listSite" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
              <select id="user_site" class="form-control" name="user_site">
                <option value="">-</option>
                <?php foreach ($listSite as $site) { ?>
                <option value="<?php echo $site->site_id ?>">
                  <?php echo $site->site_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="listSite" class="col-sm-2 col-form-label"
              >Contrat</label
            >
            <div class="col-sm-10">
              <select
                id="user_contrat"
                class="form-control"
                name="user_contrat"
              >
                <option value="">-</option>
                <?php foreach ($listSite as $site) { ?>
                <option value="<?php echo $site->site_id ?>">
                  <?php echo $site->site_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="user_identifiant" class="col-sm-2 col-form-label"
              >Identifiant</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_identifiant"
                class="form-control"
                id="user_identifiant"
                placeholder="Identifiant"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label"
              >Password</label
            >
            <div class="col-sm-10">
              <input
                type="password"
                required="true"
                name="user_password"
                class="form-control"
                id="inputPassword3"
                placeholder="Password"
              />
            </div>
          </div>
          <div id="addmdpconfirmation">
            <div class="form-group row">
              <label
                for="addinputPasswordConfirmation"
                class="col-sm-2 col-form-label"
                >Confirmation</label
              >
              <div class="col-sm-10">
                <input
                  type="password"
                  class="form-control"
                  id="addinputPasswordConfirmation"
                  placeholder="Confirmation Password"
                />
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="user_ingress" class="col-sm-2 col-form-label"
              >Id Ingress</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="user_ingress"
                class="form-control"
                id="user_ingress"
                placeholder="Id Ingress"
              />
            </div>
          </div>

          <input type="hidden" name="send" value="sent" />
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Annuler
            </button>
            <button
              type="submit"
              id="save_utilisateur"
              value="save_utilisateur"
              class="btn btn-primary"
            >
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AJOUT UTILISATEUR -->

<!-- MODAL MODIF UTILISATEUR  -->
<div
  class="modal fade"
  id="editUtilisateurModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Modification d'un utilisateur
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>

      <form
        method="post"
        id="form_edit_utilisateur"
        action="<?= site_url('user/saveEditUtilisateur') ?>"
      >
        <div class="modal-body">
          <div class="form-group row">
            <label for="edit_user_nom" class="col-sm-2 col-form-label"
              >Nom</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="edit_user_nom"
                class="form-control"
                id="edit_user_nom"
                placeholder="Nom"
                value=""
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="edit_user_prenom" class="col-sm-2 col-form-label"
              >Prénom</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="edit_user_prenom"
                class="form-control"
                id="edit_user_prenom"
                placeholder="Prénom"
                value=""
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="edit_user_matricule" class="col-sm-2 col-form-label"
              >Matricule</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="edit_user_matricule"
                class="form-control"
                id="edit_user_matricule"
                placeholder="Matricule"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="edit_user_initiale" class="col-sm-2 col-form-label"
              >Initiale</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="edit_user_initiale"
                class="form-control"
                id="edit_user_initiale"
                placeholder="Initiale"
                value=""
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="list_campagne" class="col-sm-2 col-form-label"
              >Campagne</label
            >
          </div>

          <div id="campagne-list-edit"></div>

          <div class="form-group row">
            <label for="list_service" class="col-sm-2 col-form-label"
              >Service</label
            >
          </div>

          <div id="service-list-edit"></div>

          <div class="form-group row">
            <label for="edit_user_mail" class="col-sm-2 col-form-label"
              >Email</label
            >
            <div class="col-sm-10">
              <input
                type="email"
                name="edit_user_mail"
                class="form-control"
                id="edit_user_mail"
                placeholder="Email"
                value=""
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_user_ingress" class="col-sm-2 col-form-label"
              >ID Ingress</label
            >
            <div class="col-sm-10">
              <input
                type="text"
                required="true"
                name="edit_user_ingress"
                class="form-control"
                id="edit_user_ingress"
                placeholder="Id Ingress"
                value=""
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_user_dateembauche" class="col-sm-2 col-form-label"
              >Date d'embauche</label
            >
            <div class="col-sm-10">
              <input
                type="date"
                name="edit_user_dateembauche"
                class="form-control"
                id="edit_user_dateembauche"
                placeholder=""
                value=""
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_user_role" class="col-sm-2 col-form-label"
              >Role</label
            >
            <div class="col-sm-10">
              <select
                id="edit_user_role"
                class="form-control"
                name="edit_user_role"
              >
                <option value="">-</option>
                <?php foreach ($listRole as $role) { ?>
                <option value="<?php echo $role->role_id ?>">
                  <?php echo $role->role_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_user_site" class="col-sm-2 col-form-label"
              >Site</label
            >
            <div class="col-sm-10">
              <select
                id="edit_user_site"
                class="form-control"
                name="edit_user_site"
              >
                <option value="">-</option>
                <?php foreach ($listSite as $site) { ?>
                <option value="<?php echo $site->site_id ?>">
                  <?php echo $site->site_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_user_contrat" class="col-sm-2 col-form-label"
              >Contrat</label
            >
            <div class="col-sm-10">
              <select
                id="edit_user_contrat"
                class="form-control"
                name="edit_user_contrat"
              >
                <option value="">-</option>
                <?php foreach ($listSite as $site) { ?>
                <option value="<?php echo $site->site_id ?>">
                  <?php echo $site->site_libelle ?>
                </option>
                <?php }
                     ?>
              </select>
            </div>
          </div>
          <div>
            <div class="form-group row">
              <label for="inputPassword37" class="col-sm-2 col-form-label"
                >Password</label
              >
              <div class="col-sm-10">
                <input
                  type="password"
                  name="edit_user_password"
                  class="form-control"
                  id="inputPassword37"
                  placeholder="Password"
                />
              </div>
            </div>
            <div id="mdpconfirmation">
              <div class="form-group row">
                <label
                  for="inputPasswordConfirmation"
                  class="col-sm-2 col-form-label"
                  >Confirmation</label
                >
                <div class="col-sm-10">
                  <input
                    type="password"
                    name="edit_user_password_confiramation"
                    class="form-control"
                    id="inputPasswordConfirmation"
                    placeholder="Password"
                  />
                </div>
              </div>

              <input
                type="hidden"
                name="edit_user_password4"
                class="form-control"
                id="edit_user_pwd4"
                placeholder="Password"
              />
            </div>
          </div>

          <input type="hidden" id="edit_user_id" name="edit_user_id" />

          <input type="hidden" name="send_edit" value="sent" />
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Annuler
          </button>
          <button
            type="submit"
            id="edit_utilisateur"
            value="edit_utilisateur"
            class="btn btn-primary"
          >
            Enregister
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- END MODAL MODIF UTILISATEUR -->

<!-- Modal Import utlisateur  -->
<div
  class="modal fade"
  id="importUtlisateurModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import utilisateurs</h5>
        <button
          type="button"
          class="close"
          data-bs-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form
        method="post"
        id="form_import_user"
        action="<?= site_url('user/doImportUtilisateur') ?>"
        enctype="multipart/form-data"
      >
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleFormControlFile1"
              >Besoin d'un modèle d'import ?</label
            >
            <button
              type="button"
              class="btn btn-sm btn-default"
              id="download_tpl_import_user"
            >
              Télécharger
            </button>
          </div>

          <div class="form-group">
            <label for="exampleFormControlFile1"
              >Ajouter le fichier csv d'import des utilisateurs</label
            >
            <input
              type="file"
              class="form-control-file"
              id="user_file_import"
              name="user_file_import"
              accept=".csv"
            />
          </div>

          <input type="hidden" name="send_edit" value="sent" />
        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Annuler
          </button>
          <button
            type="submit"
            id="save_import"
            value="save_import"
            class="btn btn-primary"
          >
            Lancer l'import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END MODAL IMPORT -->

<!-- MODAL SUPPRESSION UTILISATEUR -->
<div
  class="modal fade"
  id="deleteUtilisateurModal"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  tabindex="-1"
  aria-labelledby="Supprimer un utilisateur"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
          Suppression d'un utilisateur
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>

      <div class="modal-body">
        <div class="form-group row">
          <p>Etes-vous sur de bien vouloir dÃ©sactiver cet utilisateur ?</p>
          <form
            id="confirmDesactivateUserForm"
            name="confirmDesactivateUserForm"
            action="<?= site_url('user/desactivateUtilisateur') ?>"
            method="post"
          >
            <input type="hidden" name="sendDesactivate" value="sent" />
            <input
              type="hidden"
              name="userToDesactivate"
              id="userToDesactivate"
              value=""
            />
          </form>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Annuler
        </button>
        <button
          type="button"
          id="confirmDesactivateUser"
          value="save_utilisateur"
          class="btn btn-primary"
        >
          Confirmer la desactivation
        </button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION UTILISATEUR -->

<script type="text/javascript">
  $(document).ready(function () {
    //Quand la modal d'ajout ustilisateur s'affiche
    $("#add-utilisateur").on("click", function () {
      $("#campagne-list").html("");
      add_campagne_elt(1);
      $("#service-list").html("");
      add_service_elt(1);
      $("#addUtilisateurModal").modal("show");
      $("#addmdpconfirmation").hide();
    });

    //Action sur .add_campagne_to_user
    $(document).on("click", ".add_campagne_to_user", function (e) {
      add_campagne_elt();
    });

    //Action sur .add_service_to_user
    $(document).on("click", ".add_service_to_user", function (e) {
      add_service_elt();
    });

    $("#inputPassword3").on("input", function () {
      $("#addinputPasswordConfirmation, #inputPassword3").on(
        "input",
        function () {
          var inputpass = $("#inputPassword3").val();
          var inputpasstoconfirm = $("#addinputPasswordConfirmation").val();
          console.log(inputpass);
          console.log(inputpasstoconfirm);

          if (inputpass.length > 0) {
            $("#addmdpconfirmation").show();

            if (inputpass === inputpasstoconfirm) {
              $("#addinputPasswordConfirmation").addClass("success-border");
              $("#addinputPasswordConfirmation").removeClass("error-border");
            } else {
              $("#addinputPasswordConfirmation").addClass("error-border");
              $("#addinputPasswordConfirmation").removeClass("success-border");
            }
          } else {
            $("#addmdpconfirmation").hide();
          }
        }
      );
    });
    $("#inputPassword37").on("input", function () {
      $("#inputPassword37, #inputPasswordConfirmation").on(
        "input",
        function () {
          var inputpass = $("#inputPassword37").val();
          var inputpasstoconfirm = $("#inputPasswordConfirmation").val();
          console.log(inputpass);
          console.log(inputpasstoconfirm);

          if (inputpass.length > 0) {
            $("#mdpconfirmation").show();

            if (inputpass === inputpasstoconfirm) {
              $("#inputPasswordConfirmation").addClass("success-border");
              $("#inputPasswordConfirmation").removeClass("error-border");
            } else {
              $("#inputPasswordConfirmation").addClass("error-border");
              $("#inputPasswordConfirmation").removeClass("success-border");
            }
          } else {
            $("#mdpconfirmation").hide();
          }
        }
      );
    });

    /**
     * Click sur les boutons de modification utilisateur pour chaque ligne
     */
    $(document).on("click", ".edit-utilisateur", function () {
      $("#mdpconfirmation").hide();

      let usr_id = $(this).data("user");

      $.ajax({
        url: "<?= site_url('user/getInfoUtilisateur') ?>",
        method: "POST",
        dataType: "json",
        data: { usr_id: usr_id },
        success: function (response) {
          console.log(response);
          if (!response.error) {
            console.log(response.info_user.usr_password);

            //Mise à jour des champs de la modal par rapport au json retourné
            $("#edit_user_nom").val(response.info_user.usr_nom);
            $("#edit_user_prenom").val(response.info_user.usr_prenom);
            $("#edit_user_ingress").val(response.info_user.usr_ingress);
            $("#edit_user_initiale").val(response.info_user.usr_initiale);
            $("#edit_user_mail").val(response.info_user.usr_email);
            $("#edit_user_matricule").val(response.info_user.usr_matricule);
            $("#edit_user_dateembauche").val(
              response.info_user.usr_dateembauche
            );
            $("#edit_user_role").val(response.info_user.usr_role);
            $("#edit_user_site").val(response.info_user.usr_site);
            $("#edit_user_pwd4").val(response.info_user.usr_password);

            $("#edit_user_contrat").val(response.info_user.usr_contrat);
            //$('#edit_user_service').val(response.info_user.user_service);
            $("#edit_user_id").val(response.info_user.usr_id);
            $("#edit_user_actif").val(response.info_user.usr_actif);
            //$('#edit_user_campagne').val(response.info_user.user_campagne);

            //Chargement des campagne
            load_campagne_elts(response.listCampagne);

            //chargement des services
            load_service_elts(response.listService);

            $("#editUtilisateurModal").modal("show");
          } else {
            //TODO
            alert("Erreur survenu");
          }
        },
      });
    });

    function add_campagne_elt(init = 0) {
      let nb_elt = $(".campagne-elt").length;
      let curr_elt = nb_elt + 1;
      //Prendre les elements selectionnés sur les autres select .campagne-list
      var selected_elt = $(".campagne-elt select")
        .map(function () {
          return this.value;
        })
        .get();

      let elt_empty =
        '<div class="form-group row campagne-elt-empty">' +
        '<div class="col-sm-7 offset-sm-2">' +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_campagne_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
        "</div>";

      let elt =
        '<div class="form-group row campagne-elt">' +
        '<div class="col-sm-7 offset-sm-2">' +
        '<select id="list_campagne" class="form-control campagne-elt-' +
        curr_elt +
        '" name="user_campagne_add[]">' +
        "<?php foreach ($listCampagne as $campagne) { ?>" +
        '<option value="<?php echo $campagne->campagne_id ?>"><?php echo $campagne->campagne_libelle ?></option>' +
        "<?php }" +
        "?>" +
        "</select>" +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_campagne_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_campagne_to_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
        "</div>";
      if (init == 1) {
        $("#campagne-list").append(elt_empty);
      } else {
        $("#campagne-list").append(elt);
        $(".campagne-elt-empty").remove();
      }

      //On supprime les elements déjà selectionnés dans les autres select
      $.each(selected_elt, function (k, v) {
        $(".campagne-elt-" + curr_elt + " option[value=" + v + "]").remove();
      });
    }

    function add_service_elt(init = 0) {
      let nb_elt = $(".service-elt").length;
      let curr_elt = nb_elt + 1;
      //prendre les elements selectionnés sur les autres select .service-list
      var selected_elt = $(".service-elt select")
        .map(function () {
          return this.value;
        })
        .get();

      let elt_empty =
        '<div class="form-group row service-elt-empty">' +
        '<div class="col-sm-7 offset-sm-2">' +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_service_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
        "</div>";

      let elt =
        '<div class="form-group row service-elt">' +
        '<div class="col-sm-7 offset-sm-2">' +
        '<select id="list_service" class="form-control service-elt-' +
        curr_elt +
        '" name="user_service_add[]">' +
        "<?php foreach ($listService as $service) { ?>" +
        '<option value="<?php echo $service->service_id ?>"><?php echo $service->service_libelle ?></option>' +
        "<?php }" +
        "?>" +
        "</select>" +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_service_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_service_to_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
        "</div>";
      if (init == 1) {
        $("#service-list").append(elt_empty);
      } else {
        $("#service-list").append(elt);
        $(".service-elt-empty").remove();
      }

      //On supprime les elements déjà selectionnés dans les autres select
      $.each(selected_elt, function (k, v) {
        $(".service-elt-" + curr_elt + " option[value=" + v + "]").remove();
      });
    }

    function edit_campagne_elt(cp, init = 0) {
      let nb_elt = $(".campagne-elt-edit").length;
      let curr_elt = nb_elt + 1;
      //Prendre les elements selectionnés sur les autres select .campagne-list
      var selected_elt = $(".campagne-elt-edit select")
        .map(function () {
          return this.value;
        })
        .get();

      let elt_empty =
        '<div class="form-group row campagne-elt-edit-empty">' +
        '<div class="col-sm-7 offset-sm-2">' +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_campagne_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
        "</div>";

      let elt =
        '<div class="form-group row campagne-elt-edit">' +
        '<div class="col-sm-7 offset-sm-2">' +
        '<select id="list_campagne_edit" class="form-control campagne-elt-edit-' +
        curr_elt +
        '" name="user_campagne_edit[]">' +
        "<?php foreach ($listCampagne as $campagne) { ?>" +
        '<option value="<?php echo $campagne->campagne_id ?>"><?php echo $campagne->campagne_libelle ?></option>' +
        "<?php }" +
        "?>" +
        "</select>" +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_campagne_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_campagne_to_edit_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
        "</div>";
      if (init == 1) {
        $("#campagne-list-edit").append(elt_empty);
      } else {
        $("#campagne-list-edit").append(elt);
        $(".campagne-elt-edit-empty").remove();
      }

      if (cp)
        $(
          ".campagne-elt-edit-" +
            curr_elt +
            " option[value=" +
            cp.campagne_id +
            "]"
        ).attr("selected", "selected");

      //On supprime les elements déjà selectionnés dans les autres select
      $.each(selected_elt, function (k, v) {
        $(
          ".campagne-elt-edit-" + curr_elt + " option[value=" + v + "]"
        ).remove();
      });
    }

    function edit_service_elt(serv, init = 0) {
      let nb_elt = $(".service-elt-edit").length;
      let curr_elt = nb_elt + 1;
      //Prendre les elements selectionnés sur les autres select .service-list
      var selected_elt = $(".service-elt-edit select")
        .map(function () {
          return this.value;
        })
        .get();

      let elt_empty =
        '<div class="form-group row service-elt-edit-empty">' +
        '<div class="col-sm-7 offset-sm-2">' +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_service_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
        "</div>";

      let elt =
        '<div class="form-group row service-elt-edit">' +
        '<div class="col-sm-7 offset-sm-2">' +
        '<select id="list_service_edit" class="form-control service-elt-edit-' +
        curr_elt +
        '" name="user_service_edit[]">' +
        "<?php foreach ($listService as $service) { ?>" +
        '<option value="<?php echo $service->service_id ?>"><?php echo $service->service_libelle ?></option>' +
        "<?php }" +
        "?>" +
        "</select>" +
        "</div>" +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_service_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
        '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_service_to_edit_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
        "</div>";
      if (init == 1) {
        $("#service-list-edit").append(elt_empty);
      } else {
        $("#service-list-edit").append(elt);
        $(".service-elt-edit-empty").remove();
      }

      if (serv)
        $(
          ".service-elt-edit-" +
            curr_elt +
            " option[value=" +
            serv.service_id +
            "]"
        ).attr("selected", "selected");

      //On supprime les elements déjà selectionnés dans les autres select
      $.each(selected_elt, function (k, v) {
        $(
          ".service-elt-edit-" + curr_elt + " option[value=" + v + "]"
        ).remove();
      });
    }

    $(document).on("click", ".add_campagne_to_edit_user", function (e) {
      edit_campagne_elt();
    });

    $(document).on("click", ".remove_campagne_to_user", function (e) {
      let nb_elt = $(".campagne-elt").length;
      $(this).closest(".form-group").remove();
      if (nb_elt <= 1) {
        add_campagne_elt(1);
      }
    });

    $(document).on("click", ".remove_campagne_to_edit_user", function (e) {
      let nb_elt = $(".campagne-elt-edit").length;
      $(this).closest(".form-group").remove();
      if (nb_elt <= 1) {
        edit_campagne_elt("init", 1);
      }
    });

    $(document).on("click", ".delete_campagne", function () {
      $(this).closest(".campagne_unit").remove();
    });

    $(document).on("click", ".add_service_to_edit_user", function (e) {
      edit_service_elt();
    });

    $(document).on("click", ".remove_service_to_user", function (e) {
      let nb_elt = $(".service-elt").length;
      $(this).closest(".form-group").remove();
      if (nb_elt <= 1) {
        add_service_elt(1);
      }
    });

    $(document).on("click", ".remove_service_to_edit_user", function (e) {
      let nb_elt = $(".service-elt-edit").length;
      $(this).closest(".form-group").remove();
      if (nb_elt <= 1) {
        edit_service_elt("init", 1);
      }
    });

    $(document).on("click", ".delete_service", function () {
      $(this).closest(".campagne_unit").remove();
    });

    $("#save_utilisateur").on("click", function () {
      $("#form_add_utilisateur").submit();
    });

    $("#edit_utilisateur").on("click", function () {
      $("#form_edit_utilisateur").submit();
    });

    function load_campagne_elts(list) {
      $("#campagne-list-edit").html("");
      edit_campagne_elt("init", 1);
      for (const cp of list) {
        edit_campagne_elt(cp);
      }
    }

    function load_service_elts(list) {
      $("#service-list-edit").html("");
      edit_service_elt("init", 1);
      for (const serv of list) {
        edit_service_elt(serv);
      }
    }

    $(document).on("click", ".delete-utilisateur", function () {
      let usrId = $(this).data("user");
      $("#userToDesactivate").val(usrId);
      $("#deleteUtilisateurModal").modal("show");
    });

    $(document).on("click", "#confirmDesactivateUser", function () {
      $("#confirmDesactivateUserForm").submit();
    });

    $("#import-utilisateur").on("click", function () {
      $("#importUtlisateurModal").modal("show");
    });

    $("#save_import").on("click", function () {
      $("#form_import_user").submit();
    });

    $("#download_tpl_import_user").on("click", function () {
      $.fileDownload("<?= base_url('template/tpl_import_user.csv') ?>")
        .done(function () {
          alert("Template telechargé!");
        })
        .fail(function () {
          alert("Erreur telechargement template!");
        });
    });
  });
</script>
