jQuery(document).ready(function($) {
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        var product_id = $(this).data('product-id');
        var quantity = 1; // You can adjust this as needed

        $.ajax({
            url: '/wp-json/lightcommerce/v1/add-to-cart/',
            method: 'POST',
            data: {
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                // Handle success, e.g., show a success message
                console.log('Product added to cart successfully');
            },
            error: function(xhr, status, error) {
                // Handle error, e.g., display an error message
                console.error('Error adding product to cart:', error);
            }
        });
    });

    $('.remove-from-cart-btn').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        // Send an AJAX request to remove the product from the cart via the API
        $.ajax({
            url: '/wp-json/lightcommerce/v1/remove-from-cart/',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                // Reload the page to update the cart display
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error, e.g., display an error message
                console.error('Error removing product from cart:', error);
            }
        });
    });
});

