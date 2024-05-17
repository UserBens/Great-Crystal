@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">

        <h2 class="text-center display-4 mb-4">Account Number Search</h2>


        {{-- code here --}}
        <div class="m-1">
            <form action="{{ route('cash.index') }}" method="GET" class="mb-3">
                <div class="row">

                    <div class="col-md-3">
                        <label for="date">Type Transaction</label>

                        <select name="type" class="form-control">
                            <option value="">-- All Data --</option>
                            <option value="transaction_transfer"
                                {{ $form->type === 'transaction_transfer' ? 'selected' : '' }}>
                                Transaction Transfer</option>
                            <option value="transaction_send" {{ $form->type === 'transaction_send' ? 'selected' : '' }}>
                                Transaction Send</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date">Sort By</label>

                        <select name="sort" class="form-control">
                            <option value="">-- All Data --</option>
                            <option value="amount" {{ $form->sort === 'amount' ? 'selected' : '' }}>Amount</option>
                            <option value="date" {{ $form->sort === 'date' ? 'selected' : '' }}>Date</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $form->date ?? '' }}">
                    </div>

                    <div class="col-md-3">
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

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Account Number found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no expenditure data found -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Account Number has been created yet. Click the button below to create Account Number!</h3>
                    <a type="button" href="/admin/account/create-account" class="btn btn-success btn mt-3">
                        <i class="fa-solid fa-plus"></i> Create Account
                    </a>
                </div>
            </div>
        @else
            <a type="button" href="/admin/account/create-account" class="btn btn-success btn mt-3">
                <i class="fa-solid fa-plus"></i> Create Account
            </a>

            <!-- Display Cash or Bank data in a table -->
            <div class="card card-dark mt-5">
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
                                <th style="width: 3%">#</th>
                                <th>Name</th>
                                <th>Account Number</th>
                                <th>Type</th>
                                <th>Bank Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th style="width: 8%;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through expenditure data -->
                            @foreach ($data as $account)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $account->account_no }}</td>
                                    <td>{{ $account->type }}</td>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>Rp.{{ number_format($account->amount, 0, ',', '.') }}</td>
                                    {{-- <td style="max-width: 200px;">{{ $account->description }}</td> --}}
                                    <td>{{ \Carbon\Carbon::parse($account->created_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="/admin/account/{{ $account->id }}/edit"
                                            style="margin-right: 5px;">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>

                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $account->id }}"
                                            style="margin-right: 5px;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                    <td class="project-actions text-right">
                                        <!-- Add action buttons here (view, edit, delete, etc.) -->

                                        <div class="btn-group">


                                            {{-- @can('delete-account') --}}

                                            {{-- @endcan --}}
                                        </div>

                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div id="deleteModal{{ $account->id }}" class="modal fade" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda yakin ingin menghapus data ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <form action="{{ route('account.destroy', $account->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Ya,
                                                                Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Modal Konfirmasi Penghapusan -->
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
            <!-- /Display Cash or Bank data in a table -->
        @endif

    </div>

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

    {{-- delete button --}}
    <script>
        // Tangani klik tombol delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let expenditureId = this.getAttribute('data-id');

                // Tampilkan modal konfirmasi penghapusan
                $('#deleteModal' + expenditureId).modal('show');

                // Hentikan tindakan default penghapusan
                return false;
            });
        });
    </script>
@endsection
