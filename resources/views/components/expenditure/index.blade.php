@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">

        {{-- @if ($totalExpenditure)
            <div class="mt-3">
                <h4>Total Expenditure: Rp.{{ number_format($totalExpenditure, 0, ',', '.') }}</h4>
            </div>
        @endif --}}
        <h2 class="text-center display-4 mb-3">Expenditure Search</h2>
        {{-- <form class="mt-5" action="/admin/teachers">
            <!-- Form input fields -->
        </form> --}}

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No expenditure found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no expenditure data found -->

            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No expenditure has been created yet. Click the button below to create expenditure!</h3>
                    <a role="button" href="/admin/expenditure/create" class="btn btn-success mt-4">
                        <i class="fa-solid fa-plus"></i> Create Expenditure
                    </a>
                </div>
            </div>
        @else
            <!-- Display data when expenditure data is available -->
            <!-- Add button to register new expenditure -->
            <form action="/admin/expenditure" method="GET" class="input-group input-group-lg">
                <input name="search" type="search" value="{{ $form->search }}" class="form-control form-control-lg"
                    placeholder="Type Expenditure here">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-lg btn-default">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>



            <a type="button" href="/admin/expenditure/create" class="btn btn-success btn mt-5 mx-2">
                <i class="fa-solid fa-plus"></i> Create Expenditure
            </a>

            {{-- Notifikasi Success
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}

            <!-- Display expenditure data in a table -->
            <div class="card card-dark mt-5">
                <div class="card-header">
                    <h3 class="card-title">Total Expenditure : Rp.{{ number_format($totalExpenditure, 0, ',', '.') }}</h3>
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
                                <th style="width: 3%">#</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount Spent</th>
                                <th>Spent At</th>
                                <th style="width: 8%;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through expenditure data -->
                            @foreach ($data as $expenditure)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $expenditure->type }}</td>
                                    <td>{{ $expenditure->description }}</td>
                                    <td>Rp.{{ number_format($expenditure->amount_spent, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expenditure->spent_at)->format('Y-m-d') }}</td>
                                    <td class="project-actions text-right">
                                        <!-- Add action buttons here (view, edit, delete, etc.) -->
                                        {{-- <td class="project-actions text-right toastsDefaultSuccess">
                                            <a class="btn btn-primary {{ session('role') == 'admin' ? 'btn' : 'btn-sm' }}"
                                                href="">
                                                <i class="fas fa-folder">
                                                </i>
                                                View
                                            </a>
                                            @if ($el->is_active)
                                                <a class="btn btn-info {{ session('role') == 'admin' ? 'btn' : 'btn-sm' }}"
                                                    href="update/{{ $el->unique_id }}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit
                                                </a>
                                            @endif
                                            @if (session('role') == 'superadmin' && $el->is_active)
                                                <a href="javascript:void(0)" id="delete-student" data-id="{{ $el->id }}"
                                                    data-name="{{ $el->name }}" class="btn btn-danger btn-sm">
                                                    <i class="fas fa fa-ban">
                                                    </i>
                                                    Deactive
                                                </a>
                                            @elseif ($el->is_graduate && sizeof($grades) > $el->grade_id)
                                                <a href="/admin/student/re-registration/{{ $el->unique_id }}"
                                                    class="btn btn-dark btn-sm">
                                                    <i class="fas fa fa-register">
                                                    </i>
                                                    Re-registration
                                                </a>
                                            @elseif (session('role') == 'superadmin' && !$el->is_graduate)
                                                <a href="javascript:void(0)" id="active-student"
                                                    data-id="{{ $el->id }}" data-name="{{ $el->name }}"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa fa-register">
                                                    </i>
                                                    Activate
                                                </a>
                                            @endif
                                        </td> --}}
                                        <a class="btn btn-info btn-sm"
                                            href="/admin/expenditure/{{ $expenditure->id }}/edit">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination with adjusted layout -->
                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div>
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
                        </div>
                        <div>
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>

            <!-- Pagination links -->
            <!-- Include pagination logic here -->
        @endif

    </div>

    {{-- @if (session('success'))
        <script>
            swal({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK",
            });
        </script>
    @endif
     --}}

    {{-- SweetAlert --}}
    @if (session('success'))
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            swal({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK",
            });
        </script>
    @endif

    <script>
        // Tangani klik tombol pencarian
        document.getElementById('searchButton').addEventListener('click', function() {
            // Dapatkan nilai dari input pencarian
            var searchValue = document.getElementById('searchInput').value;

            // Redirect ke halaman pencarian dengan parameter 'search'
            window.location.href = '/admin/expenditure?search=' + searchValue;
        });
    </script>
@endsection
