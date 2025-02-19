jQuery(document).ready(function($) {
    loadEnvios();

    // on submit...
    $("#submitship").click(function() {
        $("#errorShip").hide();

        var c_postal = $("input#envio_codpostal").val();
        var er_c_postal = /^[0-9]{4}$/;
        if (!er_c_postal.test(c_postal)) {
            $("#errorShip").fadeIn().text("Ingrese su código postal");
            $("input#envio_codpostal").focus();
            return false;
        }

        loadEnvios();
    });

    return false;
});


function loadEnvios() {
    //codigo postal
    var c_postal = $("input#envio_codpostal").val();
    var er_c_postal = /^[0-9]{4}$/;
    if (!er_c_postal.test(c_postal)) {
        return false;
    }

    var peso = $("input#peso_envio").val();
    var cantproductos = $("input#cantproductos_envio").val();
    var total = $("input#total_envio").val();

    // ajax
    var parametros = { "c_postal": c_postal, 'peso': peso, 'cantproductos': cantproductos, 'total': total };
    $.ajax({
        url: WEB_ROOT + '/ajax/calcular-envio.php',
        data: parametros,
        beforeSend: function(objeto) {
            $("#result-envios").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>').fadeIn('slow');
        },
        success: function(data) {
            $("#result-envios").html(data).fadeIn('slow');
        }
    });
}