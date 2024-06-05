@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Selected Transaction Details</h3>
                        </div>
                        <div class="card-body">
                            <table class="table projects">
                                <thead>
                                    <tr>
                                        <th>No Transaction</th>
                                        <th>Account Number</th>
                                        <th>Account Name</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
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
                                    @foreach ($transactionDetails as $details)
                                        @foreach ($details as $detail)
                                            <tr>
                                                <td>{{ $detail['no_transaction'] }}</td>
                                                <td>{{ $detail['account_number'] }}</td>
                                                <td>{{ $detail['account_name'] }}</td>
                                                <td>{{ $detail['debit'] > 0 ? 'Rp ' . number_format($detail['debit'], 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ $detail['credit'] > 0 ? 'Rp ' . number_format($detail['credit'], 0, ',', '.') : '0' }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($detail['date'])->format('j F Y') }}</td>
                                                <td>{{ $detail['description'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($detail['created_at'])->format('j F Y') }}</td>
                                            </tr>
                                            @php
                                                $totalDebit += $detail['debit'];
                                                $totalKredit += $detail['credit'];
                                            @endphp
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td><strong>{{ 'Rp ' . number_format($totalDebit, 0, ',', '.') }}</strong></td>
                                        <td><strong>{{ 'Rp ' . number_format($totalKredit, 0, ',', '.') }}</strong></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <a href="{{ route('journal.detail.selected.pdf', ['selectedNoTransactions' => $selectedNoTransactions]) }}"
                                target="_blank" class="btn btn-warning btn-sm mt-2" id="print-pdf">
                                <i class="fa-solid fa-file-pdf fa-bounce"
                                    style="color: #000000; margin-right:2px;"></i>Print PDF
                            </a>
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
