@extends('layouts.admin.master')

@section('content')
    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item"><a href="{{ url('/admin/payment-materialfee') }}">Material Fee</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Material Fee</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Student Name</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $student->name }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Grade</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $student->grade->name }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Class</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $student->grade->class }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Total Material Fees</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $materialFees->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($materialFees as $fee)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">{{ ucfirst($fee->type) }} Material Fee</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Total Amount</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">Rp {{ number_format($fee->amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Down Payment</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">Rp {{ number_format($fee->dp, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Discount</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">{{ $fee->discount }}%</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Installment Terms</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">{{ $fee->installment ?? 0 }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Amount per Term</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">Rp
                                            {{ number_format($fee->amount_installment, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Created at</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">{{ $fee->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4 p-4">
                        <div class="card-header">
                            <h3 class="card-title">Summary</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach ($materialFees as $fee)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                {{ ucfirst($fee->type) }}:
                                            </td>
                                            <td align="right" class="p-1">
                                                Rp {{ number_format($fee->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @if ($fee->dp > 0)
                                            <tr>
                                                <td align="left" class="p-1">Down Payment:</td>
                                                <td align="right" class="p-1">
                                                    -Rp {{ number_format($fee->dp, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($fee->discount > 0)
                                            <tr>
                                                <td align="left" class="p-1">Discount:</td>
                                                <td align="right" class="p-1">
                                                    {{ $fee->discount }}%
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="2">
                                            <hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="p-1 font-weight-bold">Total:</td>
                                        <td align="right" class="p-1 font-weight-bold">
                                            Rp {{ number_format($materialFees->sum('amount'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <a href="#" class="btn btn-warning w-100 mb-2">
                        <i class="fa-solid fa-file-pdf fa-bounce" style="color: #000000; margin-right:2px;"></i>
                        Print PDF
                    </a>

                    <a href="#" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-check" style="margin-right:2px;"></i>
                        Mark as Paid
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
