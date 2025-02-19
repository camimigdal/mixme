		$(function() {
		    load(1);
		});

		function load(page) {
		    var query = $("#busqueda").val();
		    var cat = $("#categoria").val();
		    var col = $("#color").val();
		    var orden = $("[name=ord]:checked").val();

		    var per_page = 24;
		    var parametros = { "action": "ajax", "page": page, 'query': query, 'cat': cat, 'col': col, 'orden': orden, 'per_page': per_page };
		    $.ajax({
		        url: WEB_ROOT + '/ajax/listar-productos.php',
		        data: parametros,
		        beforeSend: function(objeto) {
		            document.body.classList.add('loading');
		        },
		        success: function(data) {
		            $("#grilla-productos").html(data).fadeIn('slow');
		            document.body.classList.remove('loading');
		        }
		    })
		}