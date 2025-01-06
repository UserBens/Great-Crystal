<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .header {
            width: 100%;
            height: 5%;
            margin-bottom: 20px;
        }
        .logo {
            color: rgb(255, 115, 0);
            text-align: center;
        }
        .student-details {
            font-size: 14px;
        }
        .table-detail {
            width: 100%;
            padding: 10px;
        }
        .table-detail th, .table-detail td {
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: grey;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>Great Crystal School</h1>
            <h3>SCHOOL AND COURSE CENTER</h3>
        </div>
    </div>

    <div class="student-details">
        <h3>Tagihan Material Fee untuk {{ $mailData['student']->name }}</h3>
        <p>Installment: {{ $mailData['bill'][0]->installment }}x</p>
        <p>Grade: {{ $mailData['student']->grade->name }} - {{ $mailData['student']->grade->class }}</p>
        <p>Date Issued: {{ date('d/m/Y', strtotime($mailData['bill'][0]->created_at)) }}</p>
        <p>Due Date: {{ date('d/m/Y', strtotime($mailData['bill'][0]->deadline_invoice)) }}</p>
    </div>

    <table class="table-detail">
        <thead>
            <tr>
                <th>Item</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Material Fee</td>
                <td>{{ number_format($mailData['bill'][0]->amount, 2) }}</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>

    <div class="footer">
        <p>Great Crystal School - {{ date('l, d F Y') }}</p>
        <p>Jl. Raya Darmo Permai III, Surabaya, Indonesia</p>
    </div>
</body>
</html>
