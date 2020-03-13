<div class="wrap">
    <h2><?= esc_html( get_admin_page_title() ) ?></h2>
    <p>Soll die Sammelstelle &ldquo;<?= esc_html( $sammelstelle->name ); ?>&rdquo; wirklich gelöscht werden?</p>

    <form action="<?= Sammelstellen_Admin::get_sammelstellen_url(); ?>" method="post" class="validate" novalidate="novalidate" id="sammelstellenConfirmDelete">
        <?= wp_nonce_field( Sammelstellen_Admin::DELETE_NONCE, Sammelstellen_Admin::NONCE_NAME ); ?>
        <input type="hidden" name="action" value="delete-sammelstelle"/>
        <input type="hidden" name="id" value="<?= esc_attr( $sammelstelle->id ); ?>"/>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Löschen bestätigen"/>
    </form>

</div>
