$('document').ready(function(){
    $('.summernote').summernote();

    $('#facebookData .submit').on('click', function(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var data = {};
        $('#facebookData input').each(function(){
            data[this.name] = $(this).val();
        });

        $.post(
            '/postajax/updatefacebook/',
            JSON.stringify(data),
            'application/json'
        ).done(function(data){
            button.removeClass('disabled');
        });
    });
});
