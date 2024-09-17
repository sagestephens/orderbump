jQuery(document).ready(function($) {
    $('#custom-checkout-button').on('click', function() {
        $('#upsell-modal').show();
        let countdown = 10;
        let timerInterval = setInterval(function() {
            countdown--;
            $('#countdown').text(countdown);
            if (countdown <= 0) {
                clearInterval(timerInterval);
                $('#upsell-modal').hide();
                $('form.checkout').submit();
            }
        }, 1000);
    });

    $('#no-thanks-button').on('click', function() {
        $('#upsell-modal').hide();
        $('form.checkout').submit();
    });

    $('#add-upsell-button').on('click', function() {
        var productId = $(this).data('product-id');
        console.log('Product ID:', productId); // Debugging line
        $.ajax({
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
            type: 'POST',
            data: { product_id: productId, quantity: 1 },
            success: function(response) {
                console.log('Response:', response); // Debugging line
                if (response.error) {
                    alert('Error adding product to cart: ' + response.error_message);
                } else {
                    alert('Product added to cart!');
                    $(document.body).trigger('wc_fragment_refresh');
                    $('#upsell-modal').hide();
                    $('form.checkout').submit();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // Error logging
                alert('An error occurred while adding the product to the cart.');
            }
        });
    });
});
