$(function(){

    $('div.WS-alert').fadeOut(7000, function(){

        $(this).remove();

    });

    $('#select_locale').change(function(){

        window.location.href = $(this).attr("target")+$(this).val() ;
    })


});