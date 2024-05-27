<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Journal</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
        }
        .header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .header .title {
            font-size: 24px;
            font-weight: bold;
            color: rgb(255, 115, 0);

        }
        .header .subtitle {
            font-size: 21px;
            margin-top: 30px;
            text-align: left;
        }
        .header .texttype {
            font-size: 18px;
            margin-top: 30px;
            text-align: left; /* Ubah posisi teks menjadi di sebelah kiri */

        }
        .address {
            text-align: center;
            margin-bottom: 30px;
            color: grey;
        }
        .transaction-details {
            width: 100%;
            margin-bottom: 20px;
            
        }
        .transaction-details th, .transaction-details td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .transaction-details th {
            /* background-color: #f2f2f2; */
            text-align: left;
            
        }
        .transaction-details .total {
            font-weight: bold;
        }
        .header-table {
            background-color: rgb(255, 115, 0); /* Warna latar belakang header tabel */
            color: white; /* Warna teks */

        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">GREAT CRYSTAL SCHOOL AND COURSE CENTER</div>
        {{-- <div class="subtitle mt-3">Detail Journal</div> --}}
        <div class="texttype">Transaction Type : {{ $type }}</div>
    </div>
   
    <table class="transaction-details">
        <thead class="transaction-details header-table">
            <tr>
                <th>No Transaction</th>
                <th>Account Number</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Date</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactionDetails as $detail)
            <tr>
                <td>{{ $transaction->no_transaction }}</td>
                <td>{{ $detail['account_number'] }} - {{ $detail['account_name'] }}</td>
                <td>{{ $detail['debit'] > 0 ? 'Rp '.number_format($detail['debit'], 0, ',', '.') : '0' }}</td>
                <td>{{ $detail['credit'] > 0 ? 'Rp '.number_format($detail['credit'], 0, ',', '.') : '0' }}</td>
                <td>{{ date('d M Y', strtotime($detail['date'])) }}</td>
                <td>{{ $detail['description'] }}</td>
                <td>{{ date('d M Y', strtotime($detail['created_at'])) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total</td>
                <td>Rp {{ number_format(array_sum(array_column($transactionDetails, 'debit')), 0, ',', '.') }}</td>
                <td>Rp {{ number_format(array_sum(array_column($transactionDetails, 'credit')), 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>

    <div class="address">
        Great Crystal School, {{ date('l, d F Y') }}, Jl. Raya Darmo Permai III, Surabaya, Indonesia
    </div>

</body>
</html>
