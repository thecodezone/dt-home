<?php echo $this->layout( 'layouts/plugin' ); ?>

<div>
    <b>
        Logged in as <?php esc_attr_e( $user->user_nicename ) ?>!
    </b>
</div>
