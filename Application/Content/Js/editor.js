$('document').ready(function(){
    $('.summernote').summernote({
        height: 300
    });

    var editPostContentTemplate = Handlebars.compile($('#postContentTemplate').html());

    $('#opengraphdata .submit').on('click', function(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var data = {};
        $('#opengraphdata input').each(function(){
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

        $('#metadata select').each(function(){
            data[this.name] = $(this).val();
        });

        var tags = {};
        $('#tags input:checked').each(function(){
            tags[this.name] = $(this).val();
        });

        data['tags'] = tags;

        data['HomePageText'] = $('#metadata .summernote').summernote('code');

        $.post(
            '/postajax/updatemetadata/',
            JSON.stringify(data),
            'application/json'
        ).done(function(data){
            button.removeClass('disabled');
        });
    });

    $('.addContent').on('click', function(e){
        e.preventDefault();

        $('.addContent').addClass('disabled');

        var data = {};
        data['Id'] = $(this).attr('data-id');

        $.post(
            '/postajax/addcontent/',
            JSON.stringify(data),
            'application/json'
        ).done(function(data){
            var newContent = $('#content-wrapper').append(editPostContentTemplate(data));
            newContent.find('.editContent').on('click', editContent);
            newContent.find('.stopContent').on('click', stopContent);
            newContent.find('.saveContent').on('click', saveContent);
            newContent.find('.deleteContent').on('click', deleteContent);

            $('.addContent').removeClass('disabled');
        });
    });

    function editContent(e){
        e.preventDefault();

        $(this).closest('.postSection').find('.editableContent').summernote();
    }

    function stopContent(e){
        e.preventDefault();

        $(this).closest('.postSection').find('.editableContent').summernote('destroy');
    }

    function saveContent(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var content = $(this).closest('.postSection').find('.editableContent').summernote('code');
        $(this).closest('.postSection').find('.editableContent').summernote('destroy');
        var id = $(this).closest('.postSection').attr('data-id');

        var data = {
            Id: id,
            Content: content
        };

        $.post(
            '/postajax/savecontent/',
            JSON.stringify(data),
            'application/json'
        ).done(function(data){
            button.removeClass('disabled');
        });
    }

    function deleteContent(e){
        e.preventDefault();

        var button = $(this);
        button.addClass('disabled');

        var id = $(this).closest('.postSection').attr('data-id');
        var data = {
            Id: id
        };

        if(confirm("Are you sure you want to delete this content?")) {
            $.post(
                '/postajax/deletecontent/',
                JSON.stringify(data),
                'application/json'
            ).done(function (data) {
                button.closest('.postContent').remove();
                button.removeClass('disabled');
            });
        }else{
            button.removeClass('disabled');
        }
    }

    $('.editContent').on('click', editContent);
    $('.stopContent').on('click', stopContent);
    $('.saveContent').on('click', saveContent);
    $('.deleteContent').on('click', deleteContent);
});
