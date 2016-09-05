$(document).ready(function(){
    $('#vendor-name').val($('.tabbb').find('li.current').attr('data-name'));

    $('.vendor-list').on('click',function(){
        $('#vendor-name').val($(this).attr('data-name'));
    });

    $('#code-btn').on('click',function(e){
        e.preventDefault();

        var vendor = $('#vendor-name').val();
        var alfamart_code = $('#alfamart-code').val();
        var transaction_code = $('#transaction-code').val();
        var indomaret_code = $('#indomaret-code').val();
        var recaptcha_response_field = $('#recaptcha-response-field').val();

        $.ajax({
            url: BASE_URL+'code-check',
            type: 'post',
            dataType: 'json',
            data: $.extend(false, tokens, {
                vendor: vendor,
                alfamart_code: alfamart_code,
                transaction_code: transaction_code,
                indomaret_code: indomaret_code,
                recaptcha_response_field: recaptcha_response_field
            }),
            success: function (result) {
                $('.error-m').html(result.message);
                if (window.grecaptcha) grecaptcha.reset();
            }
        });
    });
});
