@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <h2 class="text-center display-4 mb-4">Journal Search</h2>

            <div class="m-2">
                <!-- Form untuk filter data -->
                <!-- Isi formulir disini -->
                {{-- <div class="m-3"> --}}

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
                                    <option value="asc" {{ $form->order === 'asc' ? 'selected' : '' }}>Ascending
                                    </option>
                                    <option value="desc" {{ $form->order === 'desc' ? 'selected' : '' }}>Descending
                                    </option>
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
                {{-- </div> --}}
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Report Journal</h3>
                        </div>

                        <!-- Tabel untuk menampilkan data -->
                        <div class="card-body p-0">
                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th>Account Number</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Date</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previousTransactionId = null;
                                    @endphp
                                    @foreach ($allData as $transaction)
                                        @if ($previousTransactionId && $previousTransactionId != $transaction->id)
                                            <!-- Baris pemisah antar kelompok transaksi -->
                                            <tr class="transaction-separator"></tr>
                                        @endif

                                        <tr>
                                            {{-- Untuk transfer_account_id --}}
                                            @if ($transaction->transfer_account_id)
                                                <td>{{ $transaction->transferAccount->account_no }} -
                                                    {{ $transaction->transferAccount->name }}</td>

                                                <td>0</td> {{-- Debit --}}
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td> {{-- Credit --}}
                                                
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('journal.detail', $transaction->id) }}"
                                                        class="btn btn-primary btn-sm">View Detail</a>
                                                </td>
                                            @endif
                                        </tr>

                                        <tr>
                                            {{-- Untuk deposit_account_id --}}
                                            @if ($transaction->deposit_account_id)
                                                <td>{{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td>
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td> {{-- Debit --}}
                                                <td>0</td> {{-- Credit --}}
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('journal.detail', $transaction->id) }}"
                                                        class="btn btn-primary btn-sm">View Detail</a>
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
