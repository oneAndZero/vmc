$(document).ready(function () {

    var i = $('.file').size();
    var name = $('input:file').attr('name');

    $('#add').click(function () {
        $('<div class="row file"><input type="file" name="' + name + '"/></div>').fadeIn('fast').appendTo('.files');
        i++;
    });

    $('#remove').click(function () {
        if (i > 1) {
            $('.file:last').remove();
            i--;
        }
    });

    $('#reset').click(function () {
        while (i > 2) {
            $('.row:last').remove();
            i--;
        }
    });

    // here's our click function for when the forms submitted

    $('.submit').click(function () {

        var answers = [];
        $.each($('.field'), function () {
            answers.push($(this).val());
        });

        if (answers.length == 0) {
            answers = "none";
        }

        alert(answers);

        return false;

    });

});
