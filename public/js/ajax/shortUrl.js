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
                            <td><button href="#" class="btn btn-info btn-sm">Edit</button> <button href="#" class="btn btn-danger btn-sm delete-action">Delete</a></td>
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
                text: 'Your url has been successfuly deleted!',
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