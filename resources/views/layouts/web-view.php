<div class="cloak">
    <div class="container">
        <dt-tile>
            <div class="section__inner">
                <?php $user = wp_get_current_user();
                ?>
                <?php echo $this->section( 'content' ) ?>
            </div>
        </dt-tile>
    </div>
</div>

