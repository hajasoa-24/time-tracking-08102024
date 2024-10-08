<!-- MODAL MOTIF -->
<div class="modal fade" id="addImageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="Ajouter un motif" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un image:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_add_image" action="<?= site_url('badge/uploadImage') ?>"
                    enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="inputGroupFile02" name="image">
                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    </div>
                    <input type="hidden" name="image_agent" id="image_agent" value="">
                    <input type="hidden" name="send" value="sent">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="submit" id="save_motif" value="save_motif"
                            class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- END MODAL MOTIF -->


<script type="text/javascript">
$(document).ready(function() {

    $('#save_motif').on('click', function() {
        $('#form_add_image').submit();
    })

    /**
     * Click sur les boutons gerer motif pour chaque ligne
     */
    $(document).on('click', '.edit-presence', function() {
        $('#motif_incomplet').prop('checked', false);
        //Load agent_id on modal before showing it
        let agent_id = $(this).data('agent');
        $('#image_agent').val(agent_id);


        $('#addImageModal').modal('show');
    });


});
</script>