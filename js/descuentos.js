$(function() {

    // on submit...
    $("#submitdesc").click(function() {


        //Capturo variables
        var codigo = $("input#descuento").val();


        var parametros = { "codigo": codigo };
        $.ajax({
            url: WEB_ROOT + '/ajax/validar-descuento.php',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
            },

            success: function(data) {
                if (data == "descuento-no-existe") {
                    error = '<p class="text-danger"><i class="fas fa-times"></i> El código de descuento no es válido</p>';
                    $("#result-descuentos").html(error).fadeIn('slow');
                    document.body.classList.remove('loading');
                } else {
                    $("#result-descuentos").html(data).fadeIn('slow');
                    document.body.classList.remove('loading');
                }

            }
        });



    });


});