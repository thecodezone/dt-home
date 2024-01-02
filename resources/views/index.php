<?php
$this->layout( 'layouts/plugin' );
?>

<div>
    <b>
        Name: <?php echo $this->e( $user->user_nicename ); ?>
    </b>
</div>

<a href="<?php echo esc_url( $subpage_url ); ?>">
	<?php $this->esc_html_e( 'Visit subpage', 'dt-launcher' ); ?>
</a>
