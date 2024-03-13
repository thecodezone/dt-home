<div class="cloak">
    <div class="container">
        <div>
            <div class="section__inner">
                <?php $user = wp_get_current_user();
                ?>
                <?php echo $this->section( 'content' ) ?>
            </div>
        </div>
    </div>
</div>

