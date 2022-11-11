@extends('layouts.user')
@section('css')
    <style rel="stylesheet" href="{{ asset('css/files.css')}}">
        .select2 {
            width: 300px !important;
        }

        .tree, .tree ul {
            margin: 0;
            padding: 0;
            list-style: none
        }

        .tree ul {
            margin-left: 1em;
            position: relative
        }

        .tree ul ul {
            margin-left: .5em
        }

        .tree ul:before {
            content: "";
            display: block;
            width: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            border-left: 1px solid
        }

        .tree li {
            margin: 0;
            padding: 0 1em;
            line-height: 2em;
            color: #369;
            font-weight: 700;
            position: relative
        }

        .tree ul li:before {
            content: "";
            display: block;
            width: 10px;
            height: 0;
            border-top: 1px solid;
            margin-top: -1px;
            position: absolute;
            top: 1em;
            left: 0
        }

        .tree ul li:last-child:before {
            background: #fff;
            height: auto;
            top: 1em;
            bottom: 0
        }

        .indicator {
            margin-right: 5px;
        }

        .tree li a {
            text-decoration: none;
            color: #369;
        }
    </style>
@endsection
@section('content')
    <script>
        const apiToken = '{{auth()->user()->createToken("API TOKEN")->plainTextToken}}';
    </script>

    <div class="container">
        <div class="col-md-8 mt-3">
            <h2 class="my-3">File storage</h2>
            <div>
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadFileModal">
                    <i class="ion ion-md-cloud-upload"></i> Upload file
                </button>
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadFolderModal"
                > Create folder
                </button>
            </div>

            @if($errors->has('upload_error'))
                <div class="alert alert-danger mt-5">{{$errors->first('upload_error')}}</div>
            @endif
            @if($errors->has('folder_name_error'))
                <div class="alert alert-danger mt-5">{{$errors->first('folder_name_error')}}</div>
            @endif
            @if($errors->has('file_name_error'))
                <div class="alert alert-danger mt-5">{{$errors->first('file_name_error')}}</div>
            @endif
            @if($errors->has('file_rename_error'))
                <div class="alert alert-danger mt-5">{{$errors->first('file_rename_error')}}</div>
            @endif

            <ul class="tree mt-5">

                @foreach($files as $file)
                    <li class="branch mb-2" data-file-id="{{$file->id}}">
                        @if(!$file->is_folder)
                            <i class="indicator fa-solid fa-file"></i>
                        @else
                            <i class='indicator fa-solid fa-folder'
                               onclick="openFolder({{$file->id}})"></i>
                        @endif

                        @if ($file->is_folder)
                            <a href="{{route('folders.index', ['folder' => $file->id])}}">{{$file->name}}</a>
                        @else
                            {{$file->name . "." . $file->extension}}
                        @endif
                        <div class="btn-group dropup ms-2">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle file-action"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right file-action">
                                <a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#renameFileModal"
                                   data-bs-file-id="{{$file->id}}"
                                   data-bs-file-name="{{$file->name}}"
                                   data-bs-file-created-by="{{$file->created_by}}">Rename</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#shareModal"
                                   data-bs-file-id="{{$file->id}}" data-bs-is-folder="{{$file->is_folder}}">Share</a>
                                @if(!$file->is_folder)
                                    <a class="dropdown-item" href="{{route('files.download',['file'=>$file->id])}}">Download</a>
                                @endif
                                <a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#deleteFileModal"
                                   data-bs-file-id="{{$file->id}}"
                                   data-bs-file-name="{{$file->is_folder ? $file->name : $file->name . "." . $file->extension}}"
                                   data-bs-is-folder="{{$file->is_folder}}"
                                >Delete</a>
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="shareModalLabel">Share file</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="error-div">

                    </div>
                    <div class="form-group">
                        <form onsubmit="shareFile(event)">
                            <input type="hidden" id="shareFileId">
                            <select required class="share-select form-control" style="min-width: 200px;"
                                    name="userId" id="shareUserSelect">
                            </select>
                            <button type="submit" class="btn btn-primary">Share
                            </button>
                        </form>
                    </div>
                    <div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="usersSharedTableBody">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="shareModalCloseBtn" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="renameFileModal" tabindex="-1" aria-labelledby="renameFileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rename</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('files.rename')}}" method="POST" id="renameFileForm">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-6">
                                <input required type="text" id="nameInput" name="name"/>
                                <input type="hidden" id="fileIdInput" name="file_id"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="renameFileForm" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="deleteFileModalLabel" class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure?</p>
                    <form action="{{ route('files.destroy')}}" method="POST" id="deleteFileForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deleteFileId" name="file_id"/>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="deleteFileForm" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Upload file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('files.store') }}" method="POST" id="uploadFileForm"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <input required type="text" name="name"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <input required type="file" name="file" multiple class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="uploadFileForm" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadFolderModal" tabindex="-1" aria-labelledby="uploadFolderModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Create folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('folders.store') }}" method="POST" id="uploadFolderForm">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <input required type="text" name="name"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="uploadFolderForm" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="module">
        $(document).ready(function () {
            const shareModal = document.getElementById('shareModal')
            shareModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const fileId = button.getAttribute('data-bs-file-id')
                const tableId = $('#usersSharedTableBody');
                showSharedWithUsers(fileId, tableId);
                shareModal.querySelector('#shareFileId').value = fileId

                $('.share-select').select2({
                    ajax: {
                        url: `/api/files/${fileId}/search-shareable-users`,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term, // search term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                    },
                    minimumInputLength: 2,
                    dropdownParent: $("#shareModal"),
                    placeholder: 'Enter name',
                    allowClear: true
                });
            })
            shareModal.addEventListener('hide.bs.modal', event => {
                $(".share-select").val(null).trigger("change");
            })

            let renameFileModal = document.getElementById('renameFileModal')
            renameFileModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                let button = event.relatedTarget
                // Extract info from data-bs-* attributes
                let fileId = button.getAttribute('data-bs-file-id')
                let fileName = button.getAttribute('data-bs-file-name')
                // If necessary, you could initiate an AJAX request here
                // and then do the updating in a callback.
                //
                // Update the modal's content.
                let nameInput = renameFileModal.querySelector('#nameInput')

                let fileIdInput = renameFileModal.querySelector('#fileIdInput')

                nameInput.value = fileName
                fileIdInput.value = fileId
            })

            let deleteFileModal = document.getElementById('deleteFileModal')
            deleteFileModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                let button = event.relatedTarget
                // Extract info from data-bs-* attributes
                let fileId = button.getAttribute('data-bs-file-id')
                let fileName = button.getAttribute('data-bs-file-name')
                // If necessary, you could initiate an AJAX request here
                // and then do the updating in a callback.
                //
                // Update the modal's content.
                let modalLabel = deleteFileModal.querySelector('#deleteFileModalLabel')
                let fileIdInput = deleteFileModal.querySelector('#deleteFileId')

                console.log(modalLabel)
                modalLabel.innerHTML = `Delete ${fileName}`;
                fileIdInput.value = fileId
            })
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/files.js') }}"></script>
@endsection
