@extends('layouts.admin.master')
@section('content')
    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item"><a href="{{ url('/admin/bills') }}">Bill</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Payment</li>
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
                                    <p class="mb-0">Nomor Invoice</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $data->number_invoice }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Student Name</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">

                                        {{ $data->student->name }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Type</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $data->type }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Subject</p>
                                </div>
                                <div class="col-sm-8">
                                    @php
                                        $subject = '-';
                                        if ($data->subject) {
                                            $subject = $data->installment
                                                ? $data->type . ' installment ' . '( ' . $data->subject . ' )'
                                                : 'Cash';
                                        }
                                    @endphp
                                    <p class="text-muted mb-0">{{ $subject }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Grade</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ $data->student->grade->name }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Class</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ $data->student->grade->class }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            @php
                                $currentDate = date('y-m-d');
                                $dateInvoice1 = date_create($currentDate);
                                $dateInvoice2 = date_create(date('y-m-d', strtotime($data->deadline_invoice)));
                                $dateInvoiceWarning = date_diff($dateInvoice1, $dateInvoice2);
                                $invoice = $dateInvoiceWarning->format('%a');
                            @endphp
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Invoice</p>
                                </div>
                                <div class="col-sm-8">
                                    <div class="mb-0">

                                        <p class="text-muted">
                                            {{ date('d/m/Y', strtotime($data->deadline_invoice)) }}
                                        </p>
                                        @if ($data->paidOf)
                                            <span class="badge badge-pill badge-success"> Paid </span>
                                        @elseif (strtotime($data->deadline_invoice) < strtotime($currentDate))
                                            <span class="badge badge-pill badge-danger"> Past Due </span>
                                        @else
                                            <span class="badge badge-pill badge-warning">
                                                {{ $invoice == 0 ? 'Today' : $invoice . ' Days' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Created</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ date('d/m/Y', strtotime($data->created_at)) }}
                                    </p>
                                </div>
                            </div>
                            <hr>

                            <form method="POST" action="{{ route('choose-accountnumber') }}"
                                id="choose-accountnumber-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Payment Method</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="deposit_account_id" class="form-control select2">
                                            @foreach ($accountNumbers as $accountNumber)
                                                <option value="{{ $accountNumber->id }}"
                                                    @if ($accountNumber->id == $data->new_deposit_account_id) selected @endif>
                                                    {{ $accountNumber->account_no }} - {{ $accountNumber->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn text-primary" data-toggle="modal"
                                                data-target="#addAccountModal">
                                                + Add Account
                                            </button>
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('deposit_account_id') }}</p>
                                        @endif
                                    </div>
                                    <div class="col mt-1">
                                        <button type="submit" class="btn btn-primary"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Choose</button>
                                    </div>
                                </div>
                            </form>

                            <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog"
                                aria-labelledby="addAccountModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addAccountModalLabel">Add Account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('bills-create-accountnumber') }}" id="addAccountForm"
                                                method="POST">

                                                @csrf
                                                <div class="form-group">
                                                    <label for="account_no">Account Number<span
                                                            style="color: red">*</span>
                                                        :</label>
                                                    <input type="text" class="form-control" id="account_no"
                                                        name="account_no" placeholder="xxx.xxx"
                                                        value="{{ old('account_no') }}" required>
                                                    @error('account_no')
                                                        <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Account Name<span style="color: red">*</span>
                                                        :</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Enter Account Name"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Category<span style="color: red">*</span> :</label>
                                                    <div class="input-group">
                                                        <select name="account_category_id" class="form-control select2"
                                                            id="account_category_id" style="width: 100%">
                                                            @foreach ($accountCategory as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ old('account_category_id') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('account_category_id')
                                                        <p style="color: red;">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Description :</label>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            @if ($data->installment)
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Installment</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="text-muted mb-0">
                                            {{ $data->installment }}x
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        </div>
                    </div>

                    {{-- capital fee --}}
                    @if (sizeof($data->bill_installments) > 0)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa-solid fa-file-invoice mr-1"></i>
                                    Installments
                                </h3>
                                <div class="card-tools">

                                </div>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <!-- Morris chart - Sales -->
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative;">
                                        <div>
                                            <!-- /.card-header -->
                                            <div>
                                                <ul class="todo-list" data-widget="todo-list">

                                                    @php
                                                        $currentDate = date('y-m-d');
                                                    @endphp
                                                    @foreach ($data->bill_installments as $el)
                                                        <li>
                                                            <!-- drag handle -->
                                                            <span class="handle">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </span>
                                                            <!-- checkbox -->
                                                            <div class="icheck-primary d-inline ml-2">
                                                                <span class="text-muted">[
                                                                    {{ date('d F Y', strtotime($el->deadline_invoice)) }}
                                                                    ]</span>
                                                            </div>
                                                            <!-- todo text -->
                                                            <span class="text">( {{ $el->type }} )
                                                                {{ $el->student->name }}</span>
                                                            <!-- Emphasis label -->

                                                            @if ($el->paidOf)
                                                                <small class="badge badge-success"><i
                                                                        class="far fa-checklist"></i> Success</small>
                                                            @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))
                                                                <small class="badge badge-danger"><i
                                                                        class="far fa-clock"></i> Past Due</small>
                                                            @else
                                                                @php
                                                                    $date1 = date_create($currentDate);
                                                                    $date2 = date_create(
                                                                        date('y-m-d', strtotime($el->deadline_invoice)),
                                                                    );
                                                                    $dateWarning = date_diff($date1, $date2);
                                                                    $dateDiff =
                                                                        $dateWarning->format('%a') == 0
                                                                            ? 'Today'
                                                                            : $dateWarning->format('%a') . ' days';
                                                                @endphp
                                                                <small class="badge badge-warning"><i
                                                                        class="far fa-clock"></i>
                                                                    {{ $dateDiff }}</small>
                                                            @endif
                                                            <!-- General tools such as edit or delete-->
                                                            <div class="tools">
                                                                <a href="/admin/bills/detail-payment/{{ $el->id }}"
                                                                    target="_blank">
                                                                    <i class="fas fa-search"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <a target="_blank" href="/admin/bills/installment-pdf/{{ $data->id }}"
                            class="btn btn-dark w-100 mb-2" id='report-pdf'><i class="fa-solid fa-file-pdf fa-bounce"
                                style="color: white; margin-right:2px;"></i>Report PDF</a>
                    @endif

                    {{-- material fee --}}
                    @if ($data->type === 'Material Fee' && $data->material_fee_installment)
                        @php
                            $materialFee = $data->material_fee_installment->material_fee;
                        @endphp

                        @if ($materialFee && $materialFee->installment > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa-solid fa-book mr-1"></i>
                                        Material Fee Installments ({{ $data->student->name }})
                                    </h3>
                                    <div class="card-tools">
                                        <span class="badge badge-info">
                                            Installments: {{ $materialFee->installment }}x
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <div class="chart tab-pane active" id="material-fee-chart"
                                            style="position: relative;">
                                            <div>
                                                <ul class="todo-list" data-widget="todo-list">
                                                    @php
                                                        $currentDate = date('Y-m-d');
                                                        $allInstallments = $materialFee
                                                            ->installment_bills()
                                                            ->with('bill')
                                                            ->orderBy('installment_number')
                                                            ->get();
                                                    @endphp
                                                    @foreach ($allInstallments as $installment)
                                                        <li>
                                                            <span class="handle">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </span>
                                                            <div class="icheck-primary d-inline ml-2">
                                                                <span class="text-muted">[
                                                                    {{ date('d F Y', strtotime($installment->bill->deadline_invoice)) }}
                                                                    ]</span>
                                                            </div>
                                                            <span class="text">
                                                                Rp
                                                                {{ number_format($materialFee->amount_installment, 0, ',', '.') }}
                                                                (Installment {{ $installment->installment_number }} of
                                                                {{ $materialFee->installment }})
                                                                @if ($materialFee->discount > 0)
                                                                    <span class="badge badge-warning">Discount:
                                                                        {{ $materialFee->discount }}%</span>
                                                                @endif
                                                            </span>

                                                            @if ($installment->bill->paidOf)
                                                                <small class="badge badge-success">PAID</small>
                                                            @else
                                                                <small class="badge badge-secondary">NOT YET</small>
                                                            @endif

                                                            <div class="tools">
                                                                <a href="/admin/bills/detail-payment/{{ $installment->bill->id }}"
                                                                    target="_blank">
                                                                    <i class="fas fa-search"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a target="_blank" href="/admin/bills/material-installment-pdf/{{ $materialFee->id }}"
                                class="btn btn-dark w-100 mb-2" id='material-report-pdf'>
                                <i class="fa-solid fa-file-pdf fa-bounce" style="color: white; margin-right:2px;"></i>
                                Material Fee Report PDF
                            </a>
                        @endif
                    @endif

                </div>

                <div class="col-lg-4 p-1">
                    <div class="card mb-4 p-4">
                        <table>
                            <thead>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>
                                @if (sizeof($data->bill_collection) > 0)
                                    @foreach ($data->bill_collection as $el)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                {{ $el->name }} :
                                            </td>
                                            <td align="right" class="p-1">
                                                Rp. {{ number_format($el->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($data->charge > 0)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                Charge:
                                            </td>
                                            <td align="right">
                                                + Rp. {{ number_format($data->charge, 0, ',', '.') }}
                                            </td>

                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td align="left" class="p-1" style="width:50%;">
                                            Amount :
                                        </td>
                                        <td align="right">
                                            Rp.{{ number_format($data->amount - $data->charge, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    @if ($data->dp)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                Done payment :
                                            </td>
                                            <td align="right">
                                                -Rp.{{ number_format($data->dp, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($data->installment)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                Installment :
                                            </td>
                                            <td align="right">
                                                {{ $data->installment }}x
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($data->discount)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                Discount:
                                            </td>
                                            <td align="right">
                                                {{ $data->discount ? $data->discount : 0 }}%
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data->charge > 0)
                                        <tr>
                                            <td align="left" class="p-1" style="width:50%;">
                                                Charge:
                                            </td>
                                            <td align="right">
                                                + Rp. {{ number_format($data->charge, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif

                            </tbody>
                        </table>

                        @if ($data->bill_collection && $data->installment && $data->type === 'Book')
                            <hr>
                            <table>
                                <thead>
                                    <th></th>
                                    <th></th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td align="left" class="p-1 font-weight-bold" style="width:65%;">Total amount :
                                        </td>
                                        <td align="right">Rp. {{ $data->amount }} </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="p-1 font-weight-bold" style="width:65%;">Installment :
                                        </td>
                                        <td align="right"> {{ $data->installment }}x </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif

                        <hr>

                        <table>
                            <thead>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>
                                @php
                                    if ($data->type == 'SPP') {
                                        # code...
                                        $total = $data->discount
                                            ? $data->amount - ($data->amount * $data->discount) / 100
                                            : $data->amount;
                                        $total = $total;
                                    } else {
                                        $total = $data->installment
                                            ? $data->amount_installment
                                            : $data->amount - $data->dp;
                                        $total = $total;
                                    }
                                @endphp
                                <tr>
                                    <td align="left" class="p-1 font-weight-bold" style="width:65%;">
                                        Total :
                                    </td>
                                    <td align="right" class="font-weight-bold">
                                        Rp. {{ number_format($total, 0, ',', '.') }}
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <a target="_blank" href="/admin/bills/paid/pdf/{{ $data->id }}"
                        class="btn btn-warning w-100 mb-2" id="change-paket"><i class="fa-solid fa-file-pdf fa-bounce"
                            style="color: #000000; margin-right:2px;"></i>Print PDF</a>
                    @if (!$data->paidOf)
                        @if (strtolower($data->type) == 'paket' && !$data->installment)
                            <a id="changes-paket"
                                href="/admin/bills/change-paket/{{ $data->student->unique_id }}/{{ $data->id }}"
                                class="btn btn-info w-100 mb-2">Change Paket</a>
                            <a id="paket-installment" href="/admin/bills/intallment-paket/{{ $data->id }}"
                                class="btn btn-secondary w-100 mb-2">Installment Paket</a>
                        @endif
                        @if (strtolower($data->type) == 'book')
                            <a href="javascript:void(0)" id="update-status-book" data-id="{{ $data->id }}"
                                data-name="{{ $data->student->name }}" data-student-id="{{ $data->student->id }}"
                                class="btn btn-success w-100 mb-2">Paid book success</a>
                        @else
                            <a href="javascript:void(0)" id="update-status" data-id="{{ $data->id }}"
                                data-name="{{ $data->student->name }}" data-subject="{{ $data->subject }}"
                                data-bill-type="{{ $data->type }}" class="btn btn-success w-100 mb-2">
                                Paid success
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    @includeIf('components.super.update-paid')

    @if (session('after_create'))
        <script>
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            setTimeout(() => {
                Toast.fire({
                    icon: 'success',
                    title: 'Data has been saved !!!',
                });
            }, 1500);
        </script>
    @endif

    <script>
        $('#choose-accountnumber').on('click', function() {
            var id = $(this).data('id');
            var accountnumber_id = $('select[name="accountnumber_id"]').val();

            $.ajax({
                url: '/admin/bills/choose-accountnumber',
                method: 'POST',
                data: {
                    id: id,
                    accountnumber_id: accountnumber_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Status updated successfully.');
                        location.reload(); // Optional: reload to reflect changes
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            @endif

            @if ($errors->any())
                let errorMessages = "";
                @foreach ($errors->all() as $error)
                    errorMessages += "{{ $error }}<br>";
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessages,
                    timer: 5000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
