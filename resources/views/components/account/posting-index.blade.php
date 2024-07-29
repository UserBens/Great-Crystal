@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-4">Posting Balance Account Search</h2>
        <div class="m-1">
            <form action="{{ route('balance-post.index') }}" method="GET" class="mb-3">
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

        @if (sizeof($data) == 0 && ($form->sort || $form->order || $form->search))
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Balance Account found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Balance Account has been created yet. Click the button below to create Balance Account!</h3>
                    <div class="btn-group">
                    </div>
                </div>
            </div>
        @else
            <div class="btn-group mt-2">
                <a type="button" href="/admin/account/create-account" class="btn btn-success btn-sm mt-3"
                    style="margin-right: 8px">
                    <i class="fa-solid fa-plus"></i> Create Account
                </a>
            </div>
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">List Balance Account</h3>
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
                                <th>Balance</th>
                                <th>Post/Unpost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $account)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $account->account_no }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $categories->where('id', $account->account_category_id)->first()->category_name }}
                                    </td>
                                    <td>Rp.{{ number_format($account->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <!-- Post Form -->
                                            <form action="{{ route('balance.post', $account->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $account->id }}">

                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="me-2 flex-grow-1">
                                                        <label for="posted_date" class="form-label">Post Date:</label>
                                                        <div class="input-group">
                                                            <input type="month" id="posted_date" name="posted_date"
                                                                value="{{ isset($account->posted_date) ? \Carbon\Carbon::parse($account->posted_date)->format('Y-m') : '' }}"
                                                                required class="form-control">
                                                            <button class="btn btn-sm btn-primary" type="submit"
                                                                style="margin-left: 12px;">Post</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <!-- Unpost Form -->
                                            <form action="{{ route('balance.unpost') }}" method="POST">
                                                @csrf

                                                <div class="d-flex align-items-center">
                                                    <div class="me-2 flex-grow-1">
                                                        <label for="date" class="form-label">Unpost Date:</label>
                                                        <div class="input-group">
                                                            <input type="month" id="date" name="date"
                                                                value="{{ isset($account->posted_date) ? \Carbon\Carbon::parse($account->posted_date)->format('Y-m') : '' }}"
                                                                required class="form-control">
                                                            <button class="btn btn-sm btn-danger" type="submit"
                                                                style="margin-left: 12px;">Unpost</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4 px-3">
                    <div class="mb-3">
                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }}
                        results
                    </div>
                    <div>
                        {{ $data->links('pagination::bootstrap-4') }}
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
        document.addEventListener('DOMContentLoaded', function() {

            initializeAutoNumeric();

            function initializeAutoNumeric() {
                AutoNumeric.multiple('.currency', {
                    currencySymbol: 'Rp.',
                    decimalCharacter: ',',
                    digitGroupSeparator: '.',
                    decimalPlaces: 0,
                });
            }

            // Remove thousand separators before form submit
            document.getElementById('accountForm').addEventListener('submit', function() {
                const currencyInputs = document.querySelectorAll('.currency');
                currencyInputs.forEach(input => {
                    input.value = input.value.replace(/\./g, ''); // Remove all thousand separators
                });
            });
        });
    </script>

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
                            url: '{{ route('balance.destroy', ['id' => ':id']) }}'
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
        function removeThousandSeparator(input) {
            // Remove thousand separator (.)
            let value = input.value.replace(/\./g, '');

            // Update input value
            input.value = value;
        }

        // Fungsi untuk menghapus pemisah ribuan sebelum formulir disubmit
        function submitForm() {
            // Hapus pemisah ribuan dari input amount_spent
            let amountInput = document.getElementById("amount");
            removeThousandSeparator(amountInput);

            // Submit formulir
            document.getElementById("accountForm").submit();
        }
    </script>

    <script>
        $(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy', // Format sesuai dengan yang Anda inginkan
                autoclose: true
            });
        });
    </script>

@endsection
