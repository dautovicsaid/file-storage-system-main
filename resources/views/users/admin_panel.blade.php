@extends('layouts.user')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($errors->has('storage_resize_error'))
                    <div class="alert alert-danger mt-5">{{$errors->first('storage_resize_error')}}</div>
                @endif
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Storage used</th>
                        <th>Storage limit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{$user->name}}
                            </td>
                            <td>
                                {{$user->email}}
                            </td>
                            <td>
                                {{\App\Models\User::size_converted_from_bytes($user->storage_used)}} MB
                            </td>
                            <td>
                                {{\App\Models\User::size_converted_from_bytes($user->storage_limit)}} GB
                                <button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                        data-bs-toggle="modal" data-bs-target="#storageLimitModal"
                                        data-bs-storage-limit="{{\App\Models\User::size_converted_from_bytes($user->storage_limit)}}"
                                        data-bs-user-id="{{$user->id}}"
                                        data-bs-user-name="{{$user->name}}"
                                        data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="storageLimitModal" tabindex="-1" aria-labelledby="storageLimitModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="storageLimitModal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="storage_limit_form" action="{{route('users.changeStorageLimit')}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="storage_limit" class="col-form-label">Storage limit (In GB):</label>
                            <input type="number" class="form-control" name="storage_limit" id="storage_limit">
                        </div>
                        <input type="hidden" class="form-control" name="user_id" id="user_id"></input>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button form="storage_limit_form" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let storageLimitModal = document.getElementById('storageLimitModal')
        storageLimitModal.addEventListener('show.bs.modal', function (event) {

            let button = event.relatedTarget
            let userName = button.getAttribute('data-bs-user-name')
            let userId = button.getAttribute('data-bs-user-id')
            let storageLimit = button.getAttribute('data-bs-storage-limit')
            let modalTitle = storageLimitModal.querySelector('.modal-title')
            let modalStorageLimitInput = storageLimitModal.querySelector('.modal-body #storage_limit')
            let modalUserInput = storageLimitModal.querySelector('.modal-body #user_id')


            modalTitle.textContent = userName
            modalStorageLimitInput.value = storageLimit
            modalUserInput.value = userId
        })
    </script>
@endsection
