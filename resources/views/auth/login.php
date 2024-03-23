<?php
$this->layout( 'layouts/auth' );
?>

<div class="container login">
    <dt-tile class="login__background">
        <div class="section__inner">
            <div class="logo">
                <img
                        src="<?php echo esc_url( $logo_path ) ?>"
                        alt="Disciple.Tools"
                        class="logo__image">
            </div>
            <form action="<?php echo esc_attr( $form_action ) ?>"
                  method="POST">

				<?php if ( ! empty( $error ) ) : ?>
                    <dt-alert context="alert"
                              dismissable>
						<?php echo esc_html( strip_tags( $error ) ) ?>
                    </dt-alert>
				<?php endif; ?>

                <dt-text name="username"
                         placeholder="<?php esc_attr_e( 'Username or Email Address', 'dt_home' ); ?>"
                         value="<?php echo esc_attr( $username ); ?>"
                         required
                         tabindex="1"
                ></dt-text>
                <dt-text name="password"
                         placeholder="<?php esc_attr_e( 'Password', 'dt_home' ); ?>"
                         value="<?php echo esc_attr( $password ); ?>"
                         type="password"
                         tabindex="2"
                         required></dt-text>

                <sp-button-group>
                    <sp-button tabindex="3"
                               type="submit">
						<?php esc_html_e( 'Login', 'dt_home' ) ?>
                    </sp-button>

                    <sp-button href="<?php echo esc_url( $register_url ); ?>"
                               variant="secondary"
                               tabindex="`4"
                               title="<?php esc_attr_e( 'Create Account', 'disciple-tools-autolink' ); ?>">
						<?php esc_html_e( 'Create Account', 'disciple-tools-autolink' ) ?>
                    </sp-button>
                </sp-button-group>
            </form>
        </div>
    </dt-tile>
</div>
<div class="login__footer">
    <sp-button href="<?php echo esc_url( $reset_url ); ?>"
               variant="secondary"
               treatment="link"
    >
		<?php esc_html_e( 'Forgot Password?', 'disciple-tools-autolink' ); ?>
    </sp-button>
</div>

