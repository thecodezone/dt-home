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

                <?php if ( !empty( $error ) ) : ?>
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


                    <sp-button class="login__button"
                               tabindex="3"
                               type="submit">   <?php esc_html_e( 'Login', 'dt_home' ) ?>
                    </sp-button>

                    <sp-button context="link" class="create__link"
                               href="<?php echo esc_url( $register_url ); ?>"
                               tabindex="`4"
                               title="<?php esc_attr_e( 'Create Account', 'disciple-tools-autolink' ); ?>">
                        <?php esc_html_e( 'Create Account', 'disciple-tools-autolink' ) ?>
                        <dt-chevron-right></dt-chevron-right>
                    </sp-button>
                </div>
            </form>
        </div>
    </dt-tile>
    <div class="login__footer">
        <dt-button context="link"
                   href="<?php echo esc_url( $reset_url ); ?>">
            <?php esc_html_e( 'Forgot Password?', 'disciple-tools-autolink' ); ?>
        </dt-button>
    </div>

