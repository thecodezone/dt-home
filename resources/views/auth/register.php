<?php
$this->layout( 'layouts/auth' );
?>
<div class="container register">
    <dt-tile class="register__background">
        <div class="section__inner">
            <div class="logo">
                <img
                    src="<?php echo esc_url( $logo_path ) ?>"
                    alt="Disciple.Tools"
                    class="logo__image">
            </div>

            <form action="<?php echo esc_attr( $form_action ) ?>"
                  method="POST">
                <?php wp_nonce_field( 'dt_autolink_register' ); ?>

                <?php if ( !empty( $error ) ) : ?>
                    <dt-alert context="alert"
                              dismissable>
                        <?php echo esc_html( $error ) ?>
                    </dt-alert>
                <?php endif; ?>

                <dt-text name="username"
                         placeholder="<?php esc_attr_e( 'Username', 'disciple-tools-autolink' ); ?>"
                         value="<?php echo esc_attr( $username ); ?>"
                         required></dt-text>
                <dt-text name="email"
                         placeholder="<?php esc_attr_e( 'Email', 'disciple-tools-autolink' ); ?>"
                         value="<?php echo esc_attr( $email ); ?>"
                         required></dt-text>
                <dt-text name="password"
                         placeholder="<?php esc_attr_e( 'Password', 'disciple-tools-autolink' ); ?>"
                         value="<?php echo esc_attr( $password ); ?>"
                         type="password"
                         required></dt-text>
                <dt-text name="confirm_password"
                         placeholder="<?php esc_attr_e( 'Confirm Password', 'disciple-tools-autolink' ); ?>"
                         value=""
                         type="password"
                         required></dt-text>

                <sp-button class="register__button"
                           tabindex="3"
                           type="submit">
                    <?php esc_html_e( 'Register', 'dt_home' ) ?>
                </sp-button>
            </form>
        </div>
    </dt-tile>
    <div class="login__footer">
        <dt-button context="link"
                   href="<?php echo esc_url( $login_url ); ?>">
            <?php esc_html_e( 'Back to Login', 'dt_home' ); ?>
        </dt-button>
    </div>
</div>
