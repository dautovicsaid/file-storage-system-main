const openedClass = 'fa-solid fa-folder-open';
const closedClass = 'fa-solid fa-folder';

function openFolder(fileId) {
    const folder = $(`li.branch[data-file-id=${fileId}]`);

    const icon = folder.children('i:first');
    icon.toggleClass(openedClass + " " + closedClass);
    folder.children().children().filter(function () {
        return !$(this).hasClass('file-action');
    }).toggle();


    if (folder.attr('data-folder-loaded') === 'true') {
        return;
    }

    fetch(`/api/folders/${fileId}/files`, {
        headers: new Headers({
            'Authorization': `Bearer ${apiToken}`
        })
    })
        .then(response => response.json())
        .then(files => {
            const ulElement = $('<ul/>')

            $.each(files, function (index, file) {
                const li = $('<li/>').attr('data-file-id', file.id);

                if (file.is_folder) {
                    $('<a/>').attr('href', `/folders/${file.id}`).text(file.name).appendTo(li);
                } else {
                    li.text(`${file.name}.${file.extension}`);
                }

                if (file.is_folder) {
                    li.addClass('branch')
                    $('<i/>').addClass(`indicator ${closedClass}`).on('click', () => openFolder(file.id))
                        .prependTo(li);
                } else {
                    $('<i/>').addClass(`indicator fa-solid fa-file`).prependTo(li);
                }
                const dropUpDiv = $('<div/>').addClass("btn btn-group dropup ms-2");

                const button = $('<button/>').addClass("btn btn-sm btn-secondary dropdown-toggle file-action")
                    .attr({
                        'data-bs-toggle': 'dropdown',
                        'aria-expanded': 'false'
                    }).text('Actions');

                const dropdownMenuDiv = $('<div/>').addClass("dropdown-menu dropdown-menu-right file-action");

                const renameItem = $('<a/>').addClass("dropdown-item")
                    .attr({
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#renameFileModal',
                        'data-bs-file-id': file.id,
                        'data-bs-file-name': file.name,
                        'data-bs-file-created-by': file.created_by,
                    }).text('Rename');

                const shareItem = $('<a/>').addClass('dropdown-item')
                    .attr({
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#shareModal',
                        'data-bs-file-id': file.id,
                        'data-bs-is-folder': file.is_folder
                    }).text('Share');

                const deleteItem = $('<a/>').addClass('dropdown-item')
                    .attr({
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#deleteFileModal',
                        'data-bs-file-id': file.id,
                        'data-bs-file-name': file.is_folder ? file.name : `${file.name}.${file.extension}`
                    }).text('Delete');

                const downloadItem = $('<a/>').addClass('dropdown-item')
                    .attr('href', `files/${file.id}/download`).text('Download');

                dropdownMenuDiv.append([renameItem, shareItem, deleteItem])

                if (!file.is_folder) {
                    dropdownMenuDiv.append(downloadItem)
                }
                dropUpDiv.append(button)
                dropUpDiv.append(dropdownMenuDiv)
                li.append(dropUpDiv)

                ulElement.append(li);
            });

            folder.append(ulElement);
            folder.attr('data-folder-loaded', 'true');
        });
}


function shareFile(event) {
    event.preventDefault();

    const fileId = $("#shareFileId").val();
    const userId = $("#shareUserSelect").val();
    // const isFolder = $("#shareModal").attr('data-bs-is-folder');

    fetch(`/api/files/${fileId}/share`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${apiToken}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({userId})
    }).then(() => $("#shareModalCloseBtn").click());
}

function showSharedWithUsers(fileId, tableId) {

    fetch(`/api/files/${fileId}/shared-users`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${apiToken}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    }).then((response) => response.json())
        .then(users => {
            const html = users.map(user => {
                    return `<tr id="table-row-${user.user_id}">
                        <td>
                        ${user.name}
                        </td>
                        <td>
                        ${user.email}
                        </td>
                        <td>
                                <button class="btn btn-danger btn-sm rounded-0"
                                type="submit" data-toggle="tooltip" data-placement="top"
                                title="" data-original-title="Delete" onclick="removeSharedWithUser(${fileId},${user.user_id})"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>`;
                }
            ).join();

            tableId.html(html);
        });
}

function removeSharedWithUser(fileId,userId) {
    fetch(`/api/shared-files/${fileId}/${userId}/delete`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${apiToken}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    }).then((response) => {
        if(response.status === 200) {
            const tableRow = $(`#table-row-${userId}`);
            tableRow.remove()
        } else {
            $('#error-div').html(`
            <div class="alert alert-danger mt-2">You can't remove other users shared files</div>
            `)
        }
    });

}
