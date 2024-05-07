@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">

        <h2 class="text-center display-4 mb-3">Cash & Bank Search</h2>

        {{-- code here --}}



        <!-- Conditional rendering based on data availability -->
        @if (sizeof($allData) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Cash or Bank found based on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($allData) == 0)
            <!-- Display message when no transfer data found -->

            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No Cash or Bank has been transferred yet. Click the button below to create Transaction!</h3>
                    <div class="btn-group mt-5">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-plus"></i> Create Transaction
                        </button>
                        <div class="dropdown-menu" style="min-width: 100%">
                            {{-- <a class="dropdown-item" href="/admin/cash/create-account">Create New Account</a> --}}
                            <a class="dropdown-item" href="{{ route('transaction-transfer.create') }}">Transfer Money</a>
                            <a class="dropdown-item" href="/admin/cash/transaction-send">Send Money</a>
                            <a class="dropdown-item" href="/admin/cash/transaction-receive">Receive Money</a>
                            <!-- Example of another option -->
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Display data when transfer data is available -->
            <!-- Add button to register new transfer -->
            {{-- <form action="/admin/expenditure" method="GET" class="input-group input-group-lg">
                <input name="search" type="search" value="{{ $form->search }}" class="form-control form-control-lg"
                    placeholder="Type Expenditure here">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-lg btn-default">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form> --}}

            <!-- Form filter untuk menyaring data -->
            <form action="{{ route('cash.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ $form->search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-control">
                            <option value="">-- Select Type --</option>
                            <option value="transaction_transfer"
                                {{ $form->type === 'transaction_transfer' ? 'selected' : '' }}>
                                Transaction Transfer</option>
                            <option value="transaction_send" {{ $form->type === 'transaction_send' ? 'selected' : '' }}>
                                Transaction Send</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-control">
                            <option value="">-- Sort By --</option>
                            <option value="amount" {{ $form->sort === 'amount' ? 'selected' : '' }}>Amount</option>
                            <option value="date" {{ $form->sort === 'date' ? 'selected' : '' }}>Date</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="order" class="form-control">
                            <option value="">-- Order --</option>
                            <option value="asc" {{ $form->order === 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ $form->order === 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="row mt-2">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">-- Status --</option>
                            <option value="paid" {{ $form->status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ $form->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <!-- Tambahkan input fields lainnya sesuai kebutuhan -->
                </div> --}}
                <button type="submit" class="btn btn-primary mt-2">Filter</button>
            </form>


            <div class="btn-group mt-5">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fa-solid fa-plus"></i> Create Transaction
                </button>
                <div class="dropdown-menu" style="min-width: 100%">
                    {{-- <a class="dropdown-item" href="/admin/cash/create-account">Create New Account</a> --}}
                    <a class="dropdown-item" href="{{ route('transaction-transfer.create') }}">Transfer Money</a>
                    <a class="dropdown-item" href="{{ route('transaction-send.create') }}">Send Money</a>
                    <a class="dropdown-item" href="{{ route('transaction-receive.create') }}">Receive Money</a>
                    <!-- Example of another option -->
                </div>
            </div>

            <!-- Display Cash or Bank data in a table -->
            <div class="card card-dark mt-5">
                <div class="card-header">
                    {{-- <h3 class="card-title">Total Expenditure : Rp.{{ number_format($totalExpenditure, 0, ',', '.') }}</h3> --}}
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
                                <th>Transfer From</th>
                                <th>Recipient</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th style="width: 8%;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through transfer data -->
                            @foreach ($allData as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->transferAccount->account_no }} -
                                        {{ $item->transferAccount->name }}</td>
                                    <td>{{ $item->depositAccount->account_no }} -
                                        {{ $item->depositAccount->name }}</td>

                                    <td>Rp.{{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->spent_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <!-- Tampilkan pembeda berdasarkan nilai kolom 'type' -->
                                        @if ($item->type === 'transaction_transfer')
                                            <span class="badge badge-primary">Transfer</span>
                                        @elseif ($item->type === 'transaction_send')
                                            <span class="badge badge-success">Send</span>
                                        @elseif ($item->type === 'transaction_receive')
                                            <span class="badge badge-warning">Receive</span>
                                        @endif
                                    </td>

                                    {{-- <td style="max-width: 200px;">{{ $transfer->description }}</td> --}}
                                    <td class="project-actions text-right">
                                        <!-- Add action buttons here (view, edit, delete, etc.) -->

                                        <div class="btn-group">
                                            {{-- <a class="btn btn-info btn-sm" href="{{ route('transaction-transfer.edit', $transfer->id) }}"
                                                style="margin-right: 5px;">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a> --}}

                                            <button class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $item->id }}" style="margin-right: 5px;">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            {{-- @can('delete-transfer')
                                            @endcan --}}
                                        </div>

                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div id="deleteModal{{ $item->id }}" class="modal fade" tabindex="-1"
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
                                                        {{-- <form action="{{ route('transaction-transfer.destroy', $transfer->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                        </form> --}}
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
                            Showing {{ $allData->firstItem() }} to {{ $allData->lastItem() }} of
                            {{ $allData->total() }} results
                        </div>
                        <div>
                            {{ $allData->links('pagination::bootstrap-4') }}
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
