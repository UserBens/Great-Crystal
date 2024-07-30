@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Expenditure Search</h2>
        <form action="{{ route('expenditure.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="sort">Sort By</label>
                    <select name="sort" class="form-control" id="sort-select">
                        <option value="">Default</option>
                        <option value="oldest" {{ $form->sort === 'oldest' ? 'selected' : '' }}>Date (Oldest First)</option>
                        <option value="newest" {{ $form->sort === 'newest' ? 'selected' : '' }}>Date (Newest First)</option>
                    </select>
                    <input type="hidden" name="order" id="sort-order" value="{{ $form->order }}">
                </div>
                <div class="col-md-4">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $form->date ?? '' }}">
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
                    <h3>No expenditure found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no expenditure data found -->

            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No expenditure has been created yet. Click the button below to create expenditure!</h3>
                    {{-- <a role="button" href="/admin/expenditure/create" class="btn btn-success mt-4">
                        <i class="fa-solid fa-plus"></i> Create Expenditure
                    </a> --}}

                    <div class="btn-group">
                        <a type="button" href="{{ route('expenditure.create') }}" class="btn btn-success btn-sm mt-3">
                            <i class="fa-solid fa-plus"></i> Add Expenditure
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Display data when expenditure data is available -->
            <!-- Add button to register new expenditure -->
            <div class="btn-group">
                <a type="button" href="{{ route('expenditure.create') }}" class="btn btn-success btn-sm mt-3">
                    <i class="fa-solid fa-plus"></i> Add Expenditure
                </a>
            </div>

            <!-- Display expenditure data in a table -->
            <div class="card card-dark mt-4">
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
                                <th style=>#</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount Spent</th>
                                <th>Spent At</th>
                                <th >Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through expenditure data -->
                            @foreach ($data as $expenditure)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $expenditure->type }}</td>
                                    <td style="max-width: 200px;">{{ $expenditure->description }}</td>
                                    <td>Rp.{{ number_format($expenditure->amount_spent, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expenditure->spent_at)->format('j F Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-warning"
                                            href="/admin/expenditure/{{ $expenditure->id }}/edit"
                                            style="margin-right: 4px;">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>

                                        {{-- @can('delete-expenditure') --}}                                   
                                        <button type="button" class="btn btn-sm delete-btn btn-danger"
                                            data-id="{{ $expenditure->id }}">
                                            <i class="fas fa-trash mr-1" style=""></i>Delete
                                        </button>
                                        {{-- @endcan --}}                                
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination with adjusted layout -->
                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div class="mb-3">
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
                            url: '{{ route('expenditure.destroy', ['id' => ':id']) }}'
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

    {{-- search button --}}
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
