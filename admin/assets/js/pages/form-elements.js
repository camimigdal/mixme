$(document).ready(function() {
    $('.summernote').summernote({
        height: 200,
        callbacks: {
            onPaste: function(e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                setTimeout(function() {
                    document.execCommand('insertText', false, bufferText);
                }, 10);
            }
        }
    });

    $('.date-picker').datepicker({
        orientation: "top auto",
        autoclose: true
    });

    $('#cp1').colorpicker({
        format: 'hex'
    });
    $('#cp2').colorpicker();
});