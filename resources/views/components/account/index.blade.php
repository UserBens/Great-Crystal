@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-4">Account Number Search</h2>
        <div class="m-1">
            <form action="{{ route('account.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="sort">Sort By</label>
                        <select name="sort" class="form-control" id="sort-select">
                            <option value="">Default</option>
                            <option value="oldest" {{ $form->sort === 'oldest' ? 'selected' : '' }}>Date (Oldest First)
                            </option>
                            <option value="newest" {{ $form->sort === 'newest' ? 'selected' : '' }}>Date (Newest First)
                            </option>
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
        </div>

        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Account Number found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Account Number has been created yet. Click the button below to create Account Number!</h3>
                    <div class="btn-group">
                        <a type="button" href="/admin/account/create-account" class="btn btn-success btn-sm mt-3">
                            <i class="fa-solid fa-plus"></i> Add Account
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="btn-group mt-2">
                <a type="button" href="/admin/account/create-account" class="btn btn-success btn-sm mt-3"
                    style="margin-right: 8px">
                    <i class="fa-solid fa-plus"></i> Add Account
                </a>            
            </div>
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">List Account Number</h3>
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
                                <th>Account</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Balance</th>                                
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $account)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $account->account_no }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $categories->firstWhere('id', $account->account_category_id)->category_name }}
                                    <td>{{ $account->type }}
                                    </td>
                                    <td>Rp.{{ number_format($account->amount, 0, ',', '.') }}</td>                                    
                                    <td class="">
                                        <a class="btn btn-warning btn-sm" style="margin-right: 4px"
                                            href="/admin/account/{{ $account->id }}/edit">
                                            <i class="fas fa-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm delete-btn btn-danger"
                                            data-id="{{ $account->id }}">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                            url: '{{ route('account.destroy', ['id' => ':id']) }}'
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

@endsection
