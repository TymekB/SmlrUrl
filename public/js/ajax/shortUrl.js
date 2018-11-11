function showAll(DOMElement) {

    $.ajax({
        method: 'GET',
        url: '/api/short-url',
        success: function(data) {

            let output = '';

            $.each(data, function(index, shortUrl) {

                let link = window.location.host + "/" + shortUrl.token;

                output += `
                        <tr>
                            <td>${shortUrl.id}</td>
                            <td>${shortUrl.url}</td>
                            <td><a href="${link}">${link}</a></td>
                            <td>${shortUrl.token}</td>
                            <td><a href="#" class="btn btn-info btn-sm">Edit</a> <a href="#" class="btn btn-danger btn-sm">Delete</a></td>
                        </tr>
                        `;
            });

            $(DOMElement).html(output);
        }
    });
}