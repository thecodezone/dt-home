<?php
/**
 * @var string $home_url
 */
$this->layout( 'layouts/plugin' );
?>

<div>
    <b>
        Subpage
    </b>
</div>

<a href="<?php echo $this->e( $home_url ); ?>">
	<?php $this->esc_html_e( 'Visit home', 'dt_home' ); ?>
</a>
