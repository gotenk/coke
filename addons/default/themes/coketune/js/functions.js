$(document).ready(function(){
    $('#vendor-name').val($('.tabbb').find('li.current').attr('data-name'));

    $('.vendor-list').on('click',function(){
        $('#vendor-name').val($(this).attr('data-name'));
    });

    $('#code-btn').on('click',function(e){
        e.preventDefault();
        $('#input-id').submit();
    });
});
