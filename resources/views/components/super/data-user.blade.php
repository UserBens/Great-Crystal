@extends('layouts.admin.master')

@section('content')

{{-- ajax handler --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- Content Wrapper. Contains page content -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid">
    <h2 class="text-center display-4">Data user</h2>


    <a type="button" href="user/register-user" id="#" class="btn btn-success btn mt-5 mb-2">
        <i class="fa-solid fa-user-plus me-1"></i>
        </i>
        Add user
    </a>
    <div class="card card-dark mt-3">
        <div class="card-header">
            <h3 class="card-title">Users</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">


            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 15%">
                            #
                        </th>
                        <th style="width: 30%">
                            Username
                        </th>
                        <th>
                            Created at
                        </th>
                        <th style="width: 30%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $el)
                    <tr id="index_user_{{ $el->id }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{$el->username}}</td>
                        <td>{{$el->created_at ? date("d/m/Y", strtotime($el->created_at)) : 'Unknown'}}</td>
                        <td>

                            <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#{{'changePassword' . $el->id}}">
                                <i class=" fas fa-solid fa-unlock-keyhole"></i>
                                Change password
                            </button>

                            <a href="javascript:void(0)" id="delete-user" data-id="{{ $el->id }}"
                                data-name="{{ $el->username }}" class="btn btn-danger">
                                <i class="fas fa-trash">
                                </i>
                                Delete
                            </a>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="{{'changePassword' . $el->id}}" data-backdrop="static"
                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Change password - {{$el->username}}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action={{ route('user.editPassword', $el->id) }}
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input name="password" type="password" class="form-control"
                                                id="exampleInputPassword1" aria-describedby="emailHelp">
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword2">Reinput password</label>
                                            <input name="reinputPassword" type="password" class="form-control"
                                                id="exampleInputPassword2">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

@include('components.super.delete-user')
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('password.success'))

<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    setTimeout(() => {
        Toast.fire({
            icon: 'success',
            title: 'Success update password',
        });
    }, 1500);

</script>

@endif


@if(session('register.success'))

<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    setTimeout(() => {
        Toast.fire({
            icon: 'success',
            title: 'Success add users',
        });
    }, 1500);

</script>
@endif


@if(session('error.type.password'))
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000
    });
    Toast.fire({
        icon: 'error',
        title: 'Make sure your input password is the same !!!',
    });

</script>
@endif

@if(session('error.password'))
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000
    });
    Toast.fire({
        icon: 'error',
        title: 'The password must be at least 5 !!!',
    });

</script>
@endif

@include('components.super.delete-user')

@endsection
