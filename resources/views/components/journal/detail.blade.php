@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Transaction Detail</h3>
                        </div>
                        <div class="card-body">
                            <table class="table projects">
                                <thead>
                                    <tr>
                                        <th>No Transaction</th>
                                        <th>Account Number</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDebit = 0;
                                        $totalKredit = 0;
                                    @endphp
                                        @if ($transaction->transfer_account_id)
                                            <tr>
                                                <td>{{ $transaction->no_transaction }}</td>
                                                <td>{{ $transaction->transferAccount->account_no }} -
                                                    {{ $transaction->transferAccount->name }}</td>
                                                <td>{{ $transaction->amount < 0 ? 'Rp ' . number_format(-$transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ $transaction->description }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                            </tr>
                                            @php
                                                $totalDebit += $transaction->amount;
                                            @endphp
                                        @endif
                                        @if ($transaction->deposit_account_id)
                                            <tr>
                                                <td>{{ $transaction->no_transaction }}</td>
                                                <td>{{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td>
                                                <td>{{ $transaction->amount > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ $transaction->amount < 0 ? 'Rp ' . number_format(-$transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                                <td>{{ $transaction->description }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                            </tr>
                                            @php
                                                $totalKredit += $transaction->amount;
                                            @endphp
                                        @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>{{ $totalDebit > 0 ? 'Rp ' . number_format($totalDebit, 0, ',', '.') : '0' }}</strong>
                                        </td>
                                        <td><strong>{{ $totalKredit > 0 ? 'Rp ' . number_format($totalKredit, 0, ',', '.') : '0' }}</strong>
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
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
    <!-- /.content -->
@endsection
