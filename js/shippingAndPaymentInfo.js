$(document).ready(function() {

    $("#frmCheckout").validate({
        rules: {
            per_email: {
                required: true,
                email: true
            },
            envio: "required",
            envio_nombre: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            envio_apellido: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            envio_telefono: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                rangelength: [7, 13]
            },
            envio_dni: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                rangelength: [7, 11]
            },
            envio_direccion: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                maxlength: 40
            },
            envio_calle_num: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                min: 1,
                maxlength: 5
            },
            envio_piso: {
                min: 1,
                maxlength: 2
            },
            envio_dpto: {
                maxlength: 3
            },
            envio_ciudad: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            envio_provincia: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                }
            },
            env_codpostal: {
                required: {
                    depends: function() {
                        return $('input[name=envio]:checked').val() == 'D';
                    }
                },
                maxlength: 4,
                minlength: 4,
                number: true
            },
            per_nombre: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            per_apellido: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            per_telefono: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                rangelength: [7, 13]
            },
            per_dni: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                rangelength: [7, 11]
            },
            per_direccion: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                maxlength: 40
            },
            per_calle_num: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                min: 1,
                maxlength: 5
            },
            per_piso: {
                min: 1,
                maxlength: 2
            },
            per_dpto: {
                maxlength: 3
            },
            per_ciudad: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                minlength: 2,
                maxlength: 40
            },
            per_provincia: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                }
            },
            per_codpostal: {
                required: {
                    depends: function() {
                        if ($('input[name=envio]:checked').val() == 'S' || !$('#chkDatos').is(':checked')) {
                            return true
                        } else {
                            return false
                        }
                    }
                },
                maxlength: 4,
                minlength: 4,
                number: true
            },
            opcion_pago: "required",
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


});