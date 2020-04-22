<div class="wrap">
    <?php
        function with_breaks( $str ) {
            return str_replace( "\n", '<br/>', $str);
        }
    ?>
    <h1 class="wp-heading-inline">Sammelstellen</h1>
    <a class="page-title-action" href="<?= esc_url( Sammelstellen_Admin::get_create_sammelstelle_url() ); ?>">Neu hinzufügen</a>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th scope="col" id="name" class="manage-column column-primary">Name</th>
                <th scope="col" id="adresse" class="manage-column">Adresse</th>
                <th scope="col" id="oeffnungszeiten" class="manage-column">Öffnungszeiten</th>
                <th scope="col" id="website" class="manage-column">Website</th>
                <th scope="col" id="briefcasten" class="manage-column">Privater Briefkasten</th>
                <th scope="col" id="aktiv" class="manage-column">Aktiv</th>
                <th scope="col" id="hinweise" class="manage-column">Hinweise</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php foreach( $sammelstellen as $sammelstelle ): ?>
                <tr id="<?= esc_attr( "post-$sammelstelle->id" ); ?>" class="iedit author-self level-0 type-page status-publish hentry">
                    <td class="has-row-actions column-primary">
                        <strong><a class="row-title" href="<?= esc_url( Sammelstellen_Admin::get_edit_sammelstelle_url( $sammelstelle->id ) ); ?>" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; (Bearbeiten)"><?= esc_html( $sammelstelle->name ); ?></a></strong>
                        <div class="row-actions">
                            <span class='edit'><a href="<?= esc_url( Sammelstellen_Admin::get_edit_sammelstelle_url( $sammelstelle->id ) ); ?>" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; bearbeiten">Bearbeiten</a> | </span>
                            <span class='trash'><a href="<?= esc_url( Sammelstellen_Admin::get_delete_sammelstelle_url( $sammelstelle->id ) ); ?>" class="submitdelete" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; löschen">Löschen</a>
                    </td>
                    <td><?= with_breaks( esc_html( $sammelstelle->adresse ) ); ?></td>
                    <td><?= with_breaks( esc_html( $sammelstelle->oeffnungszeiten ) ); ?></td>
                    <td><?= esc_html( $sammelstelle->website ); ?></td>
                    <td><?= $sammelstelle->briefkasten ? 'Ja' : 'Nein' ?></td>
                    <td><?= $sammelstelle->aktiv ? 'Ja' : 'Nein' ?></td>
                    <td><?= with_breaks( esc_html( $sammelstelle->hinweise ) ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <th scope="col" class='manage-column column-primary'>Name</th>
                <th scope="col" class='manage-column'>Adresse</th>
                <th scope="col" class='manage-column'>Öffnungszeiten</th>
                <th scope="col" class='manage-column'>Aktiv</th>
                <th scope="col" class='manage-column'>Hinweise</th>
            </tr>
        </tfoot>
    </table>
</div>