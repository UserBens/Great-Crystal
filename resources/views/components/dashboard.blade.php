@extends('layouts.admin.master')
@section('content')
    @php
        $user = session('role');
        $cardStudent = 'col-lg-3 col-6';
        $cardTeacher = 'col-lg-3 col-6';
        $cardBill = 'col-lg-3 col-6';
        $cardPastDue = 'col-lg-3 col-6';
        $listStudent = '';
        $listTeacher = '';
        $listBill = '';
        $listPastDue = '';

        if ($user == 'admin') {
            $cardStudent = 'col';
            $cardTeacher = 'col';
            $cardBill = 'd-none';
            $cardPastDue = 'd-none';
            $listStudent = '';
            $listTeacher = '';
            $listBill = 'd-none';
            $listPastDue = 'd-none';
        } elseif ($user == 'accounting') {
            $cardStudent = 'd-none';
            $cardTeacher = 'col';
            $cardBill = 'col';
            $cardPastDue = 'col';
            $listStudent = 'd-none';
            $listTeacher = '';
            $listBill = '';
            $listPastDue = '';
        }
    @endphp

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @if ($user == 'accounting')
                    <div class="{{ $cardTeacher }}">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data->transactionReceive }}</h3>
                                <p>Total Transaction Receive</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <a href="{{ route('transaction-receive.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="{{ $cardBill }}">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $data->transactionSend }}</h3>
                                <p>Total Transaction Send</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-money-bill-trend-up"></i>
                            </div>
                            <a href="{{ route('transaction-send.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="{{ $cardPastDue }}">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data->transactionTransfer }}</h3>
                                <p>Total Transaction Transfer</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <a href="{{ route('transaction-transfer.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="row">
        <section class="col-lg-7 connectedSortable">
            @if ($user == 'accounting')
                <div class="{{ $listBill }} card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-hourglass-end mr-1"></i>
                            Deadline Invoice Supplier
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0" style="position: relative; height: 400px;">
                            @if ($data->invoiceSuppliers->isEmpty())
                                <div class="d-flex justify-content-center">
                                    <h2>Data Invoice Supplier does not exist !!!</h2>
                                </div>
                            @else
                                <div style="overflow-y: auto; max-height: 360px;">
                                    <ul class="todo-list" data-widget="todo-list">
                                        @foreach ($data->invoiceSuppliers as $invoiceSupplier)
                                            @php
                                                $currentDate = \Carbon\Carbon::now();
                                                $deadline = \Carbon\Carbon::parse($invoiceSupplier->deadline_invoice);
                                                $daysLeft = $currentDate->diffInDays($deadline, false);
                                                $isDueSoon = $daysLeft <= 3 && $daysLeft >= 0;
                                                $isPaid = $invoiceSupplier->payment_status === 'Paid';
                                                $daysOverdue = $daysLeft < 0;
                                            @endphp
                                            <li>
                                                <span class="handle">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                                <div class="icheck-primary d-inline ml-2">
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        [{{ date('d F Y', strtotime($invoiceSupplier->deadline_invoice)) }}]
                                                    </span>
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        Deadline Invoice -
                                                    </span>
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        {{ $invoiceSupplier->supplier_name }}
                                                        ({{ $invoiceSupplier->no_invoice }})
                                                    </span>
                                                    @if (!$isPaid)
                                                        @if ($daysOverdue)
                                                            <span class="text-danger">Overdue by {{ abs($daysLeft) }}
                                                                days</span>
                                                        @elseif ($isDueSoon)
                                                            @if ($daysLeft === 1)
                                                                <span class="text-danger">1 day left</span>
                                                            @else
                                                                <span class="text-danger">{{ $daysLeft }} days
                                                                    left</span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                    @if ($isPaid)
                                                        <span class="text-success">(Paid)</span>
                                                    @endif
                                                    <a href="{{ route('supplier.index') }}" class="small-box-footer"
                                                        style="color: orange">
                                                        <i class="fas fa-arrow-circle-right"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- 
        <section class="col-lg-7 connectedSortable">
            @if ($user == 'accounting')
                <div class="{{ $listBill }} card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-hourglass-end mr-1"></i>
                            Deadline Invoice Supplier
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            @if ($data->invoiceSuppliers->isEmpty())
                                <div class="d-flex justify-content-center">
                                    <h2>Data Invoice Supplier does not exist !!!</h2>
                                </div>
                            @else
                                <div style="overflow-y: auto; max-height: 300px;">
                                    <ul class="todo-list" data-widget="todo-list">
                                        @foreach ($data->invoiceSuppliers as $invoiceSupplier)
                                            @php
                                                $currentDate = \Carbon\Carbon::now();
                                                $deadline = \Carbon\Carbon::parse($invoiceSupplier->deadline_invoice);
                                                $daysLeft = $currentDate->diffInDays($deadline, false);
                                                $isDueSoon = $daysLeft <= 3 && $daysLeft >= 0;
                                                $isPaid = $invoiceSupplier->payment_status === 'Paid';
                                                $daysOverdue = $currentDate->diffInDays($deadline, false);
                                                $overdueClass = $daysOverdue > 0 ? 'text-danger' : '';
                                            @endphp
                                            <li>
                                                <span class="handle">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </span>
                                                <div class="icheck-primary d-inline ml-2">
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        [{{ date('d F Y', strtotime($invoiceSupplier->deadline_invoice)) }}]
                                                    </span>
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        Deadline Invoice -
                                                    </span>
                                                    <span class="{{ $isPaid ? 'text-black' : 'text-danger' }}">
                                                        {{ $invoiceSupplier->supplier_name }}
                                                        ({{ $invoiceSupplier->no_invoice }})
                                                    </span>
                                                    @if ($isDueSoon)
                                                        @if ($daysLeft == 1)
                                                            <span class="text-danger">1 day left</span>
                                                        @else
                                                            <span class="text-danger">{{ $daysLeft }} days left</span>
                                                        @endif
                                                    @elseif ($daysOverdue > 0)
                                                        <span class="text-danger">Overdue by {{ $daysOverdue }}
                                                            days</span>
                                                    @endif
                                                    @if ($isPaid)
                                                        <span class="text-success">(Paid)</span>
                                                    @endif
                                                    <a href="{{ route('supplier.index') }}" class="small-box-footer"
                                                        style="color: orange">
                                                        <i class="fas fa-arrow-circle-right"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </section> --}}










        <section class="col-lg-5 connectedSortable">
            @if ($user == 'accounting')
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            Income Chart
                        </div>
                        <div class="card-body">
                            <div id="income_chart"></div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div class="row">
        @if ($user == 'accounting')
            <section class="col-lg-6 connectedSortable">
                <div class="card mb-3\">
                    <div class="card-header">
                    Pie Chart
                </div>
                <div class="card-body">
                    <div id="pie_chart"></div>
                </div>
    </div>
    </section>

    <section class="col-lg-6 connectedSortable">
        <div class="card mb-3">
            <div class="card-header">
                Basic Bar
            </div>
            <div class="card-body">
                <div id="basic_bar"></div>
            </div>
        </div>
    </section>

    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                Column Chart
            </div>
            <div class="card-body">
                <div id="column_chart"></div>
            </div>
        </div>
    </div>
    @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Highcharts.chart('income_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Monthly Income vs Expenses'
                },
                xAxis: {
                    categories: @json(array_values($data->incomeData['categories'])),
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Amount'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Income',
                    data: @json(array_values($data->incomeData['data']))
                }, {
                    name: 'Expenses',
                    data: @json(array_values($data->expenseData['data']))
                }]
            });

            Highcharts.chart('pie_chart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Transaction Types Distribution'
                },
                series: [{
                    name: 'Transactions',
                    colorByPoint: true,
                    data: @json($data->pie)
                }]
            });

            Highcharts.chart('basic_bar', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Corn vs wheat estimated production for 2020',
                    align: 'left'
                },
                subtitle: {
                    text: 'Source: <a target="_blank" href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>',
                    align: 'left'
                },
                xAxis: {
                    categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                    crosshair: true,
                    accessibility: {
                        description: 'Countries'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '1000 metric tons (MT)'
                    }
                },
                tooltip: {
                    valueSuffix: ' (1000 MT)'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Corn',
                    data: [406292, 260000, 107000, 68300, 27500, 14500]
                }, {
                    name: 'Wheat',
                    data: [51086, 136000, 5500, 141000, 107180, 77000]
                }, {
                    name: 'Potato',
                    data: [53086, 146000, 52300, 14000, 101180, 745000]
                }]
            });

            Highcharts.chart('column_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Yearly Transaction Amounts'
                },
                xAxis: {
                    categories: @json($data->column['categories']),
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Amount'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: @json(array_values($data->column['series']))
            });
        });
    </script>
@endsection
