<?php
$this->layout('layouts/auth');
?>

<div class="container login">
    <dt-tile>
        <div class="section__inner">
            <div class="logo">
                <img
                    src="https://sample.ddev.site/wp-content/plugins/disciple-tools-autolink/magic-link//images/logo-color.png"
                    alt="Disciple.Tools"
                    class="logo__image">
            </div>
            <form action="<?php echo esc_attr($form_action) ?>"
                  method="POST">

                <?php if (!empty($error)) : ?>
                    <dt-alert context="alert"
                              dismissable>
                        <?php echo esc_html(strip_tags($error)) ?>
                    </dt-alert>
                <?php endif; ?>

                <dt-text name="username"
                         placeholder="<?php esc_attr_e('Username or Email Address', 'dt-launcher'); ?>"
                         value="<?php echo esc_attr($username); ?>"
                         required
                         tabindex="1"
                ></dt-text>
                <dt-text name="password"
                         placeholder="<?php esc_attr_e('Password', 'dt-launcher'); ?>"
                         value="<?php echo esc_attr($password); ?>"
                         type="password"
                         tabindex="2"
                         required></dt-text>

                <div class="login__buttons">
                    <dt-button context="success"
                               tabindex="3"
                               type="submit">
                        <?php esc_html_e('Login', 'dt-launcher') ?>
                    </dt-button>

                    <dt-button context="link"
                               href="<?php echo esc_url($register_url); ?>"
                               tabindex="`4"
                               title="<?php esc_attr_e('Create Account', 'disciple-tools-autolink'); ?>">
                        <?php esc_html_e('Create Account', 'disciple-tools-autolink') ?>
                        <dt-chevron-right></dt-chevron-right>
                    </dt-button>
                </div>
            </form>
        </div>
    </dt-tile>
    <div class="login__footer">
        <dt-button context="link"
                   href="<?php echo esc_url($reset_url); ?>">
            <?php esc_html_e('Forgot Password?', 'disciple-tools-autolink'); ?>
        </dt-button>
    </div>


