<?php

$this->layout( 'layouts/settings', compact( 'tab', 'link', 'page_title' ) )
?>

    <form method="post">
		<?php wp_nonce_field( 'dt_admin_form', 'dt_admin_form_nonce' ) ?>

        <!-- Add a form -->
    </form>

<?php $this->start( 'right' ) ?>

    <!-- Add some content to the right side -->

<?php $this->stop() ?>