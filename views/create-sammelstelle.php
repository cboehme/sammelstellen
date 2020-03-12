<div class="wrap">
    <h2><?= esc_html( get_admin_page_title() ) ?></h2>
    <p>Legt eine neue Sammelstelle an.</p>

    <?php
    $form_submission_url = Sammelstellen_Admin::get_sammelstellen_url();
    $nonce_name = Sammelstellen_Admin::CREATE_NONCE;
    $action = 'create-sammelstelle';
    $form_submit_text = 'Neue Sammelstelle hinzufügen';
    include( SAMMELSTELLEN__PLUGIN_DIR . 'views/_sammelstelle-form.php' );
    ?>
</div>
