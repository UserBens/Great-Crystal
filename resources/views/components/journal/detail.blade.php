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
                            <!-- Tabel Transaksi -->
                            <table class="table projects">
                                <thead>
                                    <tr>
                                        <th>No Transaction</th>
                                        <th>Account Number</th>
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
                                    @foreach ($transactionDetails as $detail)
                                        <tr>
                                            <td>
                                                @if ($type === 'invoice_supplier')
                                                    {{ $transaction->no_invoice ?? 'N/A' }}
                                                @elseif ($type === 'bill')
                                                    {{ $transaction->number_invoice ?? 'N/A' }}
                                                @else
                                                    {{ $transaction->no_transaction ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td>{{ $detail['account_number'] }} - {{ $detail['account_name'] }}</td>
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

                            <div class="mt-3">


                                @if ($type === 'invoice_supplier')
                                    <div class="mt-3">
                                        <h4>History of Credit Account Changes</h4>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Old Kredit Account</th>
                                                    <th>New Kredit Account</th>
                                                    <th>Change At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $transaction->oldAccount->account_no ?? 'N/A' }} -
                                                        {{ $transaction->oldAccount->name ?? 'N/A' }}</td>
                                                    <td>{{ $transaction->newAccount->account_no ?? 'N/A' }} -
                                                        {{ $transaction->newAccount->name ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y, H:i') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if ($type === 'bill')
                                    <div class="mt-3">
                                        <h4>History of Debit Account Changes</h4>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Old Deposit Account</th>
                                                    <th>New Deposit Account</th>
                                                    <th>Change At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{ $transaction->depositAccount->account_no ?? 'N/A' }} -
                                                        {{ $transaction->depositAccount->name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ $transaction->newAccount->account_no ?? 'N/A' }} -
                                                        {{ $transaction->newAccount->name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y, H:i') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif


                            </div>

                            <a href="{{ route('journal.detail.pdf', ['id' => $transaction->id, 'type' => $type]) }}"
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
