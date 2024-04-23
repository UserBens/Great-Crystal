<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Print Pdf - {{ $data->name }} {{ $data->class }}</title>
    
    <style>



        body{
            font-family: 'Roboto', sans-serif;
        }

        p {
            margin: 2px;
            font-size: 12px;
        }
        table,
        thead,
        td,
        tr, {
            width: 100%;
            height: 5%;
        }
        

        td  {
            padding: 7px 5px;
            text-align: left;
        }
        .header,
        .header1, 
        .header2 {
            width: 100%;
            height: 5%;
            /* border: 1px solid black; */
        }
        
        .header2{
            position: relative;
            
        }
        
        .invoice {
            color: rgb(65, 57, 57)6);
            margin: 0px 5px 0px 5px;
        }
        
        .logo {
            color: rgb(255, 115, 0);
            top: absolute;
            bottom: 0;
        }

        .main_text {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
        }
        
        .child_text {
            font-size: 20px;
            font-weight: 
        }
        

        .address {
            font-size: 12px;
            color: grey;
            margin-bottom: 20px;
            margin-top: 60px;
        }

        .student {
            font-size: 11px;
            padding: 0;
            margin: 30px,4px;
        }
        
        .head_student {
            font-size: 12px;
            margin: 0;
        }

        .date {
            width: 100%;
            bottom: 0;
        }

        .date_container {
            vertical-align: bottom;
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

        .table_detail {
            margin-top: 20px;
        }
        .subtotal {
          width: 50%;
        }
        .total {
         width: 50%;
         font-style: 'bold';
         background-color: rgba(95, 95, 95, 0.012);
        }

        .paid {
         color: rgb(2, 134, 2);
        }
        .unpaid {
         color: rgb(145, 0, 0);
        }
        
    </style>
</head>

<body>
    <div class="container">
        <table>
            {{-- <thead>
                <td style="text-align: center;">
                    <div class="logo">
                        <h1 style="margin-bottom:0;">GREAT CRYSTAL</h1>
                        <h2 style="margin-top:0;">SCHOOL AND COURSE CENTER</h2>
                    </div>
                </td>
            </thead> --}}
            <tbody>
                <td style="text-align: center;" align="center">
                    <h2 class="invoice">DATA STUDENTS</h2>
                    <h2 class="invoice">GREAT CRYSTAL SCHOOL</h2>
                    <h2 class="invoice">{{strtoupper($data->name)}} {{$data->class}}</h2>
                </td>
            </tbody>
        </table>

        <p class="address">Print date: {{date('d/m/Y')}}</p>
        <table>
            <thead class="header_table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Unique ID</th>
                    <th>Gender</th>
                    <th>Date Birth</th>
                    {{-- <th>Age</th> --}}
                    <th>Nationality</th>
                </tr>
            </thead>
            <tbody class="body_table">
                @foreach ($data->student as $el)

                <tr>
                    <td style="width: 30%;" align="center">{{$loop->index + 1}}</td>
                    <td>{{$el->name}}</td>
                    <td>{{$el->unique_id}}</td>
                    <td>{{$el->gender}}</td>
                    @php
                    $explode = explode('-', $el->date_birth);
                    $date_birth = $explode[2].'-'.$explode[1].'-'.$explode[0];
                    $currentDate = date('Y-m-d');
                    $dateDiff = date_diff(date_create($el->date_birth), date_create($currentDate));
                    $age = $dateDiff->format('%y');

                    @endphp
                    <td>{{$date_birth}}</td>
                    {{-- <td>{{$age}}</td> --}}
                    <td>{{$el->nationality}}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script> --}}
</body>

</html>
