$(function() {

    var getDevice = function() {
        if (window.innerWidth < 768) {
            // Extra Small Device
            return "xs";
        } else if (window.innerWidth < 991) {
            // Small Device
            return "sm"
        } else if (window.innerWidth < 1199) {
            // Medium Device
            return "md"
        } else {
            // Large Device
            return "lg"
        }
    };
    var device = getDevice();


    //código para manejar el tubo en version celular
    if (device == 'xs') {
        $("#pop_mixer").touchwipe({
            wipeRight: function() { $('#pop_mixer').modal('hide'); },
            min_move_x: 20,
            min_move_y: 20,
            preventDefaultEvents: false
        });
        $(".page").touchwipe({
            wipeLeft: function() { $('#pop_mixer').modal('show'); },
            min_move_x: 20,
            min_move_y: 20,
            preventDefaultEvents: false
        });
    }





    //Cuando seleccionamos el pack con el peso
    $(document).on('change', 'input[name=peso]', function(e) {
        e.preventDefault();

        //Capturo variables de las opciones de envío
        var pack = $(this).data('pack');
        var peso = $(this).val();
        var idmix = $("#idmix").val();
        var type = $("#tipomix").val();



        var parametros = { "idmix": idmix, "peso": peso, "type": type, "pack": pack };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=create',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },

            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                if (device == 'xs') {
                    $('#pop_mixer').modal('show');
                }
                if (type == '100') {
                    $("#bases")[0].scrollIntoView();
                    scrollBy(0, -300);
                }
                if (type == '101') {
                    $("#semillas")[0].scrollIntoView();
                    scrollBy(0, -300);
                }
                if (type == '102') {
                    $("#frutos-secos")[0].scrollIntoView();
                    scrollBy(0, -300);
                }
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__shakeX');
                if (type == '100') {
                    toastr["success"]("<strong>BIEN HECHO!</strong><br>Ahora selecciona una base y luego los ingredientes que te gusten.");
                }
                if (type == '101') {
                    toastr["success"]("<strong>BIEN HECHO!</strong><br>Ahora selecciona los ingredientes que te gusten.");
                }
                if (type == '102') {
                    toastr["success"]("<strong>BIEN HECHO!</strong><br>Ahora selecciona los ingredientes que te gusten.");
                }

            }
        });

    });

    //Agrega bases
    $(document).on('click', '.addbases', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row
        var idmix = $("#idmix").val();
        var type = $("#tipomix").val();

        var parametros = { "idmix": idmix, "id": pid, "type": type };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=addbase',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                if (data == "mixer-no-creado") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>ESPERÁ!</strong><br>Primero seleccioná el packaging que prefieras y luego la base");
                    $("#peso")[0].scrollIntoView();
                    scrollBy(0, -300);
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    loadValNutri();
                    if (device == 'xs') {
                        $('#pop_mixer').modal('show');
                    }
                    document.body.classList.remove('loading');
                    $(".tubo-mixer").addClass('animate__shakeX');
                    toastr["success"]("<strong>AHORA SI!</strong><br>Combina con los ingredientes que más gusten.");
                }
            }
        });
    });

    //Agrega ingredientes
    $(document).on('click', '.addingredientes', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row
        var idmix = $("#idmix").val();
        var type = $("#tipomix").val();

        var parametros = { "idmix": idmix, "id": pid, "type": type };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=add',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                if (data == "mixer-no-creado") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>ESPERÁ!</strong><br>Primero seleccioná el packaging que prefieras y luego la base");
                    $("#peso")[0].scrollIntoView();
                    scrollBy(0, -300);
                } else if (data == "base-vacia") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>NO TE APURES!</strong><br>Antes tenés que agregar la base");
                    $("#bases")[0].scrollIntoView();
                    scrollBy(0, -300);
                } else if (data == "mixer-completo") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>UH! QUE LASTIMA!</strong><br>El mixer ya está completo");
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    document.body.classList.remove('loading');
                    if (device == 'xs') {
                        $('#pop_mixer').modal('show');
                    }
                    $(".tubo-mixer").addClass('animate__shakeX');
                    loadValNutri();

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
        var type = $("#tipomix").val();

        var parametros = { "id": idmix, "type": type };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=deleteall',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                loadValNutri();
                document.body.classList.remove('loading');
                toastr["success"]("<strong>COMENCEMOS DE NUEVO</strong><br>Tu mixer ahora está vacío");
            }
        });
    });

    //Elimina ingredientes
    $(document).on('click', '.btntrash', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row
        var idmix = $("#idmix").val();

        var parametros = { "idmix": idmix, "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=delete',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                loadValNutri();
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__shakeX');
                toastr["success"]("<strong>ESO ESTABA DE MÁS</strong><br>Eliminaste ese ingrediente");
            }
        });
    });

    //Update mas ingredientes
    $(document).on('click', '.btnmas', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row
        var idmix = $("#idmix").val();

        var parametros = { "idmix": idmix, "id": pid };

        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=updateMas',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                if (data == "mixer-completo") {
                    document.body.classList.remove('loading');
                    toastr["error"]("<strong>UH! QUE LASTIMA!</strong><br>El mixer ya está completo");
                } else {
                    $(".contenido-tubo").html(data).fadeIn('slow');
                    loadValNutri();
                    document.body.classList.remove('loading');
                    $(".tubo-mixer").addClass('animate__shakeX');
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
        var idmix = $("#idmix").val();

        var parametros = { "idmix": idmix, "id": pid };
        $.ajax({
            url: WEB_ROOT + '/mixer/action-mixer.php?action=updateMenos',
            data: parametros,
            beforeSend: function(objeto) {
                document.body.classList.add('loading');
                $(".tubo-mixer").removeClass('animate__shakeX');
            },
            success: function(data) {
                $(".contenido-tubo").html(data).fadeIn('slow');
                loadValNutri();
                document.body.classList.remove('loading');
                $(".tubo-mixer").addClass('animate__shakeX');
                toastr["success"]("<strong>ESO ESTABA DE MÁS</strong><br>Eliminaste ese ingrediente");
            }
        });
    });

    //Boton más info
    $(document).on('click', '#getMasinfo', function(e) {
        e.preventDefault();
        var pid = $(this).data('id'); // get id of clicked row

        $.ajax({
                url: WEB_ROOT + '/mixer/mas-info.php',
                type: 'POST',
                data: 'id=' + pid,
                dataType: 'html'
            })
            .done(function(data) {
                $('#content-mas-info').html(data); // load here
            })
            .fail(function() {
                $('#content-mas-info').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, intente nuevamente...');
            });
    });


    //Cargar mixer
    loadMixer();
    loadPesos();

});


function loadMixer() {
    var idmix = $("#idmix").val();
    var type = $("#tipomix").val();

    var parametros = { "idmix": idmix, "type": type };
    $.ajax({
        url: WEB_ROOT + '/mixer/action-mixer.php?action=load',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },

        success: function(data) {
            $(".contenido-tubo").html(data).fadeIn('slow');
            loadValNutri();
            document.body.classList.remove('loading');
        }
    });
}

function loadValNutri() {
    var idmix = $("#idmix").val();
    var type = $("#tipomix").val();

    var parametros = { "idmix": idmix, "type": type };
    $.ajax({
        url: WEB_ROOT + '/mixer/valores-nutrionales.php',
        data: parametros,
        success: function(data) {
            $(".content-val-nut").html(data).fadeIn('slow');
        }
    });
}

function loadPesos() {
    var idmix = $("#idmix").val();
    var type = $("#tipomix").val();

    var parametros = { "idmix": idmix, "type": type };
    $.ajax({
        url: WEB_ROOT + '/mixer/pesos-mixer.php',
        data: parametros,
        success: function(data) {
            $(".mixer-packs-list").html(data).fadeIn('slow');
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