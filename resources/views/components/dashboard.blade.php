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
            $cardInvoiceSupplier = 'col';
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
                                <p>Total Receive Transaction</p>
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
                                <p>Total Send Transaction</p>
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
                                <p>Total Transfer Transaction</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <a href="{{ route('transaction-transfer.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="{{ $cardInvoiceSupplier }}">
                        <!-- small box -->
                        <div class="small-box bg-orange">
                            <div class="inner">
                                <h3>{{ $data->invoiceData }}</h3>
                                <p>Total Invoice Supplier</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <a href="{{ route('invoice-supplier.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="row">
        <section class="col-lg-12 connectedSortable">
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
                                                        {{ $invoiceSupplier->supplier->name }}
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

        <section class="col-lg-12 connectedSortable">
            @if ($user == 'accounting')
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            Area Chart
                        </div>
                        <div class="card-body">
                            <div id="area_chart"></div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div class="row">
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
        @endif
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Highcharts.chart('income_chart', {
            //     chart: {
            //         type: 'column'
            //     },
            //     title: {
            //         text: 'Monthly Income vs Expenses'
            //     },
            //     xAxis: {
            //         categories: @json(array_values($data->incomeData['categories'])),
            //         crosshair: true
            //     },
            //     yAxis: {
            //         title: {
            //             text: 'Amount'
            //         }
            //     },
            //     tooltip: {
            //         headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            //         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            //             '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
            //         footerFormat: '</table>',
            //         shared: true,
            //         useHTML: true
            //     },
            //     plotOptions: {
            //         column: {
            //             pointPadding: 0.2,
            //             borderWidth: 0
            //         }
            //     },
            //     series: [{
            //         name: 'Income',
            //         data: @json(array_values($data->incomeData['data']))
            //     }, {
            //         name: 'Expenses',
            //         data: @json(array_values($data->expenseData['data']))
            //     }]
            // });

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
                        '<td style="padding:0"><b>Rp. {point.y:,.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        var point = this.points[0];
                        var formattedAmount = Highcharts.numberFormat(point.y, 0, ',', '.');
                        return '<span style="font-size:10px">' + point.key + '</span><table>' +
                            '<tr><td style="color:' + point.series.color + ';padding:0">' + point.series
                            .name + ': </td>' +
                            '<td style="padding:0"><b>Rp. ' + formattedAmount + '</b></td></tr>' +
                            '</table>';
                    }
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
                    text: 'Transaction Distribution by Count and Amount'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b><br>Total Amount: <b>{point.amount}</b>'
                },
                series: [{
                    name: 'Transactions Count',
                    colorByPoint: true,
                    data: @json($data->pieData)
                }]
            });

            Highcharts.chart('basic_bar', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Invoice Supplier Payment Status',
                    align: 'left'
                },
                xAxis: {
                    categories: @json($data->invoiceSuppliersChart->pluck('name')),
                    crosshair: true,
                    accessibility: {
                        description: 'Payment Status'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Invoices'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Invoices',
                    data: @json($data->invoiceSuppliersChart->pluck('y'))
                }]
            });

            Highcharts.chart('area_chart', {
                chart: {
                    type: 'area'
                },
                title: {
                    text: 'Monthly Transaction and Invoice Distribution'
                },
                xAxis: {
                    categories: @json($data->months),
                    title: {
                        text: 'Month'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Amount'
                    },
                    labels: {
                        formatter: function() {
                            return Highcharts.numberFormat(this.value, 0, ',', '.');
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:,.0f}</b><br/>Amount'
                },
                plotOptions: {
                    area: {
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [{
                        name: 'Transaction Send',
                        data: @json($data->transactionSendData),
                        color: '#28a745'
                    },
                    {
                        name: 'Transaction Receive',
                        data: @json($data->transactionReceiveData),
                        color: '#007bff'
                    },
                    {
                        name: 'Transaction Transfer',
                        data: @json($data->transactionTransferData),
                        color: '#ff5733'
                    },
                    {
                        name: 'Invoice Supplier',
                        data: @json($data->invoiceSupplierData),
                        color: '#ffc107'
                    },
                    {
                        name: 'Bills',
                        data: @json($data->billsData),
                        color: '#6c757d'
                    }
                ]
            });

        });
    </script>
@endsection
