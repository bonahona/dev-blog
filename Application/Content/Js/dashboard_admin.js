$(document).ready(function() {
    CreateSeeAlsoLinks();
    CreateInheritInterface();
    CreateGenericType();
    CreateParameter();
    DeleteSeeAlsoLinks();
    DeleteInheritInterface();
    DeleteParameters();
});

function CreateSeeAlsoLinks()
{
    $('.see-also-link').on('click', function(event){
        event.preventDefault();

        var form = $(this).closest('form.ajax-form');
        var requestTarget = form.attr('link-target');
        $.post(
            requestTarget,
            form.serialize(),
            function(data){

                var tableRow = $('<tr></tr>');
                var tableColumns = Array();
                for(var i = 0; i < 4; i ++){
                    tableColumns[i] = $('<td></td>');
                }

                tableColumns[0].html(data.Id);
                tableColumns[1].html(data.DisplayName);
                tableColumns[2].html(data.Link);
                var buttonElement = $('<button type="button" class="delete-see-also-link btn btn-md btn-default" link-target="/SeeAlsoLinks/Delete/' + data.Id + '"> \
                    <span class="glyphicon glyphicon-trash"</span> \
                    </button>\
                ');
                DeleteSeeAlsoLinks(buttonElement);

                tableColumns[3].append(buttonElement);


                for(i = 0; i < 4; i ++){
                    tableRow.append(tableColumns[i]);
                }

                var tableBody = $('tbody.see-also-links-body');
                tableBody.append(tableRow);


                $('#seealsolinkdialog').modal('toggle');
            }
        )
    });
}

function CreateInheritInterface()
{
    $('.inherit-interface').on('click', function(event){
        event.preventDefault();

        var form = $(this).closest('form.ajax-form');
        var requestTarget = form.attr('link-target');

        $.post(
            requestTarget,
            form.serialize(),
            function(data){
                var tableRow = $('<tr></tr>');
                var tableColumns = Array();
                for(var i = 0; i < 3; i ++){
                    tableColumns[i] = $('<td></td>');
                }

                tableColumns[0].html(data.Id);
                tableColumns[1].html(data.Type);
                var buttonElement = $('<button type="button" class="delete-inherit-interface btn btn-md btn-default" link-target="/InheritInterfaces/Delete/' + data.Id + '"> \
                    <span class="glyphicon glyphicon-trash"</span> \
                    </button>\
                ');

                DeleteInheritInterface(buttonElement);

                tableColumns[2].append(buttonElement);

                for(i = 0; i < 3; i ++){
                    tableRow.append(tableColumns[i]);
                }

                var tableBody = $('tbody.implements-inheritance-body');
                tableBody.append(tableRow);

                $('#inheritinterfacedialog').modal('toggle');
            }
        )
    });
}


function CreateGenericType()
{
    $('.generic-type-link').on('click', function(event){
        event.preventDefault();

        var form = $(this).closest('form.ajax-form');
        var requestTarget = form.attr('link-target');

        $.post(
            requestTarget,
            form.serialize(),
            function(data){

                $('#creategenerictypedialog').modal('toggle');
                /*
                 var tableRow = $('<tr></tr>');
                 var tableColumns = Array();
                 for(var i = 0; i < 5; i ++){
                 tableColumns[i] = $('<td></td>');
                 }

                 tableColumns[0].html(data.Id);
                 tableColumns[1].html(data.ParameterName);
                 tableColumns[2].html(data.Type);
                 tableColumns[3].html(data.Type);
                 var buttonElement = $('<button type="button" class="delete-parameter btn btn-md btn-default" link-target="/Parameters/Delete/' + data.Id + '"> \
                 <span class="glyphicon glyphicon-trash"</span> \
                 </button> \
                 ');
                 DeleteParameters(buttonElement);

                 tableColumns[4].append(buttonElement);


                 for(i = 0; i < 5; i ++){
                 tableRow.append(tableColumns[i]);
                 }

                 var tableBody = $('tbody.create-parameter-body');
                 tableBody.append(tableRow);

                 $('#createparameterdialog').modal('toggle');
                 */
            }
        )
    });
}

function CreateParameter()
{
    $('.parameter-link').on('click', function(event){
        event.preventDefault();

        var form = $(this).closest('form.ajax-form');
        var requestTarget = form.attr('link-target');

        $.post(
            requestTarget,
            form.serialize(),
            function(data){

                var tableRow = $('<tr></tr>');
                var tableColumns = Array();
                for(var i = 0; i < 5; i ++){
                    tableColumns[i] = $('<td></td>');
                }

                tableColumns[0].html(data.Id);
                tableColumns[1].html(data.ParameterName);
                tableColumns[2].html(data.Type);
                tableColumns[3].html(data.Type);
                var buttonElement = $('<button type="button" class="delete-parameter btn btn-md btn-default" link-target="/Parameters/Delete/' + data.Id + '"> \
                     <span class="glyphicon glyphicon-trash"</span> \
                    </button> \
                 ');
                DeleteParameters(buttonElement);

                tableColumns[4].append(buttonElement);


                for(i = 0; i < 5; i ++){
                    tableRow.append(tableColumns[i]);
                }

                var tableBody = $('tbody.create-parameter-body');
                tableBody.append(tableRow);

                $('#createparameterdialog').modal('toggle');
            }
        )
    });
}

function DeleteSeeAlsoLinks(element)
{
    if(element == undefined){
        element = $('.delete-see-also-link')
    }

    element.on('click', function(event) {
        event.preventDefault();

        var buttonElement = $(this);
        if(confirm('Are you sure?')) {
            var requestTarget = $(this).attr('link-target');

            $.post(requestTarget, function(data){
                buttonElement.closest('tr').remove();
            });
        }
    })
}

function DeleteInheritInterface(element)
{
    if(element == undefined){
        element = $('.delete-inherit-interface')
    }

    element.on('click', function(event) {
        event.preventDefault();

        var buttonElement = $(this);
        if(confirm('Are you sure?')) {
            var requestTarget = $(this).attr('link-target');

            $.post(requestTarget, function(data){
                buttonElement.closest('tr').remove();
            });
        }
    })
}

function DeleteParameters(element)
{
    if(element == undefined){
        element = $('.delete-parameter')
    }

    element.on('click', function(event) {
        event.preventDefault();

        var buttonElement = $(this);
        if(confirm('Are you sure?')) {
            var requestTarget = $(this).attr('link-target');

            $.post(requestTarget, function(data){
                buttonElement.closest('tr').remove();
            });
        }
    })
}
