@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Student Bill Detail</h2>
        <a href="{{ route('reports.student-bill-detail.export', ['student_id' => $student->id]) }}"
            class="btn btn-primary btn-sm mb-3" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <!-- Student Information Card -->
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Student Information</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name :</strong> {{ $student->name }}</p>
                        <p><strong>Grade :</strong> {{ $student->grade->name }}</p>
                        <p><strong>Class :</strong> {{ $student->grade->class }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Bills :</strong> Rp {{ number_format($summary->total, 0, ',', '.') }}</p>
                        <p><strong>Paid Bills :</strong> Rp {{ number_format($summary->paid, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bills Table -->
        @if (sizeof($bills) == 0)
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No bills found for your search criteria!</h3>
                </div>
            </div>
        @else
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Bills List</h3>
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
                                <th style="width: 2%">Invoice Number</th>
                                <th style="width: 15%">Bill Type</th>
                                <th style="width: 20%">Amount</th>
                                <th style="width: 24%">Due Date</th>
                                <th style="width: 24%">Paid Date</th>
                                <th style="width: 15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bills as $bill)
                                <tr>
                                    <td>{{ $bill->number_invoice }}</td>
                                    <td>{{ $bill->type }}</td>

                                    @if ($bill->type === 'Capital Fee')
                                        <td>Rp {{ number_format($bill->amount_installment, 0, ',', '.') }}</td>
                                    @else
                                        <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                    @endif

                                    <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                                    <td>
                                        @if ($bill->paidOf)
                                            {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                                        @else
                                            <div class="text-danger">
                                                <small><i class="fas fa-exclamation-circle"></i> Belum terbayar</small><br>
                                                <small><i class="fas fa-exclamation-triangle"></i> Terkena charge</small>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($bill->paidOf)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-danger">Not Yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div class="mb-3">
                            Showing {{ $bills->firstItem() }} to {{ $bills->lastItem() }} of
                            {{ $bills->total() }} results
                        </div>
                        <div>
                            {{ $bills->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
