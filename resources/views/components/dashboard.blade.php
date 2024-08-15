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

            @if ($user != 'accounting')
                <div class="row">

                    <div class="{{ $cardStudent }}">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data->student }}</h3>

                                <p>Total Students Active</p>
                            </div>
                            <div class="icon">
                                {{-- <i class="ion ion-bag"></i> --}}
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <a href="/admin/list" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="{{ $cardTeacher }}">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data->teacher }}
                                    {{-- <sup style="font-size: 20px">%</sup> --}}
                                </h3>

                                <p>Total Teachers Active</p>
                            </div>
                            <div class="icon">
                                {{-- <i class="ion ion-stats-bars"></i> --}}
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                            @if (session('role') !== 'accounting')
                                <a href="/admin/teachers" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            @else
                                <div class="small-box-footer" style="padding: 0.93rem"></div>
                            @endif
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="{{ $cardBill }}">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $data->bill }}</h3>

                                <p>Bills last 30 days</p>
                            </div>
                            <div class="icon">
                                {{-- <i class="ion ion-person-add"></i> --}}
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            @if (session('role') !== 'admin')
                                <a href="/admin/bills" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            @else
                                <div class="small-box-footer" style="padding:0.93rem;"></div>
                            @endif
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="{{ $cardPastDue }}">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $data->pastDue }}</h3>

                                <p>Bills Past Due</p>
                            </div>
                            <div class="icon">
                                {{-- <i class="ion ion-pie-graph"></i> --}}
                                <i class="fa-solid fa-calendar-xmark"></i>
                            </div>
                            @if (session('role') != 'admin')
                                <a href="/admin/bills?grade=all&invoice=pastdue&type=all&status=false&search="
                                    class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            @else
                                <div class="small-box-footer" style="padding:0.93rem;"></div>
                            @endif
                        </div>
                    </div>
                    <!-- ./col -->

                </div>

                <div class="row">
                    <!-- Left col -->
                    @if ($user == 'superadmin')
                        <section class="col-lg-7 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="{{ $listBill }} card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa-solid fa-hourglass-end mr-1"></i>
                                        Past due bills
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="revenue-chart"
                                            style="position: relative; height: 310px;">

                                            @if (sizeof($data->dataPastDue) == 0)
                                                <div class="d-flex justify-content-center">

                                                    <h2>Data past due does not exist !!!</h2>

                                                </div>
                                            @else
                                                {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                                                <div>
                                                    <!-- /.card-header -->
                                                    <div>
                                                        <ul class="todo-list" data-widget="todo-list">

                                                            @php
                                                                $currentDate = date('y-m-d');
                                                            @endphp

                                                            @foreach ($data->dataPastDue as $el)
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
                                                                                class="far fa-checklist"></i>
                                                                            Success</small>
                                                                    @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))
                                                                        <small class="badge badge-danger"><i
                                                                                class="far fa-clock"></i> Past Due</small>
                                                                    @else
                                                                        @php
                                                                            $date1 = date_create($currentDate);
                                                                            $date2 = date_create(
                                                                                date(
                                                                                    'y-m-d',
                                                                                    strtotime($el->deadline_invoice),
                                                                                ),
                                                                            );
                                                                            $dateWarning = date_diff($date1, $date2);
                                                                            $dateDiff = $dateWarning->format('%a days');
                                                                        @endphp
                                                                        <small class="badge badge-warning"><i
                                                                                class="far fa-clock"></i>
                                                                            {{ $dateDiff }}</small>
                                                                    @endif
                                                                    <!-- General tools such as edit or delete-->
                                                                    @if (session('role') !== 'admin')
                                                                        <div class="tools">
                                                                            <a href="/admin/bills/detail-payment/{{ $el->id }}"
                                                                                target="_blank">
                                                                                <i class="fas fa-search"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif


                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- Map card -->
                            <div class="{{ $listStudent }} card bg-gradient-info">
                                <div class="card-header border-0">
                                    <h3 class="card-title">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        Student's
                                    </h3>
                                    <!-- card tools -->
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-info btn-sm" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Place Birth</th>
                                                <th scope="col">Register Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data->dataStudent as $el)
                                                <tr>
                                                    <td scope="row">{{ $loop->index + 1 }}</td>
                                                    <td>{{ $el->name }}</td>
                                                    <td>{{ $el->place_birth }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($el->created_at)) }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-none row">
                                        <div class="col-4 text-center">
                                            <div id="sparkline-1"></div>
                                            <div class="text-white">Visitors</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <div id="sparkline-2"></div>
                                            <div class="text-white">Online</div>
                                        </div>
                                        <!-- ./col -->
                                        <div class="col-4 text-center">
                                            <div id="sparkline-3"></div>
                                            <div class="text-white">Sales</div>
                                        </div>
                                        <!-- ./col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                            </div>
                            <!-- /.card -->


                        </section>




                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-5 connectedSortable">

                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="{{ $listBill }} card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        New bills
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div class="chart tab-pane active" id="revenue-chart"
                                            style="position: relative; height: 310px;">


                                            @if (sizeof($data->dataBill) == 0)
                                                <div class="d-flex justify-content-center">

                                                    <h2>Data bill does not exist !!!</h2>

                                                </div>
                                            @else
                                                {{-- <h1>New Bills</h1> --}}
                                                <div>
                                                    <!-- /.card-header -->
                                                    <div>
                                                        <ul class="todo-list" data-widget="todo-list">

                                                            @php
                                                                $currentDate = date('y-m-d');
                                                            @endphp

                                                            @foreach ($data->dataBill as $el)
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
                                                                                class="far fa-checklist"></i>
                                                                            Success</small>
                                                                    @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))
                                                                        <small class="badge badge-danger"><i
                                                                                class="far fa-clock"></i> Past Due</small>
                                                                    @else
                                                                        @php
                                                                            $date1 = date_create($currentDate);
                                                                            $date2 = date_create(
                                                                                date(
                                                                                    'y-m-d',
                                                                                    strtotime($el->deadline_invoice),
                                                                                ),
                                                                            );
                                                                            $dateWarning = date_diff($date1, $date2);
                                                                            $dateDiff =
                                                                                $dateWarning->format('%a') == 0
                                                                                    ? 'Today'
                                                                                    : $dateWarning->format('%a') .
                                                                                        ' days';
                                                                        @endphp
                                                                        <small class="badge badge-warning"><i
                                                                                class="far fa-clock"></i>
                                                                            {{ $dateDiff }}</small>
                                                                    @endif
                                                                    <!-- General tools such as edit or delete-->
                                                                    @if (session('role') !== 'admin')
                                                                        <div class="tools">
                                                                            <a href="/admin/bills/detail-payment/{{ $el->id }}"
                                                                                target="_blank">
                                                                                <i class="fas fa-search"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- Teacher List -->
                            <div class="{{ $listTeacher }} card bg-gradient-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="ion ion-clipboard mr-1"></i>
                                        Teacher's
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Place Birth</th>
                                                <th scope="col">Register Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data->dataTeacher as $el)
                                                <tr>
                                                    <td scope="row">{{ $loop->index + 1 }}</td>
                                                    <td>{{ $el->name }}</td>
                                                    <td>{{ $el->place_birth }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($el->created_at)) }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- right col -->
                    @endif
                </div>
            @endif


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
                <div class="card mb-3">
                    <div class="card-header">
                        Area Chart
                    </div>
                    <div class="card-body">
                        <div id="area_chart"></div>
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
                    },
                    labels: {
                        formatter: function() {
                            return 'Rp. ' + Highcharts.numberFormat(this.value, 0, ',', '.');
                        }
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
                        var s = '<span style="font-size:10px">' + this.x + '</span><table>';
                        this.points.forEach(function(point) {
                            s += '<tr><td style="color:' + point.series.color + ';padding:0">' +
                                point.series.name + ': </td>' +
                                '<td style="padding:0"><b>Rp. ' + Highcharts.numberFormat(point
                                    .y, 0, ',', '.') + '</b></td></tr>';
                        });
                        s += '</table>';
                        return s;
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
                yAxis: [{
                    min: 0,
                    title: {
                        text: 'Total Invoices'
                    },

                }, {
                    min: 0,
                    title: {
                        text: 'Total Amount'
                    },
                    opposite: true,
                    labels: {
                        formatter: function() {
                            return 'Rp. ' + Highcharts.numberFormat(this.value, 0, ',', '.');
                        }
                    }
                }],
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Invoices',
                    data: @json($data->invoiceSuppliersChart->pluck('count')),
                    tooltip: {
                        valueSuffix: ' invoices'
                    },
                    yAxis: 0
                }, {
                    name: 'Amount',
                    data: @json($data->invoiceSuppliersChart->pluck('amount')),
                    tooltip: {
                        pointFormatter: function() {
                            return `<span style="color:${this.color}">\u25CF</span> ${this.series.name}: <b>Rp. ${Highcharts.numberFormat(this.y, 0, ',', '.')}</b><br/>`;
                        }
                    },
                    yAxis: 1
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
                            // Format angka dengan titik sebagai pemisah ribuan
                            return 'Rp. ' + this.value.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            });
                        }
                    }
                },
                tooltip: {
                    pointFormatter: function() {
                        // Format tooltip dengan jumlah sesuai format yang diinginkan
                        return '<span style="color:' + this.color + '">\u25CF</span> ' + this.series
                            .name + ': <b>Rp. ' + this.y.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            }) + '</b><br/>';
                    }
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
