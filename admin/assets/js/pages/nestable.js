$(document).ready(function() {
    // Nestable
    var last_touched = '';
    var updateOutput = function(e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            $.post('process_update.php', { 'whichnest': last_touched, 'output': output.val() },
                function(data) {
                    toastr["success"]("datos actualizados");
                }
            );
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };


    // activate Nestable for list 1
    $('#nestable').nestable({
            group: 1
        })
        .on('change', function() { last_touched = 'nestable'; })
        .on('change', updateOutput);

    updateOutput($('#nestable').data('output', $('#nestable-output')));

    $('#nestable-menu').on('click', function(e) {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

});