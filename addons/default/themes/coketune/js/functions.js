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

    $('#winner-result').on('click', '#prev', function(e){
        e.preventDefault();
        var offset = parseInt($('#pagination-pemenang').attr('data-offset'));
        var is_next = parseInt($('#pagination-pemenang').attr('data-is-next'));
        if(offset!=0){            
            ngajax( (offset - 20));
        }
    });
    
    $('#winner-result').on('click', '#next', function(e){
        e.preventDefault();        
        var is_next = parseInt($('#pagination-pemenang').attr('data-is-next'));
        if(is_next!=0){            
            ngajax(is_next);
        }
    });

    $('#icon-search').click(function(){
        ngajax_search($('#search').val());
    });

    $('#search').keyup(function(e){
        if(e.keyCode == 13){
            ngajax_search($('#search').val());
        }        
    });

    function ngajax(next){        
        $.ajax({
            url: BASE_URL+'daftar-pemenang',
            type: 'post',            
            data: $.extend(tokens, {f_offset:next}),
            success: function (result) {                
                $('#winner-result').empty();
                $('#winner-result').append(result);
            }
        });
    }

    function ngajax_search(keyword){
        $.ajax({
            url: BASE_URL+'search-pemenang',
            type: 'post',            
            data: $.extend(tokens, {keyword:keyword}),
            success: function (result) {                
                $('#winner-result').empty();
                $('#winner-result').append(result);
            }
        });
    }
});
