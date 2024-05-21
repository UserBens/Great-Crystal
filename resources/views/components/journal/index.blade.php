@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <h2 class="text-center display-4 mb-4">Journal Search</h2>

            <div class="m-1">
                <form action="{{ route('journal.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type">Type Transaction</label>
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
                            <label for="sort">Sort By</label>
                            <select name="sort" class="form-control" id="sort-select">
                                <option value="">-- All Data --</option>
                                <option value="date"
                                    {{ $form->sort === 'date' && $form->order !== 'asc' ? 'selected' : '' }}
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
                    <input type="hidden" name="order" id="order" value="{{ $form->order ?? 'asc' }}">
                </form>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Report Journal</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table projects">
                                <thead>
                                    <tr>
                                        <th>No Transaction</th>
                                        <th>Account Number</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Date</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previousTransactionId = null;
                                    @endphp
                                    @foreach ($allData as $transaction)
                                        <tr>
                                            {{-- Untuk transfer_account_id --}}
                                            @if ($transaction->transfer_account_id)
                                                <td>{{ $transaction->no_transaction }}</td>
                                                <td>{{ $transaction->transferAccount->account_no }} -
                                                    {{ $transaction->transferAccount->name }}</td>

                                                <td>0</td> {{-- Debit --}}
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td> {{-- Credit --}}

                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('journal.detail', $transaction->id) }}"
                                                            class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>
                                                            View
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>

                                        <tr>
                                            {{-- Untuk deposit_account_id --}}
                                            @if ($transaction->deposit_account_id)
                                                <td>{{ $transaction->no_transaction }}</td>
                                                <td>{{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td>
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td> {{-- Debit --}}
                                                <td>0</td> {{-- Credit --}}
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('journal.detail', $transaction->id) }}"
                                                            class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>
                                                            View
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>

                                        @php
                                            $previousTransactionId = $transaction->id;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
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
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-wrapper -->
@endsection
