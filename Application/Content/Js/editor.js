$('document').ready(function(){
    $('.summernote').summernote({
        height: 300
    });

    $('#facebookdata .submit').on('click', function(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var data = {};
        $('#facebookdata input').each(function(){
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

    $('#metadata .submit').on('click', function(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var data = {};
        $('#metadata input').each(function(){
            data[this.name] = $(this).val();
        });

        data['HomePageText'] = $('#metadata .summernote').summernote('code');
        alert(data['HomePageText']);

        $.post(
            '/postajax/updatemetadata/',
            JSON.stringify(data),
            'application/json'
        ).done(function(data){
            button.removeClass('disabled');
        });
    });
});
