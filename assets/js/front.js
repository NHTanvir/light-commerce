jQuery(document).ready(function($) {
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        var product_id = $(this).data('product-id');
        var quantity = 1; // You can adjust this as needed
        var $successMessage = $(this).siblings('.success-message');

        $.ajax({
            url: '/wp-json/lightcommerce/v1/add-to-cart/',
            method: 'POST',
            data: {
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                $successMessage.html('Product added to cart successfully');

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

    $('input[name="payment_method"]').on('change', function() {
        if ($(this).val() === 'stripe') {
            $('#stripe-card-element').show();
        } else {
            $('#stripe-card-element').hide();
        }
    });


    $('#lightcommerce-checkout-form').on('submit', function(e) {
        e.preventDefault();
        
        // Check if COD is selected
        if ($('input[name="payment_method"]:checked').val() === 'cod') {
            var formData = $(this).serialize();
            
            $.ajax({
                url: lightcommerce_vars.place_order_url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#checkout-message').html('<p>' + response.message + '</p>');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(response) {
                    $('#checkout-message').html('<p>' + response.responseJSON.message + '</p>');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            });
        } else {
            // Handle Stripe payment
            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: $('#customer_name').val(),
                    email: $('#customer_email').val(),
                    address: {
                        line1: $('#customer_address').val()
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Handle errors
                    $('#checkout-message').html('<p>' + result.error.message + '</p>');
                } else {
                    // Send payment method id to server to create payment intent
                    fetch(lightcommerce_vars.create_payment_intent_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({payment_method_id: result.paymentMethod.id, customer_name: $('#customer_name').val(), customer_address: $('#customer_address').val(), customer_email: $('#customer_email').val(), payment_method: $('input[name="payment_method"]:checked').val()})
                    }).then(function(response) {
                        return response.json();
                    }).then(function(data) {
                        // Handle server response
                        if (data.success) {
                            // Payment successful
                            $('#checkout-message').html('<p>Payment successful!</p>');
                        } else {
                            // Payment failed
                            $('#checkout-message').html('<p>Payment failed!</p>');
                        }
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    });
                }
            });
        }
    });
      
});
