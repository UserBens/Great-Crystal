@extends('layouts.admin.master')
@section('content')

    <div class="container-fluid">
        <h2 class="text-center display-4 mb-4">Account Number Search</h2>

        <div class="m-1">
            <form action="{{ route('account.index') }}" method="GET" class="mb-3">
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
                    <a type="button" href="/admin/account/create-account" class="btn btn-success btn-sm mt-3">
                        <i class="fa-solid fa-plus"></i> Create Account
                    </a>
                </div>
            </div>
        @else
        <div class="btn-group mt-2">

            <a type="button" href="/admin/account/create-account" class="btn btn-success mt-3">
                <i class="fa-solid fa-plus"></i> Create Account
            </a>
        </div>

            {{-- <div class="btn-group mt-2">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fa-solid fa-plus"></i> Create Account
                </button>
                <div class="dropdown-menu" style="min-width: 100%">

                    <a class="dropdown-item" href="/admin/account/create-account"></a>

                </div>
            </div> --}}


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
                                <th>Category Account</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th style="width: 15%;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $account)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>{{ $account->account_no }}</td>
                                    <td>{{ $categories->firstWhere('id', $account->account_category_id)->category_name }}
                                    </td>
                                    <td>{{ $account->type }}</td>
                                    <td>Rp.{{ number_format($account->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($account->created_at)->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-warning btn-sm mr-2"
                                                href="/admin/account/{{ $account->id }}/edit">
                                                <i class="fas fa-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-toggle="modal" data-id="{{ $account->id }}">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Konfirmasi Penghapusan -->
                                <div id="deleteModal{{ $account->id }}" class="modal fade" tabindex="-1" role="dialog">
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
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Modal Konfirmasi Penghapusan -->
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

    {{-- delete button --}}
    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let accountId = this.getAttribute('data-id');
                $('#deleteModal' + accountId).modal('show');
                return false;
            });
        });
    </script>
@endsection
