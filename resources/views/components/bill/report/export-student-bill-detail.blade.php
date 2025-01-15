<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student Bill Detail</title>
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

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-image {
            width: 80px;
            height: auto;
        }

        .logo-text {
            text-align: left;
        }

        .logo-text h1 {
            color: #ff7300;
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
        }

        .logo-text h3 {
            color: #636e72;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .report-info {
            text-align: right;
            font-size: 0.9rem;
            color: #636e72;
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
            font-weight: 600;
            margin-right: 0.5rem;
        }

        /* Mempertahankan style table yang lama */
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
            background-color: #ff7300;
            /* Warna orange sesuai logo */
            color: white;
            /* Mengubah warna text menjadi putih agar lebih mudah dibaca */
            padding: 8px;
            text-align: left;
            font-weight: 500;
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

        /* End of table styles */

        .footer {
            margin-top: 3rem;
            text-align: center;
            color: #636e72;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 2px solid #f1f1f1;
        }

        .address {
            margin: 1rem 0;
        }

        .contact-info {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .signature-section {
            margin-top: 3rem;
            text-align: right;
            padding-right: 2rem;
        }

        .signature-line {
            margin-top: 4rem;
            border-top: 1px solid #636e72;
            width: 200px;
            display: inline-block;
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

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <div class="logo-text">
                    <h1>GREAT CRYSTAL</h1>
                    <h3>SCHOOL AND COURSE CENTER</h3>
                </div>
            </div>
            <div class="report-info">
                <p>Date: {{ date('d F Y') }}</p>
            </div>
        </div>

        <div class="student-info">
            <div class="bill-to">
                <h3>Student Information:</h3>
                <p class="info-item"><span class="info-label">Name:</span>{{ $student->name }}</p>
                <p class="info-item"><span class="info-label">Grade:</span>{{ $student->grade->name }}</p>
                <p class="info-item"><span class="info-label">Class:</span>{{ $student->grade->class }}</p>
                <p class="info-item"><span class="info-label">Academic Year:</span>2023/2024</p>
            </div>
            <div class="bill-summary">
                <h3>Bill Summary:</h3>
                <p class="info-item"><span class="info-label">Total Bills:</span>Rp
                    {{ number_format($summary->total, 0, ',', '.') }}</p>
                <p class="info-item"><span class="info-label">Paid Bills:</span>Rp
                    {{ number_format($summary->paid, 0, ',', '.') }}</p>
                <p class="info-item"><span class="info-label">Remaining:</span>Rp
                    {{ number_format($summary->total - $summary->paid, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Bagian tabel tetap sama seperti sebelumnya -->
        <table>
            <thead>
                <tr>
                    <th style="width: 15%">Invoice Number</th>
                    <th style="width: 10%">Bill Type</th>
                    <th style="width: 15%">Amount</th>
                    <th style="width: 20%">Due Date</th>
                    <th style="width: 15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bills as $index => $bill)
                    <tr>
                        <td>{{ $bill->number_invoice }}</td>
                        <td>{{ $bill->type }}</td>
                        <td>
                            @if ($bill->type === 'Capital Fee')
                                Rp {{ number_format($bill->amount_installment, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($bill->amount, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
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


        <div class="footer">
            <div class="address">
                <strong>GREAT CRYSTAL SCHOOL</strong><br>
                Jl. Raya Darmo Permai III, Surabaya, Indonesia
            </div>
            {{-- <div class="contact-info">
                <p>Phone: (031) 7317991 | Email: info@greatcrystal.sch.id</p>
                <p>Website: www.greatcrystal.sch.id</p>
            </div> --}}
        </div>
    </div>
</body>

</html>
