function showAll(DOMElement) {

    console.log('showing all..');

    $.ajax({
        method: 'GET',
        url: '/api/short-url',
        success: function(data) {

            let output = '';

            $.each(data, function(index, shortUrl) {

                let link = window.location.host + "/" + shortUrl.token;

                output += `
                        <tr data-id="${shortUrl.id}">
                        
                            <td>${shortUrl.id}</td>
                            <td>${shortUrl.url}</td>
                            <td><a href="${link}">${link}</a></td>
                            <td>${shortUrl.token}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-${shortUrl.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-action">Delete</button>
                               

                                <!-- Modal -->
                                <div id="modal-${shortUrl.id}" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                          <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Edit</h4>
                                          </div>
                                          
                                          <div class="modal-body">
                                               <div class="form-group">
                                                    <label for="url-${shortUrl.id}"><b>URL:</b></label>
                                                    <input type="text" class="form-control" id="url-${shortUrl.id}" value="${shortUrl.url}">
                                               </div> 
                                          </div>
                                          
                                          <div class="modal-footer">
                                                <div class="pull-left">
                                                    <button class="btn btn-primary update-action" data-id="${shortUrl.id}">Update</button>
                                                </div>
                                                
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                                <div class="clearfix"></div>
                                          </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>

                `;
            });

            $(DOMElement).html(output);

            $('.delete-action').click(function() {

                if(!confirm("Are you sure?")) {
                    return;
                }

                let id = $(this).parent().parent().data("id");

                remove(id);
            });

            $('.update-action').click(function(){

                let id = $(this).data('id');
                let url = $('#url-'+id).val();

                update(id, url);
            });
        }
    });
}

function update(id, url) {

    let jsonData = JSON.stringify({url: url});

    $.ajax({
        method: 'PUT',
        data: jsonData,
        url: '/api/short-url/'+id,
        success: function(data) {

            $('#modal-'+id).modal('toggle');

            $.toast({
                heading: 'Success!',
                text: 'Your url has been successfully updated!',
                hideAfter: false,
                icon: 'success'
            });

            setTimeout(function(){
                showAll("#data");
            }, 1000);

        },
        error: function(data) {
            $.toast({
                heading: 'Error!',
                text: 'Something went wrong. Try again later.',
                hideAfter: false,
                icon: 'error'
            });
            console.log(data);
        }
    });
}

function remove(id) {

    let jsonData = JSON.stringify({id: id});

    $.ajax({
        method: 'DELETE',
        data: jsonData,
        url: '/api/short-url',
        success: function(data) {
            $.toast({
                heading: 'Success!',
                text: 'Your url has been successfully deleted!',
                hideAfter: false,
                icon: 'success'
            });
        },
        error: function(data) {
            $.toast({
                heading: 'Error!',
                text: 'Something went wrong. Try again later.',
                hideAfter: false,
                icon: 'error'
            });
        }
    });

    showAll("#data");
}