$(function() {

    //Cargar carro
    loadCart();

    //Actualizar carro de compras
    $(document).on('change', '.box_cant_cart', function(e) {
        e.preventDefault();
        document.body.classList.add('loading');

        var pid = $(this).data('id'); // get id of clicked row
        var cantidad = $("input#txtQty" + pid).val();
        var idprec = $("input#prec" + pid).val();

        if (cantidad == 0) {
            toastr["error"]("<strong>Error!</strong><br>Ingrese la cantidad");
            document.body.classList.remove('loading');
            return false;
        }

        if (window.XMLHttpRequest) {
            objetoAjax = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        objetoAjax.onreadystatechange = mostrar2;
        objetoAjax.open('POST', WEB_ROOT + '/cart/verificar_stock_cart.php', true);
        objetoAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var parametros = "cantidad=" + cantidad +
            '&idpr=' + idprec;

        objetoAjax.send(parametros);

        function mostrar2() {
            if (objetoAjax.readyState == 4) {
                if (objetoAjax.status == 200) {
                    if (objetoAjax.responseText != 0) {
                        toastr["error"]("<strong>Que lástima!</strong><br>Solo disponemos <strong>" + objetoAjax.responseText + "</strong> unidades");
                        loadCart();
                        document.body.classList.remove('loading');
                        return false;
                    } else {
                        var parametros = { "idCart": pid, "cant": cantidad };
                        $.ajax({
                            url: WEB_ROOT + '/cart/action-cart.php?action=update',
                            data: parametros,
                            beforeSend: function(objeto) {
                                document.body.classList.add('loading');
                            },

                            success: function(data) {
                                $("#outer_div").html(data).fadeIn('slow');
                                countCart();
                                document.body.classList.remove('loading');
                                toastr["success"]("<strong>Cantidad actualizada</strong>");
                            }
                        });

                    }
                }
            }
        }

    });

});


function loadCart() {
    var parametros = {};
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=load',
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


function addToCart() {

    $(".error_addcart").hide();

    //variaciones
    if (document.getElementsByName("variacion").length != 0) {
        variacion = document.getElementsByName("variacion");
        var itemselected = false;
        for (var i = 0; i < variacion.length; i++) {
            if (variacion[i].checked) {
                itemselected = true;
                break;
            }
        }

        if (!itemselected) {
            toastr["error"]("<strong>Error!</strong><br>Debes seleccionar una opción");
            return false;
        }
    }

    //cantidad
    var cantidad = $("input#cant").val();

    if (cantidad == 0) {
        toastr["error"]("<strong>Error!</strong><br>Ingrese la cantidad");
        return false;
    }


    if (window.XMLHttpRequest) {
        objetoAjax = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    objetoAjax.onreadystatechange = mostrar;
    objetoAjax.open('POST', WEB_ROOT + '/cart/verificar_stock_cart.php', true);
    objetoAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var idprec = $("input#precio").val();

    var parametros = "cantidad=" + cantidad +
        '&idpr=' + idprec;

    objetoAjax.send(parametros);

    function mostrar() {
        if (objetoAjax.readyState == 4) {
            if (objetoAjax.status == 200) {
                if (objetoAjax.responseText != 0) {
                    toastr["error"]("<strong>Que lástima!</strong><br>Solo disponemos <strong>" + objetoAjax.responseText + "</strong> unidades");
                    return false;
                } else {

                    $.ajax({
                        url: WEB_ROOT + "/cart/action-cart.php?action=add",
                        data: $('#datcart').serialize(),
                        beforeSend: function(objeto) {
                            document.body.classList.add('loading');
                        },

                        success: function(data) {
                            $("#outer_div").html(data).fadeIn('slow');
                            countCart();
                            document.body.classList.remove('loading');
                            $('#pop_cart').modal('show');
                            toastr["success"]("<strong>Genial!</strong><br>Agregaste un producto al carro");
                        }
                    });

                }
            }
        }
    }
}

function addMixToCart(idMix) {

    var nombreMix = $("#nombreMix").val();
    var descripcionMix = $("#descripcionMix").val();
    if ($("input[name=modelo]:checked").val()) {
        var modelo = $("input[name=modelo]:checked").val();
    } else {
        var modelo = 0;
    }


    var parametros = { "idMix": idMix, "nombreMix": nombreMix, "descripcionMix": descripcionMix, "modelo": modelo };
    $.ajax({
        url: WEB_ROOT + '/cart/action-cart.php?action=addMix',
        data: parametros,
        beforeSend: function(objeto) {
            document.body.classList.add('loading');
        },
        success: function(data) {
            if (data == "error") {
                document.body.classList.remove('loading');
                toastr["error"]("<strong>UPS!</strong><br>Algo no andubo bien");
            } else {
                $("#outer_div").html(data).fadeIn('slow');
                countCart();
                document.body.classList.remove('loading');
                $('#pop_cart').modal('show');
                toastr["success"]("<strong>Genial!</strong><br>Agregaste tu mix al carro");
            }
        }
    });
}


function actualizarPrec(id) {
    var param = 'idprec=' + id;

    $.ajax({
        data: param,
        type: "GET",
        dataType: "json",
        url: WEB_ROOT + "/cart/actualizar-precio-ficha.php",
        success: function(data) {

            if (data.length > 0) {
                $.each(data, function(i, item) {
                    html = '<input type="hidden" name="precio" id="precio" value="' + item.id + '">';
                    if (item.stock == 1) {
                        stock = 'Último disponible';
                    } else {
                        stock = item.stock + ' disponibles';
                    }
                    if (item.precioorig == item.preciofinal) {
                        precio = '<p>$' + item.preciofinal + '</p>';
                    } else {
                        precio = '<p>$' + item.preciofinal + '</p><small><del>' + item.precioorig + '</del></small>';
                    }
                });
            }

            $(".act_prec").html(html);
            $(".stock").html(stock);
            $(".price").html(precio);
        }
    });
}