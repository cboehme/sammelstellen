<div class="wrap">
    <h2><?= esc_html( get_admin_page_title() ) ?></h2>
    <p>Bearbeite eine Sammelstelle.</p>

    <?php
    $form_submission_url = Sammelstellen_Admin::get_sammelstellen_url();
    $nonce_name = Sammelstellen_Admin::EDIT_NONCE;
    $action = 'edit-sammelstelle';
    $form_submit_text = 'Änderungen speichern';
    include( SAMMELSTELLEN__PLUGIN_DIR . 'views/_sammelstelle-form.php' );
    ?>
</div>
