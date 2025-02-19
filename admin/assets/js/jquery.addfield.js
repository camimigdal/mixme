$(document).ready(function() {
    //ACA le asigno el evento click a cada boton de la clase bt_plus y llamo a la funcion addField
    $(".bt_plus").each(function(el) {
        $(this).bind("click", addField);
    });
    $(".bt_menos").each(function(el) {
        $(this).bind("click", delRow);
    });

    $(".bt_pluscol").each(function(el) {
        $(this).bind("click", addColor);
    });
    $(".bt_menoscol").each(function(el) {
        $(this).bind("click", delRow);
    });
});


function addField() {
    // ID del elemento div quitandole la palabra "div_" de delante. Pasi asi poder aumentar el número. Esta parte no es necesaria pero yo la utilizaba ya que cada campo de mi formulario tenia un autosuggest , así que dejo como seria por si a alguien le hace falta.

    var clickID = parseInt($(this).parent('div').attr('id').replace('div_', ''));

    // Genero el nuevo numero id
    var newID = (clickID + 1);

    // Creo un clon del elemento div que contiene los campos de texto
    $newClone = $('#div_' + clickID).clone(true);

    //Le asigno el nuevo numero id
    $newClone.attr("id", 'div_' + newID);

    //Asigno nuevo id al primer campo input dentro del div y le borro cualquier valor que tenga asi no copia lo ultimo que hayas escrito.(igual que antes no es necesario tener un id)
    $newClone.children("input").eq(0).val('');
    $newClone.children("input").eq(1).val('');
    $newClone.children("input").eq(2).val('');
    $newClone.children("input").eq(3).val('');
    $newClone.children("input").eq(4).val('');

    //Asigno nuevo id al boton
    $newClone.children("input").eq(5).attr("id", newID)

    //Inserto el div clonado y modificado despues del div original
    $newClone.insertAfter($('#div_' + clickID));

    //Cambio el signo "+" por el signo "-" y le quito el evento addfield
    $("#" + clickID).val('- Quitar variación').unbind("click", addField);

    //Ahora le asigno el evento delRow para que borre la fial en caso de hacer click
    $("#" + clickID).bind("click", delRow);
}



function addColor() {
    // ID del elemento div quitandole la palabra "div_" de delante. Pasi asi poder aumentar el número. Esta parte no es necesaria pero yo la utilizaba ya que cada campo de mi formulario tenia un autosuggest , así que dejo como seria por si a alguien le hace falta.

    var clickID = parseInt($(this).parent('div').attr('id').replace('divcol_', ''));

    // Genero el nuevo numero id
    var newID = (clickID + 1);

    // Creo un clon del elemento div que contiene los campos de texto
    $newClone = $('#divcol_' + clickID).clone(true);

    //Le asigno el nuevo numero id
    $newClone.attr("id", 'divcol_' + newID);

    //Asigno nuevo id al primer campo input dentro del div y le borro cualquier valor que tenga asi no copia lo ultimo que hayas escrito.(igual que antes no es necesario tener un id)
    $newClone.children("input").eq(0).val('');
    $newClone.children("input").eq(1).val('');
    $newClone.children("input").eq(2).val('');
    $newClone.children("select").eq(0).val('');

    //Asigno nuevo id al boton
    $newClone.children("input").eq(3).attr("id", 'cl' + newID)

    //Inserto el div clonado y modificado despues del div original
    $newClone.insertAfter($('#divcol_' + clickID));

    //Cambio el signo "+" por el signo "-" y le quito el evento addfield
    $("#cl" + clickID).val('- Quitar color').unbind("click", addColor);

    //Ahora le asigno el evento delRow para que borre la fial en caso de hacer click
    $("#cl" + clickID).bind("click", delRow);

}


function delRow() {
    // Funcion que destruye el elemento actual una vez echo el click
    $(this).parent('div').remove();

}