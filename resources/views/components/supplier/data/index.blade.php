@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Supplier Data Search</h2>
        <form action="{{ route('supplier.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="sort">Sort By</label>
                    <select name="sort" class="form-control" id="sort-select">
                        <option>-- All Data --</option>
                        <option value="oldest" {{ $form->sort === 'oldest' ? 'selected' : '' }}>Date (Oldest First)</option>
                        <option value="newest" {{ $form->sort === 'newest' ? 'selected' : '' }}>Date (Newest First)</option>
                    </select>
                    <input type="hidden" name="order" id="sort-order" value="{{ $form->order }}">
                </div>
                <div class="col-md-4">
                    <label for="date">Date</label>
                    <input type="date" name="created_at" class="form-control" value="{{ $form->created_at ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="date">Search Data</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ $form->search ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Not found on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no transfer data found -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Click the button below to create Supplier Data!</h3>
                    <div class="btn-group">
                        <a type="button" href="{{ route('create-supplier.create') }}" class="btn btn-success btn-sm mt-3">
                            <i class="fa-solid fa-plus"></i> Add Supplier
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="btn-group">
                <a type="button" href="{{ route('create-supplier.create') }}" class="btn btn-success btn-sm mt-3">
                    <i class="fa-solid fa-plus"></i> Add Supplier
                </a>
            </div>
            <!-- Display Cash or Bank data in a table -->
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">Supplier Data List</h3>
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through transfer data -->
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }} </td>
                                    <td>{{ $item->no_telp }} </td>
                                    <td>{{ $item->email }} </td>
                                    <td>{{ $item->address }} </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('j F Y') }}</td>

                                    <td>
                                        <a href="{{ route('view-supplier.update', $item->id) }}"
                                            class="btn btn-sm btn-warning"><i class="fas fa-pen"
                                                style="margin-right: 4px"></i>Edit</a>

                                        <button type="button" class="btn btn-sm delete-btn btn-danger"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>

                                        {{-- <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#importModal" data-id="{{ $item->id }}">
                                                <i class="fas fa-upload" style="margin-right: 4px"></i>Upload
                                            </button> --}}

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination with adjusted layout -->
                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div class="mb-3">
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of
                            {{ $data->total() }} results
                        </div>
                        <div>
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Include jQuery and SweetAlert library -->
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/projects.js') }}" defer></script>


    <script>
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            @endif

            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mengirim request DELETE menggunakan Ajax
                        $.ajax({
                            url: '{{ route('supplier.destroy', ['id' => ':id']) }}'
                                .replace(':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Refresh halaman setelah menghapus
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Failed!',
                                    response.responseJSON.error ? response
                                    .responseJSON.error :
                                    'There was an error deleting the invoice supplier.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

        });
    </script>   

    <script>
        document.getElementById('sort-select').addEventListener('change', function() {
            let order = this.options[this.selectedIndex].getAttribute('data-order');
            document.getElementById('sort-order').value = order;
            this.form.submit();
        });
    </script>

    <script>
        document.getElementById('status-select').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('sort-select').addEventListener('change', function() {
            let order = this.options[this.selectedIndex].getAttribute('data-order');
            document.getElementById('sort-order').value = order;
            this.form.submit();
        });
    </script>
@endsection
