@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <h2 class="text-center display-4 mb-4">Journal Search</h2>
            <div class="m-1">
                <form action="{{ route('journal.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type">Type Transaction</label>
                            <select name="type" class="form-control">
                                <option value="">-- All Data --</option>
                                <option value="transaction_transfer"
                                    {{ $form->type === 'transaction_transfer' ? 'selected' : '' }}>Transaction Transfer
                                </option>
                                <option value="transaction_receive"
                                    {{ $form->type === 'transaction_receive' ? 'selected' : '' }}>Transaction Receive
                                </option>
                                <option value="transaction_send" {{ $form->type === 'transaction_send' ? 'selected' : '' }}>
                                    Transaction Send</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort">Sort By</label>
                            <select name="sort" class="form-control" id="sort-select">
                                <option value="">-- Select Sort --</option>
                                <option value="date"
                                    {{ $form->sort === 'date' && $form->order === 'asc' ? 'selected' : '' }}
                                    data-order="asc">Date (Oldest First)</option>
                                <option value="date"
                                    {{ $form->sort === 'date' && $form->order === 'desc' ? 'selected' : '' }}
                                    data-order="desc">Date (Newest First)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $form->date ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="search">Search Data</label>
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
                    <input type="hidden" name="order" id="order" value="{{ $form->order ?? 'desc' }}">
                </form>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Report Journal</h3>
                        </div>
                        <div class="card-body p-0">
                            <form action="{{ route('journal.detail.selected') }}" method="GET">
                                @csrf
                                <table class="table table-striped projects">
                                    <thead>
                                        <tr>
                                            <th>Select Item</th>
                                            <th>No Transaction</th>
                                            <th>Transfer Account</th>
                                            <th>Deposit Account</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allData as $item)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="id[]" value="{{ $item->id }}">
                                                    <input type="hidden" name="type[]" value="{{ $item->type }}">
                                                </td>
                                                <td>{{ $item->no_transaction }}</td>
                                                <td>{{ $item->transfer_account_name }}</td>
                                                <td>{{ $item->deposit_account_name }}</td>
                                                <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->format('j F Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('j F Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('journal.detail', ['id' => $item->id, 'type' => $item->type]) }}"
                                                            class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>
                                                            View</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary m-3">View Selected Details</button>
                            </form>

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
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortSelect = document.getElementById('sort-select');
            const orderInput = document.getElementById('order');

            sortSelect.addEventListener('change', function() {
                const selectedOption = sortSelect.options[sortSelect.selectedIndex];
                orderInput.value = selectedOption.getAttribute('data-order');
            });
        });
    </script>
@endsection
