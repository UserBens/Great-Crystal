@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <h2 class="text-center display-4">Bills Search</h2>
    <form class="my-5" action="/admin/bills">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="row mb-1">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Grade:</label>
                            @php

                                $selectedGrade = $form && $form->grade ? $form->grade : null;

                            @endphp
                            <select name="grade" class="form-control" required>
                                <option class="text-center" {{ !$selectedGrade ? 'selected' : '' }} value="all">-- All
                                    Grades --</option>
                                @foreach ($grade as $el)
                                    <option class="text-center" {{ $selectedGrade == $el->id ? 'selected' : '' }}
                                        value="{{ $el->id }}">{{ $el->name . ' - ' . $el->class }}</option>
                                @endforeach
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">

                            @php

                                $selectedInvoice = $form && $form->invoice ? $form->invoice : 'all';

                            @endphp

                            <label>Invoice : <span style="color: red"></span></label>
                            <select name="invoice" class="form-control text-center">
                                <option {{ $selectedInvoice === 'all' ? 'selected' : '' }} value="all">-- All Invoice --
                                </option>
                                <option {{ $selectedInvoice === '30' ? 'selected' : '' }} value="30">30 Days</option>
                                <option {{ $selectedInvoice === '7' ? 'selected' : '' }} value="7">7 Days</option>
                                <option {{ $selectedInvoice === 'tommorow' ? 'selected' : '' }} value="tommorow">Tomorrow
                                </option>
                                <option {{ $selectedInvoice === 'today' ? 'selected' : '' }} value="today">Today</option>
                                <option {{ $selectedInvoice === 'pastdue' ? 'selected' : '' }} value="pastdue">Past Due
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">

                            @php

                                $selectedType = $form && $form->type ? $form->type : 'all';

                            @endphp

                            <label>Type : </label>
                            <select name="type" class="form-control text-center">
                                <option class="bg-primary" {{ $selectedType === 'all' ? 'selected' : '' }} value="all">
                                    --
                                    All Type --</option>
                                <option {{ $selectedType === 'Capital Fee' ? 'selected' : '' }} value="Capital Fee">Capital
                                    Fee</option>
                                <option {{ $selectedType === 'Uniform' ? 'selected' : '' }} value="Uniform">Uniform
                                </option>
                                <option {{ $selectedType === 'Paket' ? 'selected' : '' }} value="Paket">Paket</option>
                                <option {{ $selectedType === 'Book' ? 'selected' : '' }} value="Book">Book</option>
                                <option {{ $selectedType === 'SPP' ? 'selected' : '' }} value="SPP">SPP</option>
                                <option {{ $selectedType === 'Others' ? 'selected' : '' }} value="Others">Others</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>Paid: <span style="color: red"></span></label>

                            @php

                                $selectedStatus = $form->status != 'all' && $form->status ? $form->status : 'all';

                            @endphp

                            <select name="status" class="form-control text-center">
                                <option {{ $selectedStatus == 'all' ? 'selected' : '' }} value="all">-- All Status --
                                </option>
                                <option {{ $selectedStatus == 'true' ? 'selected' : '' }} value="true">Paid</option>
                                <option {{ $selectedStatus == 'false' ? 'selected' : '' }} value="false">Not yet</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        @php
                            $selectedFrom = $form->from_bill ? $form->from_bill : '';
                        @endphp
                        <div class="form-group">
                            <label>From :</label>

                            <div class="input-group date" id="reservationBillFrom" data-target-input="nearest">
                                <input name="from_bill" type="text" class="form-control"
                                    data-target="#reservationBillFrom" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="dd/mm/yyyy" data-mask value="{{ $selectedFrom }}"
                                    placeholder="DD/MM/YYYY" />
                                <div class="input-group-append" data-target="#reservationBillFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>

                                @if ($errors->any())
                                    <p style="color: red">{{ $errors->first('from_bill') }}</p>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        @php
                            $selectedTo = $form->to_bill ? $form->to_bill : '';
                        @endphp
                        <div class="form-group">
                            <label>To :</label>
                            <div class="input-group date" id="reservationBillTo" data-target-input="nearest">
                                <input name="to_bill" type="text" class="form-control" data-target="#reservationBillTo"
                                    data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                    value="{{ $selectedTo }}" placeholder="DD/MM/YYYY" />
                                <div class="input-group-append" data-target="#reservationBillTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>

                                @if ($errors->any())
                                    <p style="color: red">{{ $errors->first('to_bill') }}</p>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input name="search" value="{{ $form->search }}" type="search"
                            class="form-control form-control-lg" placeholder="Type students name or invoice number here">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @if (sizeof($data) <= 0 &&
            ($form->search ||
                $form->type ||
                $form->invoice ||
                $form->grade ||
                $form->status ||
                $form->from_bill ||
                $form->to_bill))
        <div class="container-fluid mt-5">
            <div class="row h-100">
                <div class="col-sm-12 my-auto text-center">
                    <h3>The bills you are looking for does not exist !!!</h3>
                    {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Create bill for student
                </a> --}}
                </div>
            </div>
        @elseif (sizeof($data) <= 0)
            <div class="container-fluid mt-5">
                <div class="row h-100">
                    <div class="col-sm-12 my-auto text-center">
                        <h3>The bills never been created !!!</h3>
                        {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Create bill for student
                </a> --}}
                    </div>
                </div>
            @else
                {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
    <i class="fa-solid fa-plus"></i>
    Create bill for student
</a> --}}

                <div class="card mt-5 card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Bills</h3>

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
                                    <th style="width: 1%">
                                        No
                                    </th>
                                    <th style="width: 12%">
                                        Number Invoice
                                    </th>
                                    <th class="text-center" style="width: 5%">
                                        Type
                                    </th>
                                    <th style="width: 20%">
                                        Student
                                    </th>
                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Grade
                                    </th>
                                    <th class="text-center">
                                        Class
                                    </th>
                                    <th class="text-center">
                                        Paid of
                                    </th>
                                    <th style="width: 18%" class="text-center">
                                        Invoice
                                    </th>
                                    <th style="width: 30%">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $el)
                                    <tr id={{ 'index_student_' . $el->id }}>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>
                                        <td>
                                            {{ $el->number_invoice }}
                                        </td>
                                        <td class="text-center">
                                            <a>
                                                {{ $el->type }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $el->student->name }}
                                        </td>
                                        @php
                                            if ($el->type == 'SPP') {
                                                $amount = $el->discount
                                                    ? $el->amount - ($el->amount * $el->discount) / 100
                                                    : $el->amount;
                                            } else {
                                                $amount = $el->installment ? $el->amount_installment : $el->amount;
                                            }
                                        @endphp
                                        <td>
                                            IDR.
                                            {{ number_format($amount, 0, ',', '.') }}
                                            @if ($el->discount)
                                                <br><small class="text-muted">discount: {{ $el->discount }}%</small>
                                            @elseif ($el->installment)
                                                <br><small class="text-muted">installment: {{ $el->installment }}x</small>
                                            @endif
                                            @if ($el->charge > 0)
                                                <br><small class="text-danger">charge: IDR.
                                                    {{ number_format($el->charge, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $el->student->grade->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $el->student->grade->class }}
                                        </td>
                                        <td class="project-state text-center">
                                            @if ($el->paidOf)
                                                <h1 class="badge badge-success">done</h1>
                                            @else
                                                <h1 class="badge badge-danger">not yet</h1>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ date('d M Y', strtotime($el->deadline_invoice)) }}
                                        </td>

                                        <td class="project-actions toastsDefaultSuccess text-center">
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-sm btn-primary mr-2"
                                                    href="/admin/bills/detail-payment/{{ $el->id }}">
                                                    <i class="fas fa-folder"></i> View
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning email-btn"
                                                    data-id="{{ $el->id }}">
                                                    <i class="fas fa-envelope mr-1"></i>Email
                                                </button>
                                                {{-- Tampilkan button Pay Charge hanya jika ada charge DAN belum dibayar --}}
                                                @if ($el->charge > 0 && !$el->paidOf)
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger charge-payment-btn ml-2"
                                                        data-id="{{ $el->id }}" data-char
                                                        ge="{{ $el->charge }}">
                                                        <i class="fas fa-credit-card mr-1"></i>Pay Charge
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

                @if ($data->lastPage() > 1)
                    <div class="d-flex justify-content-end my-5">

                        <nav aria-label="...">
                            <ul class="pagination" max-size="2">

                                @php
                                    $link =
                                        '/admin/bills?grade=' .
                                        $selectedGrade .
                                        '&invoice=' .
                                        $selectedInvoice .
                                        '&type=' .
                                        $selectedType .
                                        '&status=' .
                                        $selectedStatus .
                                        '&search=' .
                                        $form->search .
                                        '&from_bill=' .
                                        $selectedFrom .
                                        '&to_bill=' .
                                        $selectedTo;
                                    $previousLink = $link . '&page=' . $data->currentPage() - 1;
                                    $nextLink = $link . '&page=' . $data->currentPage() + 1;
                                    $firstLink = $link . '&page=1';
                                    $lastLink = $link . '&page=' . $data->lastPage();

                                    $arrPagination = [];
                                    $flag = false;

                                    if ($data->lastPage() - 5 > 0) {
                                        if ($data->currentPage() <= 4) {
                                            for ($i = 1; $i <= 5; $i++) {
                                                # code...
                                                $temp = (object) [
                                                    'page' => $i,
                                                    'link' => $link . '&page=' . $i,
                                                ];

                                                array_push($arrPagination, $temp);
                                            }
                                        } elseif ($data->lastPage() - $data->currentPage() > 2) {
                                            $flag = true;
                                            $idx = [
                                                $data->currentPage() - 2,
                                                $data->currentPage() - 1,
                                                $data->currentPage(),
                                                $data->currentPage() + 1,
                                                $data->currentPage() + 2,
                                            ];

                                            foreach ($idx as $value) {
                                                $temp = (object) [
                                                    'page' => $value,
                                                    'link' => $link . '&page=' . $value,
                                                ];

                                                array_push($arrPagination, $temp);
                                            }
                                        } else {
                                            $arrFirst = [];
                                            //ini buat yang current page sampai last page

                                            for ($i = $data->currentPage(); $i <= $data->lastPage(); $i++) {
                                                $temp = (object) [
                                                    'page' => $i,
                                                    'link' => $link . '&page=' . $i,
                                                ];

                                                array_push($arrFirst, $temp);
                                            }

                                            $arrLast = [];
                                            $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                                            //ini yang buat current page but decrement

                                            for ($i = $diff; $i < $data->currentPage(); $i++) {
                                                $temp = (object) [
                                                    'page' => $i,
                                                    'link' => $link . '&page=' . $i,
                                                ];

                                                array_push($arrLast, $temp);
                                            }

                                            $arrPagination = array_merge($arrLast, $arrFirst);
                                        }
                                    } else {
                                        for ($i = 1; $i <= $data->lastPage(); $i++) {
                                            $temp = (object) [
                                                'page' => $i,
                                                'link' => $link . '&page=' . $i,
                                            ];

                                            array_push($arrPagination, $temp);
                                        }
                                    }

                                @endphp

                                <li class="mr-1 page-item {{ $data->previousPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $firstLink }}" tabindex="+1">
                                        << First </a>
                                </li>

                                <li class="page-item {{ $data->previousPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $previousLink }}" tabindex="-1">
                                        Previous
                                    </a>
                                </li>

                                @foreach ($arrPagination as $el)
                                    <li class="page-item {{ $el->page === $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $el->link }}">
                                            {{ $el->page }}
                                        </a>
                                    </li>
                                @endforeach

                                <li class="page-item {{ $data->nextPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $nextLink }}" tabindex="+1">
                                        Next
                                    </a>
                                </li>

                                <li class="ml-1 page-item {{ $data->nextPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $lastLink }}" tabindex="+1">
                                        Last >>
                                    </a>
                                </li>

                            </ul>

                        </nav>

                    </div>
                @endif
                @include('components.super.delete-student')

    @endif
    </div>





    @if (session('create_installment_bill'))
        <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
                    title: 'Successfully create installment bills for student paket',
                });
            }, 1500);
        </script>
    @endif


    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

    @if (session('change_type_paket'))
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
                    title: 'Successfully change bills for student paket',
                });
            }, 1500);
        </script>
    @endif

    @if (!$flag_date)
        <script>
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            setTimeout(() => {
                Toast.fire({
                    icon: 'warning',
                    title: "Warning !!!, The from date cannot be more than to date.",
                });
            }, 1500);
        </script>
    @endif

    @if (session('after_add_book'))
        @php
            $var = session('after_add_book');
        @endphp

        <script>
            var name = "{{ $var }}"

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            setTimeout(() => {
                Toast.fire({
                    icon: 'success',
                    title: 'Success create bill books for ' + name,
                });
            }, 1500);
        </script>
    @endif

    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/projects.js') }}" defer></script>

    {{-- MODAL KIRIM ULANG EMAIL  --}}
    <script>
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            @endif

            $('.email-btn').click(function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to send the payment success notification email?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mengirim request untuk mengirim email menggunakan Ajax
                        $.ajax({
                            url: '{{ route('admin.bills.sendPaymentNotification', ['bill_id' => ':id']) }}'
                                .replace(':id', id),
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Sent!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Refresh halaman setelah mengirim email
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Failed!',
                                    response.responseJSON.error ? response
                                    .responseJSON.error :
                                    'There was an error sending the email.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>


    {{-- MODAL CHARGE --}}
    <script>
        $(document).ready(function() {
            // Handle charge payment button click
            $('.charge-payment-btn').on('click', function() {
                var billId = $(this).data('id');
                var charge = $(this).data('charge');

                // Show confirmation dialog
                Swal.fire({
                    title: 'Pay Charge?',
                    text: 'Are you sure you want to pay the charge of IDR. ' + charge
                        .toLocaleString() + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, pay it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Process payment
                        $.ajax({
                            url: '{{ route('pay-charge', ':billId') }}'
                                .replace(':billId', billId),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                bill_id: billId
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Processing...',
                                    text: 'Please wait while we process your payment',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Charge payment processed successfully and notification sent to accounting.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        // Refresh the page or update the row
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message ||
                                            'Failed to process payment',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while processing payment',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
