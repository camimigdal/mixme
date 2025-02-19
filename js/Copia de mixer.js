$(function() {

    //Cuando seleccionamos el pack con el peso
    $(document).on('change', 'input[name=peso]', function(e) {
        e.preventDefault();

        //Capturo variables de las opciones de envío
        var idPeso = $(this).data('id');
        var peso = $(this).val();


        var parametros = { "peso": peso };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=create',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },

            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__tada');
                toastr["success"]("<strong>BIEN HECHO!</strong><br>Ahora selecciona una base y luego los ingredientes que te gusten.");
            }
        });

    });

    //Agrega bases
    $(document).on('click', '.addbases', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        var parametros = { "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=addbase',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                if (data == "mixer-no-creado") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>ESPERÁ!</strong><br>Primero seleccioná el packaging que prefieras y luego la base");
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    document.body.classList.remove('loading');
                    $(".tubo-mixer").addClass('animate__tada');
                    toastr["success"]("<strong>AHORA SI!</strong><br>Combina con los ingredientes que más gusten.");
                }
            }
        });
    });

    //Agrega ingredientes
    $(document).on('click', '.addingredientes', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        var parametros = { "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=add',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                if (data == "mixer-no-creado") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>ESPERÁ!</strong><br>Primero seleccioná el packaging que prefieras y luego la base");
                } else if (data == "base-vacia") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>NO TE APURES!</strong><br>Antes tenés que agregar la base");
                } else if (data == "mixer-completo") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>UH! QUE LASTIMA!</strong><br>El mixer ya está completo");
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    document.body.classList.remove('loading');
                    $(".tubo-mixer").addClass('animate__tada');

                    var aleatorio = Math.floor(Math.random() * 2);
                    var texto = texto_aleatorio[aleatorio]();
                    toastr["success"](texto);
                }
            }
        });
    });

    //vaciar mixer
    $(document).on('click', '#vaciarmix', function(e) {
        e.preventDefault();
        var idmix = $(this).data('id'); // get id of clicked row

        var parametros = { "id": idmix };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=deleteall',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                document.body.classList.remove('loading');
                toastr["success"]("<strong>COMENCEMOS DE NUEVO</strong><br>Tu mixer ahora está vacío");
            }
        });
    });

    //Elimina ingredientes
    $(document).on('click', '.btntrash', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        var parametros = { "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=delete',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__tada');
                toastr["success"]("<strong>ESO ESTABA DE MÁS</strong><br>Eliminaste ese ingrediente");
            }
        });
    });

    //Update mas ingredientes
    $(document).on('click', '.btnmas', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        var parametros = { "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=updateMas',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                if (data == "mixer-completo") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>UH! QUE LASTIMA!</strong><br>El mixer ya está completo");
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    document.body.classList.remove('loading');
                    $(".tubo-mixer").addClass('animate__tada');
                    var aleatorio = Math.floor(Math.random() * 2);
                    var texto = texto_aleatorio[aleatorio]();
                    toastr["success"](texto);
                }
            }
        });
    });

    //Update menos ingredientes
    $(document).on('click', '.btnmenos', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        var parametros = { "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=updateMenos',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__tada');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__tada');
                toastr["success"]("<strong>ESO ESTABA DE MÁS</strong><br>Eliminaste ese ingrediente");
            }
        });
    });

    //Cargar mixer
    loadCart();

});


function loadCart() {
    var parametros = {};
    $.ajax({
        url: WEB_ROOT + '/mixer/action-mixer.php?action=load',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $(".contenido-tubo").html(data).fadeIn('slow');
            countCart();
            document.body.classList.remove('loading');
        }
    });
}

function countCart() {
    var parametros = {};
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=count',
        data: parametros,
        success: function(data) {
            $("#icon_cart span").html(data).fadeIn('slow');
        }
    });
}


function deleteCart(idcart) {
    var parametros = { "idCart": idcart };
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=delete',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $("#outer_div").html(data).fadeIn('slow');
            countCart();
            document.body.classList.remove('loading');
        }
    });
}







var texto_aleatorio = [];

texto_aleatorio[0] = function() {
    var textos = new Array();
    textos[0] = "<strong>QUE RICO!</strong><br>Este mix te está quedando genial";
    textos[1] = "<strong>DELICIOSO!</strong><br>No vas a parar de comer";
    textos[2] = "<strong>UN MIX INCREIBLE!</strong><br>Perfecto para todos los días";
    textos[3] = "<strong>QUE BUENA IDEA!</strong><br>La combinación es excelente";

    var aleat = Math.random() * (textos.length);
    aleat = Math.floor(aleat);

    return textos[aleat];
};

texto_aleatorio[1] = function() {
    var textos = new Array();
    textos[0] = "<strong>SORPRENDENTE!</strong><br>Esa combinación es única";
    textos[1] = "<strong>ME ENCANTA!</strong><br>Te recomiendo guardar esto en una caja fuerte";
    textos[2] = "<strong>SUPREMO!</strong><br>Yo que vos lo patentaría";
    textos[3] = "<strong>LO QUIERO PROBAR!</strong><br>Te voy a robar la idea de la combinación";

    var aleat = Math.random() * (textos.length);
    aleat = Math.floor(aleat);

    return textos[aleat];
}