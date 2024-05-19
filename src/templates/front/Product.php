<?php
/**
 * Template Name: Product Template
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        ?>
        <div class="lightcommerce-single-product">
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
            <?php
            // Add to Cart Button
            $product_id = get_the_ID();
            ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="add_to_cart">
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                <button type="submit" class="button"><?php _e('Add to Cart', 'light-commerce'); ?></button>
            </form>
        </div>
        <?php
    }
}

get_footer();
