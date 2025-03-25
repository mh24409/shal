$(document).ready(function () {
    // Capture the form submission event
    $('#shipping_info_form').submit(function (event) {
        // Prevent the default form submission
        event.preventDefault();
        // Get the form data
        var formData = $(this).serialize();
        // Call the AJAX function
        $.ajax({
            url: '/checkout/delivery_info', // Replace with your server-side script URL
            method: 'POST', // Use POST or other HTTP methods as needed
            data: formData, // Send the form data
            dataType: 'json', // Set the expected data type
            success: function (data) {
                $("#apply-step").fadeOut(400, function() {
                    $(this).html(data.html); // Replace the HTML content
                    $(this).fadeIn(400); // Fade in the new content
                });
                $("#address_collapse").collapse("hide");
                $("#info_collapse").collapse("show");
                $("#payment_collapse").collapse("hide");

            },
            error: function (xhr, status, error) {
                // Handle AJAX errors
                console.error('AJAX Error: ' + status + ' ' + error);
                console.log(xhr);
            }
        });
    });

    $(document).on('submit', '#delivery_info_form', function (event) {
        event.preventDefault();
        // Prevent the default form submission
        // Get the form data
        var formData = $(this).serialize();
        // Call the AJAX function
        $.ajax({
            url: '/checkout/payment_select', // Replace with your server-side script URL
            method: 'POST', // Use POST or other HTTP methods as needed
            data: formData, // Send the form data
            dataType: 'json', // Set the expected data type
            success: function (data) {
                $("#cart_summary").html(data.cartHTML);
                $("#cart_summary_mobile").html(data.cartHTMLMobile);
                $("#apply-step").fadeOut(400, function() {
                    $(this).html(data.html); // Replace the HTML content
                    $(this).fadeIn(400); // Fade in the new content
                });
                $("#address_collapse").collapse("hide");
                $("#info_collapse").collapse("hide");
                $("#payment_collapse").collapse("show");
            },
            error: function (xhr, status, error) {
                // Handle AJAX errors
                console.error('AJAX Error: ' + status + ' ' + error);
                console.log(xhr);
            }
        });
    });

});


$(document).ready(function() {
     $(".preloader").hide();
          $("#apply-step").fadeIn();

    $("#loadContent").click(function() {
         $(".preloader").fadeIn();
            $(".preloader").fadeOut();
    });
});
