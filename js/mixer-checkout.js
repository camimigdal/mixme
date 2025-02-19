$(function() {

    //Cuando seleccionamos el pack
    $(document).on('change', 'input[name=modelo]', function(e) {
        e.preventDefault();

        //Capturo variables de las opciones
        var modelo = $(this).val();

        $('.frente-tubo').css("background-image", "url(img/packs/modelos/fondo" + modelo + ".png)");
        $('.dorso-tubo').css("background-image", "url(img/packs/modelos/fondo" + modelo + "-dorso.png)");
    });

    $(document).on('keyup', 'input[name=nombreMix]', function(e) {
        e.preventDefault();
        //Capturo variables de las opciones de envío
        var texto = $(this).val();
        $(".title-tubo").text(texto);
    });

    $(document).on('keyup', 'textarea[name=descripcionMix]', function(e) {
        e.preventDefault();
        //Capturo variables de las opciones de envío
        var texto = $(this).val();
        $(".descripcion-tubo").text(texto);
    });

    //Cargar mixer
    loadMixCheckout();
    loadDatosMixer();

});


function loadMixCheckout() {
    var idmix = $("#idmix").val();

    var parametros = { "idmix": idmix };
    $.ajax({
        url: WEB_ROOT + '/mixer/action-mixer-checkout.php?action=loadCheckout',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $(".resumen-mix").html(data).fadeIn('slow');
            loadValNutri();
            document.body.classList.remove('loading');
        }
    });
}


function loadDatosMixer() {
    var nombreMix = $('#nombreMix').val();
    var descripcionMix = $('#descripcionMix').val();

    $(".title-tubo").text(nombreMix);
    $(".descripcion-tubo").text(descripcionMix);
}


function loadValNutri() {
    var idmix = $("#idmix").val();

    var parametros = { "idmix": idmix };
    $.ajax({
        url: WEB_ROOT + '/mixer/etiqueta-checkout.php',
        data: parametros,
        success: function(data) {
            $(".content-val-nut").html(data).fadeIn('slow');
            loadDatosMixer();
        }
    });
}