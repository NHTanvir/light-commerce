<div class="wrap">
    <h2><?php _e('Stripe Settings', 'light-commerce'); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('stripe_options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Stripe Script Key', 'light-commerce'); ?>:</th>
                <td><input type="text" name="stripe_secret_key" value="<?php echo esc_attr(get_option('stripe_secret_key', 'sk_test_51Mw6NZH6GMgUDrK8XfLKnEDa2tY5YUYPtwPoewlEjesZykr1jfrp1vJUxxvpd2tccJFXNV8fYfBKZeUxP45vynqU007NSvESSR')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Stripe Publishable Key', 'light-commerce'); ?>:</th>
                <td><input type="text" name="stripe_publishable_key" value="<?php echo esc_attr(get_option('stripe_publishable_key', 'pk_test_51Mw6NZH6GMgUDrK88BkuCNeA3PHjkESL752Oe9KSK2WwKgDQ2hDlvPhG2AHMXljzOzVdbWOHZ9tB4ax7rx7fywqq002Tk0024T')); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
