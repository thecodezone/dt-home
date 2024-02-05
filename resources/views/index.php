<?php
$this->layout('layouts/plugin');
?>

<div class="container">

    <dt-tile>
        <div class="section__inner">

            <sp-button id="trigger" placement="right" class="menu-button inline-element" style>
                <sp-icon-triple-gripper slot="icon"></sp-icon-triple-gripper>
            </sp-button>
            <sp-overlay trigger="trigger@click" placement="bottom">
                <sp-popover open style="position: relative">
                    <sp-dialog>
                        <?php if ($user->has_cap('access_disciple_tools')): ?>
                            <h4 slot="heading" class="menu-title"> <?php esc_html_e('Go to disciple.tools'); ?></h4>
                            <sp-menu class="right-aligned-menu">
                                <sp-menu-item>
                                    <?php esc_html_e('Training'); ?>
                                </sp-menu-item>
                                <sp-menu-item>
                                    <?php esc_html_e('Log Out'); ?>

                                </sp-menu-item>
                            </sp-menu>
                        <?php endif; ?>
                    </sp-dialog>
                </sp-popover>
            </sp-overlay>

            <div>
                <?php echo $this->section('content') ?>
            </div>

            <b>
                Name: <?php echo $this->e($user->user_nicename); ?>
            </b>

            <a href="<?php echo esc_url($subpage_url); ?>">
                <?php $this->esc_html_e('Visit subpage', 'dt-launcher'); ?>
            </a>


        </div>
    </dt-tile>
</div>

