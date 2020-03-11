<div class="wrap">
    <h1 class="wp-heading-inline">Sammelstellen</h1>
    <a class="page-title-action" href="<?= esc_url( Sammelstellen_Admin::get_page_url() ); ?>">Neu hinzufügen</a>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <td id='cb' class='manage-column column-cb check-column'><label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox" /></td>
                <th scope="col" id='name' class='manage-column column-primary'>Name</th>
                <th scope="col" id='adresse' class='manage-column'>Adresse</th>
                <th scope="col" id='oeffnungszeiten' class='manage-column'>Öffnungszeiten</th>
                <th scope="col" id='aktiv' class='manage-column'>Aktiv</th>
                <th scope="col" id='hinweise' class='manage-column'>Hinweise</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php foreach( $sammelstellen as $sammelstelle ): ?>
                <tr id="post-<?= esc_html( $sammelstelle->id ); ?>" class="iedit author-self level-0 type-page status-publish hentry">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text" for="cb-select-<?= esc_html( $sammelstelle->id ); ?>"><?= esc_html( $sammelstelle->name ); ?> auswählen</label>
                        <input id="cb-select-<?= esc_html( $sammelstelle->id ); ?>" type="checkbox" name="sammelstellen[]" value="<?= esc_html($sammelstelle->id); ?>" />
                    </th>
                    <td class="has-row-actions column-primary">
                        <strong><a class="row-title" href="<?= esc_url( Sammelstellen_Admin::get_edit_sammelstelle_url( $sammelstelle->id ) ); ?>" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; (Bearbeiten)"><?= esc_html( $sammelstelle->name ); ?></a></strong>
                        <div class="row-actions">
                            <span class='edit'><a href="<?= esc_url( Sammelstellen_Admin::get_edit_sammelstelle_url( $sammelstelle->id ) ); ?>" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; bearbeiten">Bearbeiten</a> | </span>
                            <!--<span class='trash'><a href="http://localhost:8000/wp-admin/post.php?post=2&amp;action=trash&amp;_wpnonce=dc13470be3" class="submitdelete" aria-label="&#8222;<?= esc_html( $sammelstelle->name ); ?>&#8220; löschen">Löschen</a>-->
                    </td>
                    <td><?= esc_html( $sammelstelle->adresse ); ?></td>
                    <td><?= esc_html( $sammelstelle->oeffnungzeiten ); ?></td>
                    <td><?= $sammelstelle->aktiv ? 'Ja' : 'Nein' ?></td>
                    <td><?= esc_html( $sammelstelle->hinweise ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td class='manage-column column-cb check-column'><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox" /></td>
                <th scope="col" class='manage-column column-primary'>Name</th>
                <th scope="col" class='manage-column'>Adresse</th>
                <th scope="col" class='manage-column'>Öffnungszeiten</th>
                <th scope="col" class='manage-column'>Aktiv</th>
                <th scope="col" class='manage-column'>Hinweise</th>
            </tr>
        </tfoot>
    </table>
</div>