<header>
    <h1><?php $this->esc_html_e('Plugin', 'dt_launcher'); ?></h1>
</header>

<div class="container">
    <dt-tile>
        <div class="section__inner">
            <?php $user = wp_get_current_user();
            ?>
            <?php if ($user->has_cap('access_disciple_tools')): ?>
                <menu-component></menu-component>
            <?php endif; ?>
            <?php echo $this->section('content') ?>

            <footer class="footer">
                <p>
                    <?php $this->esc_html_e('Copyright ', 'dt_launcher'); ?>

                    <?php echo $this->e(gmdate('Y')); ?>
                </p>
            </footer>
        </div>

    </dt-tile>
</div>
