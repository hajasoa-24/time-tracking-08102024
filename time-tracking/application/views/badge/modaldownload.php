<script type="text/javascript">
$(document).ready(function() {

    $('#save_motif').on('click', function() {
        $('#form_add_image').submit();
    })

    $(document).on('click', '.download-badge', function() {
        $('#motif_incomplet').prop('checked', false);
        let agent_id = $(this).data('user');
        $('#image_agent').val(agent_id);
        $('#badge').src = "<?php echo site_url("badge/exportBadge")?>";

        window.open("<?= site_url('badge/exportBadge') ?>?usr_id=" + agent_id + "", agent_id);

    });


});
</script>