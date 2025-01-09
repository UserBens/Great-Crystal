<!DOCTYPE html>
<html>

<head>
    <title>{{ $subject }}</title>
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #f9f0e6;
            font-family: 'Roboto', sans-serif;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        p {
            display: block;
            margin: 2px;
            font-size: 12px;
        }

        .header {
            width: 100%;
            padding: 20px 0;
            background-color: transparent;
        }

        .logo {
            text-align: center;
            padding: 20px 0;
        }

        .logo h1 {
            color: rgb(255, 115, 0);
            margin: 0;
            font-size: 32px;
            font-weight: bold;
            line-height: 1.2;
        }

        .logo h3 {
            color: rgb(255, 115, 0);
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: normal;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: transparent;
        }
    </style>
</head>

<body>
    <div
        style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
        Notification - Great Crystal School And Center
    </div>

    <div style="background-color:#f9f0e6;">
        <!-- Logo Section -->
        <div class="container">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                    <tr>
                        <td class="header">
                            <div class="logo">
                                <h1>GREAT CRYSTAL</h1>
                                <h3>SCHOOL AND COURSE CENTER</h3>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Main Content -->
        <div style="background:#fbfbfb;background-color:#fbfbfb;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                style="background:#fbfbfb;background-color:#fbfbfb;width:100%;">
                <tbody>
                    <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                    style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0px;text-align:center;">
                                                <div class="mj-column-per-100 mj-outlook-group-fix"
                                                    style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation" style="vertical-align:top;" width="100%">
                                                        <tbody>
                                                            @if ($mailData['past_due'])
                                                                <tr>
                                                                    <td style="width:100%;" align="center">
                                                                        <img height="auto"
                                                                            src="https://iili.io/JBn6n2V.jpg"
                                                                            style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" />
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            <tr>
                                                                <td align="left"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:20px;font-weight:400;line-height:30px;text-align:left;color:#333333;">
                                                                        <h1
                                                                            style="margin:0;font-size:24px;line-height:normal;font-weight:700;text-transform:none;">
                                                                            Pemberitahuan Tagihan
                                                                            {{ ucwords(strtolower($mailData['bill'][0]->type ?? 'Unknown')) }}
                                                                            -
                                                                            @if (isset($mailData['student']) && isset($mailData['student']->material_fee->type))
                                                                                {{ ucwords(strtolower($mailData['student']->material_fee->type === 'paket' ? 'Package' : $mailData['student']->material_fee->type)) }}
                                                                            @else
                                                                                {{ ucwords(strtolower('General')) }}
                                                                            @endif
                                                                        </h1>
                                                                    </div>
                                                                </td>

                                                            </tr>

                                                            <tr>
                                                                <td
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <p
                                                                        style="border-top:solid 1px #F4F5FB;font-size:1px;margin:0px auto;width:100%;">
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td align="left"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:16px;font-weight:400;line-height:20px;text-align:left;color:#333333;">
                                                                        <p>Dear Great Parents,</p>
                                                                    </div>
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:16px;font-weight:400;line-height:20px;text-align:left;color:#333333;">
                                                                        <p style="margin:0;">
                                                                            {{ 'Kami informasikan tagihan Material Fee untuk ' . $mailData['student']->name . ' adalah sebagai berikut:' }}
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Invoice Details -->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                    style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td
                                                style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;">
                                                <div class="mj-column-per-100 mj-outlook-group-fix"
                                                    style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td style="vertical-align:top;padding:10px 25px;">
                                                                    <table border="0" cellpadding="0"
                                                                        cellspacing="0" role="presentation"
                                                                        style="background-color:#f6f6f6;margin-top:15px;margin-bottom:15px;"
                                                                        width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="left" class="receipt-table"
                                                                                    style="font-size:0px;padding:20px;word-break:break-word;">
                                                                                    <table cellpadding="0"
                                                                                        cellspacing="0" width="100%"
                                                                                        border="0"
                                                                                        style="color:#333333;font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                                                                        <tr>
                                                                                            <th colspan="2"
                                                                                                align="left"
                                                                                                style="padding-bottom:10px;color:#7e7e7e;font-size:12px;line-height:16px;font-weight:700;text-transform:uppercase;">
                                                                                                Invoice
                                                                                                #{{ $mailData['bill'][0]->number_invoice }}
                                                                                            </th>
                                                                                            <th align="right"
                                                                                                style="padding-bottom:10px;color:#7e7e7e;font-size:12px;line-height:16px;font-weight:700;text-transform:uppercase;">
                                                                                                Biaya
                                                                                            </th>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="2"
                                                                                                style="color:#525f7f;font-size:15px;line-height:24px;word-break:normal;">
                                                                                                Material Fee (Cicilan
                                                                                                {{ $mailData['installment_info']['current'] }}/{{ $mailData['installment_info']['total'] }})
                                                                                            </td>
                                                                                            <td align="right"
                                                                                                style="color:#525f7f;font-size:15px;line-height:24px;word-break:normal;">
                                                                                                Rp.
                                                                                                {{ number_format($mailData['bill'][0]->amount, 0, ',', '.') }}
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3"
                                                                                                style="border-bottom:1px solid #EAEEEB;padding:5px 0;">
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="2"
                                                                                                style="color:#525f7f;font-size:15px;line-height:24px;word-break:normal;font-weight:bold;padding:20px 0 0;">
                                                                                                Total Pembayaran
                                                                                            </td>
                                                                                            <td align="right"
                                                                                                style="color:#525f7f;font-size:15px;line-height:24px;word-break:normal;font-weight:bold;padding:20px 0 0;">
                                                                                                Rp.
                                                                                                {{ number_format($mailData['bill'][0]->amount, 0, ',', '.') }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Payment Information -->
                            <div style="margin-top:20px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0"
                                    role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
                                                <div class="mj-column-per-100 mj-outlook-group-fix"
                                                    style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation" style="vertical-align:top;"
                                                        width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:17px;font-weight:400;line-height:20px;text-align:center;color:{{ $mailData['past_due'] ? 'red' : 'rgb(73, 73, 73)' }};">
                                                                        <b>{{ $mailData['past_due'] ? 'Invoice pembayaran sudah melewati jatuh tempo' : 'Invoice deadline pembayaran' }}</b>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:17px;font-weight:400;line-height:20px;text-align:center;color:rgb(73, 73, 73);">
                                                                        <b>{{ date('d/m/Y', strtotime($mailData['bill'][0]->deadline_invoice)) }}</b>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:15px;font-weight:400;line-height:20px;text-align:center;color:#616161;">
                                                                        <b>Pembayaran setelah tanggal 10 setiap bulannya
                                                                            akan dikenakan denda sebesar Rp.100.000</b>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:15px;font-weight:400;line-height:20px;text-align:center;color:#616161;">
                                                                        Pembayaran bisa dilakukan melalui transfer ke
                                                                        Rekening <b>BCA <span
                                                                                style="color:#f08922;">5190878998</span></b>
                                                                    </div>
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:15px;font-weight:400;line-height:20px;text-align:center;color:#616161;">
                                                                        an <b><span style="color:#f08922;">YP Sumber
                                                                                Daya Sukses Makmur</span></b>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:15px;font-weight:400;line-height:20px;text-align:center;color:#616161;">
                                                                        Mohon mengirimkan bukti pembayaran dengan click
                                                                        link dibawah ini.
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <a href="#"
                                                                        style="background-color:#ca6800;color:white;padding:10px 20px;text-decoration:none;border-radius:10px;font-family:Roboto,Arial,sans-serif;font-size:15px;display:inline-block;">
                                                                        Kirim Bukti Transfer
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"
                                                                    style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                    <div
                                                                        style="font-family:Roboto,Mulish,Muli,Arial,sans-serif;font-size:15px;font-weight:400;line-height:20px;text-align:center;color:#616161;">
                                                                        Terima Kasih.
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                style="width:100%;">
                <tbody>
                    <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
                            <div
                                style="font-family:Roboto,Arial,sans-serif;font-size:14px;color:#616161;text-align:center;">
                                © {{ date('Y') }} [Great Crystal], JL. RAYA DARMO PERMAI III, PUNCAK PERMAI SQUARE
                                SURABAYA, INDONESIA
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
