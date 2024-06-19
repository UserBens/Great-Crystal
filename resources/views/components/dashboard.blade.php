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
            $cardBill = 'd-none col';
            $cardPastDue = 'd-none col';
            $listStudent = '';
            $listTeacher = '';
            $listBill = 'd-none';
            $listPastDue = 'd-none';
        } elseif ($user == 'accounting') {
            $cardStudent = 'd-none col';
            $cardTeacher = 'd-none col';
            $cardBill = 'col';
            $cardPastDue = 'col';
            $listStudent = 'd-none';
            $listTeacher = 'd-none';
            $listBill = '';
            $listPastDue = '';
        }
    @endphp

    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                @if ($user == 'HR')
                    <div class="{{ $cardStudent }}">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data->transactionTransfer }}</h3>
                                <p>Total Transaction Transfer</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <a href="{{ route('transaction-transfer.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                <div class="{{ $cardTeacher }}">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $data->transactionReceive }}
                                {{-- <sup style="font-size: 20px">%</sup> --}}
                            </h3>

                            <p>Total Transaction Receive</p>
                        </div>
                        <div class="icon">
                            {{-- <i class="ion ion-stats-bars"></i> --}}
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <a href="{{ route('transaction-receive.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
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
                            {{-- <i class="ion ion-person-add"></i> --}}
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </div>
                        <a href="{{ route('transaction-send.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
    </section>
    <div class="row">
        <section class="col-lg-7 connectedSortable">
            @if ($user == 'HR')
                <div class="{{ $listBill }} card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-hourglass-end mr-1"></i>
                            Deadline Invoice Supplier
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="revenue-chart"
                                        style="position: relative; height: 360px;">
                                        @if ($data->invoiceSuppliers->isEmpty())
                                            <div class="d-flex justify-content-center">
                                                <h2>Data Invoice Supplier does not exist !!!</h2>
                                            </div>
                                        @else
                                            <div>
                                                <div style="overflow-y: auto; max-height: 300px;">
                                                    <ul class="todo-list" data-widget="todo-list">
                                                        @php
                                                            $currentDate = \Carbon\Carbon::now();
                                                        @endphp
                                                        @foreach ($data->invoiceSuppliers as $invoiceSupplier)
                                                            @php
                                                                $deadline = \Carbon\Carbon::parse(
                                                                    $invoiceSupplier->deadline_invoice,
                                                                );
                                                                $daysLeft = $currentDate->diffInDays($deadline, false);
                                                                $isDueSoon = $daysLeft <= 3 && $deadline > $currentDate;
                                                            @endphp
                                                            <li>
                                                                <span class="handle">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </span>
                                                                <div class="icheck-primary d-inline ml-2">
                                                                    <span
                                                                        class="{{ $isDueSoon ? 'text-danger' : 'text-muted' }}">
                                                                        [{{ date('d F Y', strtotime($invoiceSupplier->deadline_invoice)) }}]
                                                                    </span>
                                                                    <span
                                                                        class="{{ $isDueSoon ? 'text-danger' : 'text-muted' }}">
                                                                        Deadline Invoice -
                                                                    </span>
                                                                    <span
                                                                        class="{{ $isDueSoon ? 'text-danger' : 'text-muted' }}">
                                                                        {{ $invoiceSupplier->supplier_name }}
                                                                        ({{ $invoiceSupplier->no_invoice }})
                                                                    </span>
                                                                    @if ($isDueSoon)
                                                                        @if ($daysLeft == 1)
                                                                            <span class="text-danger">1 days left</span>
                                                                        @else
                                                                            <span class="text-danger">{{ $daysLeft }}
                                                                                days left</span>
                                                                        @endif
                                                                    @endif
                                                                    <a href="{{ route('supplier.index') }}"
                                                                        class="small-box-footer" style="color: orange"><i class="fas fa-arrow-circle-right"></i></a>
                                                                </div>
                                                            </li>
                                                        @endforeach



                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <section class="col-lg-5 connectedSortable">
            @if ($user == 'HR')
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
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    Pie Chart
                </div>
                <div class="card-body">
                    <div id="pie_chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    Line Chart
                </div>
                <div class="card-body">
                    <div id="line_chart"></div>
                </div>
            </div>
        </div>

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
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            Highcharts.chart('income_chart', {
                title: {
                    text: 'Monthly Income'
                },
                yAxis: {
                    title: {
                        text: 'Total Income'
                    }
                },
                xAxis: {
                    categories: {!! json_encode($data->income['categories']) !!}
                },
                series: [{
                    name: 'Income',
                    data: {!! json_encode($data->income['data']) !!}
                }],
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true, // Aktifkan marker
                            symbol: 'circle', // Ubah simbol marker
                            // radius: 4 // Ubah ukuran marker
                        }
                    }
                }
            });

            Highcharts.chart('line_chart', {
                title: {
                    text: 'Monthly Transaction Counts'
                },
                yAxis: {
                    title: {
                        text: 'Number of Transactions'
                    }
                },
                xAxis: {
                    categories: @json($data->line['categories'])
                },
                series: [{
                    name: 'Transactions',
                    data: @json($data->line['data'])
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

            Highcharts.chart('income_chart', {
                title: {
                    text: 'Monthly Income'
                },
                yAxis: {
                    title: {
                        text: 'Total Income'
                    }
                },
                xAxis: {
                    categories: @json($data->income['categories'])
                },
                series: [{
                    name: 'Transactions',
                    data: @json($data->income['data'])
                }]
            });


        });
    </script>

@endsection
