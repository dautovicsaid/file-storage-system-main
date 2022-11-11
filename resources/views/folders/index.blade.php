@extends('layouts.user')
@section('css')
    <style>

        .select2 {
            width: 300px !important;
        }

        .file-manager-actions {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .file-manager-actions > * {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .file-manager-container {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .file-item {
            position: relative;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            border: 1px solid #eee;
            cursor: pointer;
        }

        .file-item:hover {
            border-color: rgba(0, 0, 0, 0.05);
        }

        .file-item * {
            -ms-flex-negative: 0;
            flex-shrink: 0;
            text-decoration: none;
        }

        .dark-style .file-item:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }


        .file-item-select-bg {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -1;
            opacity: 0;
        }


        .file-item-name {
            display: block;
            overflow: hidden;
        }

        .file-manager-col-view .file-item {
            margin: 0 0.25rem 0.25rem 0;
            padding: 1.25rem 0 1rem 0;
            width: 9rem;
            text-align: center;
        }

        [dir="rtl"] .file-manager-col-view .file-item {
            margin-right: 0;
            margin-left: 0.25rem;
        }

        .file-manager-col-view .file-item-icon {
            display: block;
            margin: 0 auto 0.75rem auto;
            width: 4rem;
            height: 4rem;
            font-size: 2.5rem;
            line-height: 4rem;
        }

        .file-manager-col-view .file-item-level-up {
            font-size: 1.5rem;
        }

        .file-manager-col-view .file-item-actions {
            position: absolute;
            top: 6px;
        }


        .file-manager-col-view .file-item-actions {
            right: 6px;
        }

        [dir="rtl"] .file-manager-col-view .file-item-actions {
            right: auto;
            left: 6px;
        }

        .file-manager-col-view .file-item-name {
            width: 100%;
        }


        .file-manager-row-view .file-item {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            margin: 0 0 0.125rem 0;
            padding: 0.25rem 3rem 0.25rem 2.25em;
            width: 100%;
        }

        [dir="rtl"] .file-manager-row-view .file-item {
            padding-right: 2.25em;
            padding-left: 3rem;
        }

        .file-manager-row-view .file-item-icon {
            display: block;
            margin: 0 1rem;
            width: 2rem;
            height: 2rem;
            text-align: center;
            font-size: 1.25rem;
            line-height: 2rem;
        }

        .file-manager-row-view .file-item-level-up {
            font-size: 1rem;
        }

        .file-manager-row-view .file-item-actions {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }


        .file-manager-row-view .file-item-actions {
            right: 10px;
        }

        [dir="rtl"] .file-manager-row-view .file-item-actions {
            right: auto;
            left: 10px;
        }


        .file-manager-row-view .file-item-name {
            width: calc(100% - 4rem);
        }


        .file-manager-row-view .file-manager-row-header .file-item-name {
            margin-left: 4rem;
        }

        [dir="rtl"] .file-manager-row-view .file-manager-row-header .file-item-name {
            margin-right: 4rem;
            margin-left: 0;
        }

        a {
            text-decoration: none;
            color: black;
        }

        .light-style .file-item-name {
            color: #4e5155 !important;
        }

        .light-style .file-item.selected .file-item-select-bg {
            opacity: 0.15;
        }

        @media (min-width: 768px) {

            .light-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }

        @media (min-width: 992px) {
            .light-style .file-manager-col-view .file-item-actions {
                opacity: 0;
            }

            .light-style .file-manager-col-view .file-item:hover .file-item-actions,
            .light-style .file-manager-col-view .file-item.focused .file-item-actions,
            .light-style .file-manager-col-view .file-item.selected .file-item-actions {
                opacity: 1;
            }
        }

        .material-style .file-item-name {
            color: #4e5155 !important;
        }

        .material-style .file-item.selected .file-item-select-bg {
            opacity: 0.15;
        }

        @media (min-width: 768px) {

            .material-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }

        @media (min-width: 992px) {
            .material-style .file-manager-col-view .file-item-actions {
                opacity: 0;
            }

            .material-style .file-manager-col-view .file-item:hover .file-item-actions,
            .material-style .file-manager-col-view .file-item.focused .file-item-actions,
            .material-style .file-manager-col-view .file-item.selected .file-item-actions {
                opacity: 1;
            }
        }

        .dark-style .file-item-name {
            color: #fff !important;
        }

        .dark-style .file-item.selected .file-item-select-bg {
            opacity: 0.15;
        }

        @media (min-width: 768px) {

            .dark-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }

        @media (min-width: 992px) {
            .dark-style .file-manager-col-view .file-item-actions {
                opacity: 0;
            }

            .dark-style .file-manager-col-view .file-item:hover .file-item-actions,
            .dark-style .file-manager-col-view .file-item.focused .file-item-actions,
            .dark-style .file-manager-col-view .file-item.selected .file-item-actions {
                opacity: 1;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container flex-grow-1 light-style container-p-y">
        <div class="container-m-nx container-m-ny bg-lightest mb-3">
            <h2 class="my-2">{{$folder->name}}</h2>
            <hr class="m-0"/>

            <div class="file-manager-actions container-p-x py-2">
                <div>
                    <button type="button" class="btn btn-primary me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#uploadFileModal"><i class="ion ion-md-cloud-upload"></i>&nbsp;
                        Upload file
                    </button>
                    <button type="button" class="btn btn-primary me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#uploadFolderModal">&nbsp;
                        Create folder
                    </button>
                </div>
            </div>
            <hr class="m-0"/>
        </div>

        @if($errors->has('upload_error'))
            <div class="alert alert-danger mt-2">{{$errors->first('upload_error')}}</div>
        @endif
        @if($errors->has('folder_name_error'))
            <div class="alert alert-danger mt-2">{{$errors->first('folder_name_error')}}</div>
        @endif
        @if($errors->has('file_name_error'))
            <div class="alert alert-danger mt-2">{{$errors->first('file_name_error')}}</div>
        @endif
        @if($errors->has('file_rename_error'))
            <div class="alert alert-danger mt-2">{{$errors->first('file_rename_error')}}</div>
        @endif

        <div class="file-manager-container file-manager-col-view">
            @if($folder->folder_id != null)
                <div class="file-item">

                    <a href="{{route('folders.index',['folder' => $folder->folder_id])}}" class="file-item-name">
                        <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
                        Go back
                    </a>
                </div>
            @endif
            @foreach ($files as $file)
                <div class="file-item">
                    <div class="file-item-select-bg bg-primary"></div>
                    @if($file->is_folder)
                        <a href=" {{route('folders.index',['folder'=>$file->id])}}">
                            <div class='file-item-icon far text-secondary fa-folder'></div>
                            {{$file->name}}
                        </a>
                    @else
                        <div @class(
                        [ 'file-item-icon far text-secondary',
                         'fa-file-excel' => $file->extension == 'xlsx',
                         'fa-file-word' => $file->extension == 'docx',
                         'fa-regular fa-image' => in_array($file->extension ,['jpg','jpeg','png'])
                         ])></div>
                        {{$file->name . "." . $file->extension}}
                    @endif
                    <div class="file-item-actions btn-group">
                        <div class="dropdown">
                            <button type="button"
                                    class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle"
                                    data-bs-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item"
                                   data-bs-toggle="modal"
                                   data-bs-target="#renameFileModal"
                                   data-bs-file-id="{{$file->id}}"
                                   data-bs-file-name="{{$file->name}}">Rename</a>
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
                                >Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('files.store') }}" method="POST" id="uploadFileForm"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <input required type="text" name="name"/>
                                <input type="hidden" name="folder_id" value="{{$folder->id}}"/>
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
                    <h5 class="modal-title">Create folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('folders.store') }}" method="POST" id="uploadFolderForm">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <input required type="text" name="name"/>
                                <input type="hidden" name="folder_id" value="{{$folder->id}}"/>
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
                    dropdownParent: $("#shareModal")
                });
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
