@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <h2 class="text-center display-4 mb-4">Journal Search</h2>

            <div class="m-4">
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
            </div>

            <div class="row justify-content-center m-3">
                <!-- Menggunakan kelas justify-content-center untuk mengatur div row agar kontennya berada di tengah -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Report Journal</h3>
                            {{-- <h3 class="card-title align">Income Transactions</h3> --}}
                        </div>
                        {{-- <h4 class="p-3 m-0">Total Income : Rp {{ number_format($totalpaid, 0, ',', '.') }}</h4> --}}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive"> <!-- Menghapus kelas mx-auto dari sini -->
                                @foreach ($allData as $transaction)
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Account Number</td>
                                                <td>{{ $transaction->transferAccount->account_no }} -
                                                    {{ $transaction->transferAccount->name }}</td>
                                                {{-- <td>{{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td> --}}
                                            </tr>
                                            <tr>
                                                <td>Debit</td>
                                                <td>{{ $transaction->deposit > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kredit</td>
                                                <td>{{ $transaction->transfer > 0 ? '0' : 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Date</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Created At</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tbody>
                                            <tr>
                                                <td>Account Number</td>
                                                {{-- <td>{{ $transaction->depostiAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td> --}}
                                                <td>{{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Debit</td>
                                                <td>{{ $transaction->deposit > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kredit</td>
                                                <td>{{ $transaction->transfer > 0 ? '0' : 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Date</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Created At</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endforeach

                                {{-- @foreach ($allData as $transaction)
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Account Number</td>
                                            <td>
                                                @if ($transaction->type === 'transaction_transfer')
                                                    {{ $transaction->transferAccount->account_no }} -
                                                    {{ $transaction->transferAccount->name }}
                                                @elseif($transaction->type === 'transaction_send' || $transaction->type === 'transaction_receive')
                                                    {{ $transaction->depositAccount->account_no }} -
                                                    {{ $transaction->depositAccount->name }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($transaction->type === 'transaction_transfer')
                                                    Kredit
                                                @elseif($transaction->type === 'transaction_send' || $transaction->type === 'transaction_receive')
                                                    Debit
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->type === 'transaction_transfer')
                                                    {{ 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                                @elseif($transaction->type === 'transaction_send' || $transaction->type === 'transaction_receive')
                                                    {{ $transaction->deposit > 0 ? 'Rp ' . number_format($transaction->amount, 0, ',', '.') : '0' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($transaction->type === 'transaction_transfer')
                                                    Debit
                                                @elseif($transaction->type === 'transaction_send' || $transaction->type === 'transaction_receive')
                                                    Kredit
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->type === 'transaction_transfer')
                                                    {{ $transaction->deposit > 0 ? '0' : 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}
                                                @elseif($transaction->type === 'transaction_send' || $transaction->type === 'transaction_receive')
                                                    {{ '0' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('j F Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach --}}


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
