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
                                        <th>Kredit</th>
                                        <th>Date</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Isi tabel dengan data -->
                                    @foreach ($allData as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transferAccount->account_no }} -
                                                {{ $transaction->transferAccount->name }}</td>
                                            <td>{{ $transaction->transfer > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                            </td>
                                            <td>{{ $transaction->transfer > 0 ? '0' : 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $transaction->depositAccount->account_no }} -
                                                {{ $transaction->depositAccount->name }}</td>
                                            <td>{{ $transaction->deposit > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                            </td>
                                            <td>{{ $transaction->deposit > 0 ? '0' : 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- @foreach ($allData as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transfer_account_no }}</td>
                                        <td>{{ $transaction->deposit_account_no }}</td>
                                        <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}</td>
                                        <td>{{ $transaction->amount < 0 ? 'Rp ' . number_format(abs($transaction->amount), 0, ',', '.') : '0' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}</td>
                                    </tr>
                                @endforeach --}}
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
