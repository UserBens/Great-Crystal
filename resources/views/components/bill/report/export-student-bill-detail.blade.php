<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student Bill Detail</title>
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .student-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .summary {
            margin-top: 20px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }
    </style> --}}

    {{-- <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        p {
            margin: 2px;
            font-size: 12px;
        }

        .header,
        .header1,
        .header2 {
            width: 100%;
            height: 5%;
        }

        .header2 {
            position: relative;
        }

        .invoice {
            color: rgb(95, 95, 95);
            position: absolute;
            top: 0;
        }

        .logo {
            color: rgb(255, 115, 0);
            margin-bottom: 20px;
        }

        .student-info {
            margin: 20px 0;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .detail {
            width: 100%;
            padding: .5em;
        }

        .header_table {
            background-color: rgb(255, 115, 0);
            color: white;
        }

        .body_table {
            background-color: rgba(95, 95, 95, 0.243);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .address {
            font-size: 12px;
            color: grey;
            margin: 30px 0;
        }

        .paid {
            color: rgb(2, 134, 2);
        }

        .unpaid {
            color: rgb(145, 0, 0);
        }
    </style> --}}

    {{-- opsi menarik --}}
    {{-- <style>
        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .logo-section {
            text-align: right;
        }

        .logo {
            color: #ff7300;
            margin: 0;
        }

        .logo h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .logo h3 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: 500;
            color: #666;
        }

        .invoice {
            color: #555;
            font-size: 24px;
            margin: 0;
        }

        .student-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }

        .student-info p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }

        .student-info strong {
            color: #333;
        }

        .bill-details {
            margin-top: 30px;
        }

        .detail {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        .header_table {
            background-color: #ff7300;
            color: white;
        }

        .header_table th {
            padding: 15px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
        }

        .body_table {
            background-color: white;
            transition: background-color 0.2s;
        }

        .body_table:hover {
            background-color: #f8f9fa;
        }

        .body_table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .amount {
            font-weight: 600;
            color: #2c3e50;
        }

        .paid {
            color: #00a854;
            background-color: #f6ffed;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .unpaid {
            color: #f5222d;
            background-color: #fff1f0;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .address {
            font-size: 13px;
            color: #666;
            margin: 30px 0;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .summary {
            text-align: right;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
        }

        .highlight {
            background-color: #fff7e6;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }
    </style> --}}


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            padding: 2rem;
            color: #2d3436;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f1f1;
        }

        .invoice-title {
            color: #636e72;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .logo {
            text-align: center;
            color: #ff7300;
        }

        .logo h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .logo h3 {
            font-size: 1rem;
            font-weight: 500;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .bill-to,
        .bill-summary {
            flex: 1;
        }

        .bill-summary {
            text-align: right;
        }

        .info-item {
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .info-label {
            color: #636e72;
            margin-right: 0.5rem;
        }

        .address {
            text-align: center;
            color: #636e72;
            font-size: 0.9rem;
            margin: 2rem 0;
            padding: 1rem;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .detail-table th {
            background-color: #ff7300;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }

        .detail-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f1f1;
        }

        .detail-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-paid {
            background-color: #e3fcef;
            color: #00b894;
        }

        .status-unpaid {
            background-color: #ffe3e3;
            color: #ff4757;
        }

        .summary {
            text-align: right;
            color: #636e72;
            font-size: 0.8rem;
            margin-top: 2rem;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .container {
                box-shadow: none;
                padding: 1rem;
            }
        }
    </style>

</head>

{{-- <body>
    <div class="header">
        <h2>Student Bill Detail Report</h2>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td width="50%">
                    <strong>Name:</strong> {{ $student->name }}<br>
                    <strong>Grade:</strong> {{ $student->grade->name }}<br>
                    <strong>Class:</strong> {{ $student->grade->class }}
                </td>
                <td width="50%">
                    <strong>Total Bills:</strong> Rp {{ number_format($summary->total, 0, ',', '.') }}<br>
                    <strong>Paid Bills:</strong> Rp {{ number_format($summary->paid, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 15%">Bill Type</th>
                <th style="width: 20%">Amount</th>
                <th style="width: 20%">Due Date</th>
                <th style="width: 20%">Paid Date</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $index => $bill)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bill->type }}</td>
                    <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>

                    <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                    <td>
                        @if ($bill->paidOf)
                            {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                        @else
                            Belum terbayar
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

    <div class="summary">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('j F Y H:i:s') }}</p>
    </div>
</body> --}}

{{-- <body>
    <div class="header">
        <table>
            <tr>
                <td align="left" class="header1">
                    <h2 class="invoice">Student Bill Detail Report</h2>
                </td>
                <td align="center" class="header2">
                    <div class="logo">
                        <h1 style="margin: 0;">GREAT CRYSTAL</h1>
                        <h3 style="margin: 0;">SCHOOL AND COURSE CENTER</h3>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td width="50%">
                    <p><strong>BILL TO:</strong></p>
                    <p>Name: {{ $student->name }}</p>
                    <p>Grade: {{ $student->grade->name }}</p>
                    <p>Class: {{ $student->grade->class }}</p>
                </td>
                <td width="50%" align="right">
                    <p>Invoice no: <strong>{{ $summary->invoice_number ?? '-' }}</strong></p>
                    <p>Date issue: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong></p>
                    <p>Total Bills: <strong>Rp {{ number_format($summary->total, 0, ',', '.') }}</strong></p>
                    <p>Paid Bills: <strong>Rp {{ number_format($summary->paid, 0, ',', '.') }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <p class="address"><b>Great Crystal School</b> {{ date('l, d F Y') }}, Jl. Raya Darmo Permai III, Surabaya,
        Indonesia</p>

    <table class="detail">
        <thead class="header_table">
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 15%">Bill Type</th>
                <th style="width: 20%">Amount</th>
                <th style="width: 20%">Due Date</th>
                <th style="width: 20%">Paid Date</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $index => $bill)
                <tr class="body_table">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bill->type }}</td>
                    <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                    <td>
                        @if ($bill->paidOf)
                            {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                        @else
                            Belum terbayar
                        @endif
                    </td>
                    <td>
                        @if ($bill->paidOf)
                            <span class="paid"><b>paid</b></span>
                        @else
                            <span class="unpaid"><b>unpaid</b></span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('j F Y H:i:s') }}</p>
    </div>
</body> --}}

{{-- opsi menarik --}}
{{-- <body>
    <div class="container">
        <div class="header">
            <h2 class="invoice">Student Bill Detail Report</h2>
            <div class="logo">
                <h1>GREAT CRYSTAL</h1>
                <h3>SCHOOL AND COURSE CENTER</h3>
            </div>
        </div>

        <div class="student-info">
            <div class="bill-to">
                <p><strong>BILL TO:</strong></p>
                <p>Name: <strong>{{ $student->name }}</strong></p>
                <p>Grade: <strong>{{ $student->grade->name }}</strong></p>
                <p>Class: <strong>{{ $student->grade->class }}</strong></p>
            </div>
            <div class="bill-summary">
                <p>Invoice No: <span class="highlight">{{ $summary->invoice_number ?? '-' }}</span></p>
                <p>Date Issue: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong></p>
                <p>Total Bills: <strong>Rp {{ number_format($summary->total, 0, ',', '.') }}</strong></p>
                <p>Paid Bills: <strong>Rp {{ number_format($summary->paid, 0, ',', '.') }}</strong></p>
            </div>
        </div>

        <div class="bill-details">
            <table class="detail">
                <thead class="header_table">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 15%">Bill Type</th>
                        <th style="width: 20%">Amount</th>
                        <th style="width: 20%">Due Date</th>
                        <th style="width: 20%">Paid Date</th>
                        <th style="width: 15%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $index => $bill)
                        <tr class="body_table">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bill->type }}</td>
                            <td class="amount">Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                            <td>
                                @if ($bill->paidOf)
                                    {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                                @else
                                    <span style="color: #999;">Belum terbayar</span>
                                @endif
                            </td>
                            <td>
                                @if ($bill->paidOf)
                                    <span class="paid">PAID</span>
                                @else
                                    <span class="unpaid">UNPAID</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="address"><b>Great Crystal School</b> {{ date('l, d F Y') }}<br>Jl. Raya Darmo Permai III, Surabaya,
            Indonesia</p>

        <div class="summary">
            <p>Generated on: {{ \Carbon\Carbon::now()->format('j F Y H:i:s') }}</p>
        </div>
    </div>
</body> --}}

<body>
    <div class="container">
        <div class="header">
            <h2 class="invoice-title">Student Bill Detail Report</h2>
            <div class="logo">
                <h1>GREAT CRYSTAL</h1>
                <h3>SCHOOL AND COURSE CENTER</h3>
            </div>
        </div>

        <div class="student-info">
            <div class="bill-to">
                <h3>BILL TO:</h3>
                <p class="info-item"><span class="info-label">Name:</span>{{ $student->name }}</p>
                <p class="info-item"><span class="info-label">Grade:</span>{{ $student->grade->name }}</p>
                <p class="info-item"><span class="info-label">Class:</span>{{ $student->grade->class }}</p>
            </div>
            <div class="bill-summary">
                <p class="info-item"><span class="info-label">Date
                        issue:</span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <p class="info-item"><span class="info-label">Total Bills:</span>Rp
                    {{ number_format($summary->total, 0, ',', '.') }}</p>
                <p class="info-item"><span class="info-label">Paid Bills:</span>Rp
                    {{ number_format($summary->paid, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="address">
            <strong>Great Crystal School</strong><br>
            {{ date('l, d F Y') }}<br>
            Jl. Raya Darmo Permai III, Surabaya, Indonesia
        </div>

        <table class="detail-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 15%">Bill Type</th>
                    <th style="width: 20%">Amount</th>
                    <th style="width: 20%">Due Date</th>
                    <th style="width: 20%">Paid Date</th>
                    <th style="width: 15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bills as $index => $bill)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bill->type }}</td>
                        <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                        <td>
                            @if ($bill->paidOf)
                                {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                            @else
                                Belum terbayar
                            @endif
                        </td>
                        <td>
                            @if ($bill->paidOf)
                                <span class="status-badge status-paid">PAID</span>
                            @else
                                <span class="status-badge status-unpaid">UNPAID</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <p>Generated on: {{ \Carbon\Carbon::now()->format('j F Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
