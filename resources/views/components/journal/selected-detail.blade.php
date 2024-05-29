@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">Transaction Selected Detail</h3>
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
                                    @foreach ($transactionDetails as $detail)
                                    <tr>
                                        <td>{{ $detail[0]['no_transaction'] }}</td>
                                        <td>{{ $detail[0]['account_number'] }} - {{ $detail[0]['account_name'] }}</td>
                                        <td>{{ $detail[0]['debit'] > 0 ? 'Rp ' . number_format($detail[0]['debit'], 0, ',', '.') : '0' }}</td>
                                        <td>{{ $detail[0]['credit'] > 0 ? 'Rp ' . number_format($detail[0]['credit'], 0, ',', '.') : '0' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail[0]['date'])->format('j F Y') }}</td>
                                        <td>{{ $detail[0]['description'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail[0]['created_at'])->format('j F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $detail[1]['no_transaction'] }}</td>
                                        <td>{{ $detail[1]['account_number'] }} - {{ $detail[1]['account_name'] }}</td>
                                        <td>{{ $detail[1]['debit'] > 0 ? 'Rp ' . number_format($detail[1]['debit'], 0, ',', '.') : '0' }}</td>
                                        <td>{{ $detail[1]['credit'] > 0 ? 'Rp ' . number_format($detail[1]['credit'], 0, ',', '.') : '0' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail[1]['date'])->format('j F Y') }}</td>
                                        <td>{{ $detail[1]['description'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail[1]['created_at'])->format('j F Y') }}</td>
                                    </tr>
                                    @php
                                        $totalDebit += $detail[0]['debit'];
                                        $totalKredit += $detail[0]['credit'];
                                        $totalDebit += $detail[1]['debit'];
                                    @endphp
                                @endforeach
                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>{{ $totalDebit > 0 ? 'Rp ' . number_format($totalDebit, 0, ',', '.') : '0' }}</strong></td>
                                        <td><strong>{{ $totalKredit > 0 ? 'Rp ' . number_format($totalKredit, 0, ',', '.') : '0' }}</strong></td>
                                        <td colspan=""></td>
                                    </tr>
                                </tfoot>
                            </table>

                            {{-- Tambahkan tombol print PDF jika diperlukan --}}
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
