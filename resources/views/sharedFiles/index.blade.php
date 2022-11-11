@extends('layouts.user')
@section('css')
    <style>

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
            z-index: 1;
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

        .light-style .file-item-name {
            color: #4e5155 !important;
        }


        @media (min-width: 768px) {
            .light-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }


        .material-style .file-item-name {
            color: #4e5155 !important;
        }

        @media (min-width: 768px) {
            .material-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }


        .dark-style .file-item-name {
            color: #fff !important;
        }

        @media (min-width: 768px) {

            .dark-style .file-manager-row-view .file-item-name {
                width: calc(100% - 15rem);
            }
        }
    </style>
@endsection
@section('content')
    <div class="container flex-grow-1 light-style container-p-y">

        <h2 class="my-5">Shared files</h2>
        <div class="file-manager-container file-manager-col-view">
            @if($folder_id !=null)
                <div class="file-item">
                    <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
                    <a href="{{ route('sharedFiles.getAllSharedFiles')}}"
                       class="file-item-name">
                        ...
                    </a>
                </div>
            @endif
            @foreach ($shared_files as $file)
                <div class="file-item">
                    <div @class(
                        [
                         'file-item-icon far text-secondary',
                         'fa-folder' => $file->is_folder,
                         'fa-file-excel' => $file->extension == 'xlsx',
                         'fa-file-word' => $file->extension == 'docx',
                       'fa-regular fa-image' => in_array($file->extension ,['jpg','jpeg','png'])
                         ])></div>
                    @if($file->is_folder)
                        <a href="{{route('sharedFiles.getAllSharedFiles',['folder_id' => $file->file_id])}}"
                           class="file-item-name">{{$file->name}}</a>
                    @else
                        <a href="{{route('files.download',['file'=>$file->id])}}" class="file-item-name">
                            {{$file->name . "." . $file->extension}}</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
