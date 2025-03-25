
$(document).ready(function () {

    $('#openRegisterModal').click(function () {
        $('#loginModal').modal('hide'); // Close Modal 1
        $('#registrationModal').modal('show'); // Open Modal 2
    });
    $(".toggle-sidebar").click(function () {
        var dataValue = $(this).data("value");
        $("." + dataValue).toggleClass("opened");
    });

});

function closeDropdown(card) {
    var dropdownMenu = document.querySelector('.' + card);
    dropdownMenu.classList.toggle('show')
}
$(document).ready(function () {
    $(".collapse-button").click(function () {
        $(this).find(".plus-show, .minus-show").toggleClass("status-active");
    });
});
$(document).ready(function () {
    $("#close-shop-ad").click(function () {
        $(".search-top-ad").addClass('hide-top-ad');
    });
});
var dir = $('html').attr('dir');
if (dir == 'rtl') {
    $(document).ready(function () {
        $('#top-news-banner').eocjsNewsticker({
            speed: 25,
            divider: ' --- ',
            direction: 'rtl'
        });
    });
} else {
    $(document).ready(function () {
        $('#top-news-banner').eocjsNewsticker({
            speed: 25,
            divider: ' --- '
        });
    });
}


$(document).ready(function () {
    $("#close_sidebar").click(function () {
        $("#sidebar-filter-container").toggleClass("col-xl-3 d-none");
        $("#products-container").toggleClass("col-xl-9 col-xl-12");
        $(".filter_plus_minus_icon").toggleClass("fa-plus fa-minus");
    });

});

function DisplayRegisterLoginForm() {
    $('#loginFormDisapled').toggleClass('d-none');
    $('#registerFormDisapled').toggleClass('d-none');
}
var tamaraWidgetConfig = {
    lang: "ar",
    country: "sa",
    publicKey: "",
    css: ['.tamara-summary-widget__content{    font-family: "WebFont";font-weight:bold;z-index: 2; position: relative; background-color: white; padding: 5px 6px; border-radius: 0px 0px 0px 27px;}.tamara-summary-widget--inline-outlined::after {content: ""; position: absolute !important; top: 0px; left: 0px; width: calc(100%); height: calc(100%); margin: 0px !important; position: relative; border-radius: 0px 0px 0px 27px; background: linear-gradient(to right, #ED48FF, #00DEFF); /* z-index: -1; */ padding: 0px !important;}.tamara-summary-widget--inline-outlined {border-radius: 0px 0px 0px 27px !important ;position: relative; background-color: white; border: unset;}.tamara-summary-widget__container { display:flex;justify-content:space-between;align-items:center; gap:15px; font-size:15px; margin-bottom: 15px;    padding: 1px !important; margin-top: 16px; border: 3px solid transparent; border-image: linear-gradient(to right, #ED48FF, #00DEFF);border-image-slice: 1;}.tamara-summary-widget__container { cursor: pointer; display: block; font-family: var(--font-secondary); font-size: 14px; font-weight: 400; line-height: unset; white-space: normal; }'],
    style: { // Optional to define CSS variable
        fontSize: '16px',
        badgeRatio: 1, // The radio of logo, we can make it big or small by changing the radio.
    }
}

$('#accordion1').on('show.bs.collapse', function () {
    $('#description-plus-icon').hide();
    $('#description-minus-icon').show();
})
$('#accordion1').on('hide.bs.collapse', function () {
    $('#description-plus-icon').show();
    $('#description-minus-icon').hide();
})
$('#accordion2').on('show.bs.collapse', function () {
    $('#details-plus-icon').hide();
    $('#details-minus-icon').show();
})
$('#accordion2').on('hide.bs.collapse', function () {
    $('#details-plus-icon').show();
    $('#details-minus-icon').hide();
})
$('#accordion3').on('show.bs.collapse', function () {
    $('#return-plus-icon').hide();
    $('#return-minus-icon').show();
})
$('#accordion3').on('hide.bs.collapse', function () {
    $('#return-plus-icon').show();
    $('#return-minus-icon').hide();
})

$('#accordion4').on('show.bs.collapse', function () {
    $('#guarantee-plus-icon').hide();
    $('#guarantee-minus-icon').show();
})
$('#accordion4').on('hide.bs.collapse', function () {
    $('#guarantee-plus-icon').show();
    $('#guarantee-minus-icon').hide();
})


$('#profile_info').on('show.bs.collapse', function () {
    $('#profile-plus-icon').hide();
    $('#profile-minus-icon').show();
})
$('#profile_info').on('hide.bs.collapse', function () {
    $('#profile-plus-icon').show();
    $('#profile-minus-icon').hide();
})

$('#orders_info').on('show.bs.collapse', function () {
    $('#orders-plus-icon').hide();
    $('#orders-minus-icon').show();
})
$('#orders_info').on('hide.bs.collapse', function () {
    $('#orders-plus-icon').show();
    $('#orders-minus-icon').hide();
})

$('#orders_infotitle').on('show.bs.collapse', function () {
    $('#orderstitle-plus-icon').hide();
    $('#orderstitle-minus-icon').show();
})
$('#orders_infotitle').on('hide.bs.collapse', function () {
    $('#orderstitle-plus-icon').show();
    $('#orderstitle-minus-icon').hide();
})
$('#statistics_info').on('show.bs.collapse', function () {
    $('#statistics-plus-icon').hide();
    $('#statistics-minus-icon').show();
})
$('#statistics_info').on('hide.bs.collapse', function () {
    $('#statistics-plus-icon').show();
    $('#statistics-minus-icon').hide();
})

var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('header').outerHeight();

$(window).scroll(function (event) {
    didScroll = true;
});

setInterval(function () {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();

    // Make sure they scroll more than delta
    if (Math.abs(lastScrollTop - st) <= delta)
        return;
    if (st > lastScrollTop && st > navbarHeight) {
        $('.ability-sticky-top').removeClass('sticky-top');
    } else {
        if (st + $(window).height() < $(document).height()) {
            $('.ability-sticky-top').addClass('sticky-top');
        }
    }

    lastScrollTop = st;
}

$('#invoice_details').on('show.bs.collapse', function () {
    $('#invoice_details-plus-icon').hide();
    $('#invoice_details-minus-icon').show();
})
$('#invoice_details').on('hide.bs.collapse', function () {
    $('#invoice_details-plus-icon').show();
    $('#invoice_details-minus-icon').hide();
})

$('#shipping_address_collapse').on('show.bs.collapse', function () {
    $('#ShippingAddressCollapse-plus-icon').hide();
    $('#ShippingAddressCollapse-minus-icon').show();
})
$('#shipping_address_collapse').on('hide.bs.collapse', function () {
    $('#ShippingAddressCollapse-plus-icon').show();
    $('#ShippingAddressCollapse-minus-icon').hide();
})

$('#shipping_company_collapse').on('show.bs.collapse', function () {
    $('#ShippingCompanyCollapse-plus-icon').hide();
    $('#ShippingCompanyCollapse-minus-icon').show();
})
$('#shipping_company_collapse').on('hide.bs.collapse', function () {
    $('#ShippingCompanyCollapse-plus-icon').show();
    $('#ShippingCompanyCollapse-minus-icon').hide();
})



$('#payment_methods_collapse').on('show.bs.collapse', function () {
    $('#PaymentMethodsCollapse-plus-icon').hide();
    $('#PaymentMethodsCollapse-minus-icon').show();
})
$('#payment_methods_collapse').on('hide.bs.collapse', function () {
    $('#PaymentMethodsCollapse-plus-icon').show();
    $('#PaymentMethodsCollapse-minus-icon').hide();
})


$(document).ready(function () {
    const inputs = $(".otp-input-number");
    const button = $(".submit-otp");
    const otpInput = $("#verification_code");

    inputs.each(function (index1) {
        $(this).keyup(function (e) {
            const currentInput = $(this);
            const nextInput = currentInput.next();
            const prevInput = currentInput.prev();

            if (currentInput.val().length > 1) {
                currentInput.val("");
                return;
            }

            if (nextInput.length && nextInput.is(":disabled") && currentInput.val() !==
                "") {
                nextInput.removeAttr("disabled");
                nextInput.focus();
            }

            if (e.key === "Backspace") {
                inputs.each(function (index2) {
                    if (index1 <= index2 && prevInput.length) {
                        $(this).attr("disabled", true);
                        $(this).val("");
                        prevInput.focus();
                    }
                });
            }

            let otpValue = "";
            inputs.each(function () {
                otpValue += $(this).val();
            });
            otpInput.val(otpValue);

            if (!inputs.eq(3).is(":disabled") && inputs.eq(3).val() !== "") {
                button.addClass("active");
                return;
            }

            button.removeClass("active");
        });
    });

    $(window).on("load", function () {
        inputs.eq(0).focus();
    });
});

$(document).ready(function () {
    $('#otp-form').submit(function(){
        $('.request_loader').removeClass('d-none');
    })
    $('.LoginWithAjaxForm').submit(function (e) { 
        $('.request_loader').removeClass('d-none');
        e.preventDefault();
        var formData = $(this).serialize();

        var formDataArray = formData.split('&');
        var formDataObject = {};
        formDataArray.forEach(function (pair) {
            var keyValue = pair.split('=');
            formDataObject[keyValue[0]] = decodeURIComponent(keyValue[1] || '');
        }); 
        if (formDataObject.email === '' && formDataObject.phone !== '') { 
            $.ajax({
                type: 'POST',
                url: formDataObject.formUrl,
                data: formData,
                success: function(response) {
                    $('.request_loader').addClass('d-none');
                    if (response.open_otp_modal === true) {
                        $('.modal').modal('hide');
                        $('#OTPModal').modal(); 
                        if (response.phone_or_email === 'email') {
                            $('#email_text').show();
                            $('#email_response_value').html(response.value);
                            $('#phone_text').hide();
                            $('#phone_response_value').html('');
                        } else {
                            $('#phone_text').show();
                            $('#phone_response_value').html(response.value);
                            $('#email_text').hide();
                            $('#email_response_value').html('');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $('.request_loader').addClass('d-none');
                    AIZ.plugins.notify('error', "الرجاء تأكيد البيانات");
                }
            });
        } else if (formDataObject.email !== '') {
            $.ajax({
                type: 'POST',
                url: formDataObject.formUrl,
                data: formData,
                success: function(response) {
                    $('.request_loader').addClass('d-none');
                    if (response.open_otp_modal === true) {
                        $('.modal').modal('hide');
                        $('#OTPModal').modal();
                        if (response.phone_or_email === 'email') {
                            $('#email_text').show();
                            $('#email_response_value').html(response.value);
                            $('#phone_text').hide();
                            $('#phone_response_value').html('');
                        } else {
                            $('#phone_text').show();
                            $('#phone_response_value').html(response.value);
                            $('#email_text').hide();
                            $('#email_response_value').html('');
                        }
                    }
                },
                error: function(xhr, status, error) { 
                   $('.request_loader').addClass('d-none');
                    AIZ.plugins.notify('error', "الرجاء تأكيد البيانات");
                }
            });
        } else if (formDataObject.email === '' && formDataObject.phone === '') {
           $('.request_loader').addClass('d-none');
            AIZ.plugins.notify('error', "الرجاء إدخال البيانات");
        }else {
             AIZ.plugins.notify('error', "الرجاء إدخال البيانات")
        }

    });
});

function showAddNewAddress() {
    $('#addNewAddressDev').removeClass('d-none');
}
$(document).ready(function () {
    $('input[type="tel"]').css({
            'direction': 'ltr',
            'text-align': 'left'
    });
    $('.toggle-details').click(function () {
        var orderId = $(this).data('order-id');
        var targetSelector = '#details' + orderId;
        $(targetSelector).collapse('toggle');
    });
});
 
