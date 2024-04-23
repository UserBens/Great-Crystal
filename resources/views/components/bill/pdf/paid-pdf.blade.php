<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{$data->id}}</title>
    <style>



        body{
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
            /* border: 1px solid black; */
        }
        
        .header2{
            position: relative;
            
        }
        
        .invoice {
            color: rgb(95, 95, 95);
            position: absolute;
            top: 0;
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
            margin-bottom: 70px;
            margin-top: 30px;
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

        <table class="header">

            <thead>
                <th style="width: 50%;">
                </th>
                <th style="width: 20%;">
                </th>
            </thead>
            <tbody>
                <td align="left" class="header1">
                    <h2 class="invoice">Invoice</h2>
                </td>
                <td align="center" class="header2">
                <div >
                </div>
                </td>
            </tbody>

        </table>

        <table class="header">

            <thead>
                <th style="width: 50%;">
                </th>
                <th style="width: 50%;">
                </th>
            </thead>
            <tbody>
                <td align="left" class="header1">
                </td>
                <td align="center" class="header2">
                <div class="logo">
                    <h1 style="margin: 0;">GREAT CRYSTAL</h1>
                    <h3  style="margin: 0;">SCHOOL AND COURSE CENTER</h3>
                </div>
                </td>
            </tbody>

        </table>

        
        

        <table style="width: 100%;">
            <thead>
                <th >
                </th>
                <th style="width: 30%;">
                </th>
            </thead>
            <td>
                <div class="student">
                    <p class="head_student"><strong>BILL TO :</strong></p> <br>
                    <p>{{$data->student->name}}</p>
                    <p>{{$data->student->grade->name}} {{$data->student->grade->class}}</p>
                    <p>{{$data->student->place_birth}}</p>
                    <p>{{$data->student->nationality}}</p>
                </div>
        
            </td>
            <td class="date_container">
                <table class="date">
                    <thead>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody class="date_detail">
                        <tr >
                            <td align="right" style="padding: 0">
    
                                <p>Invoice no :</p>
                                
                            </td>
                            <td align="right" style="padding: 0">
    
                                <p><b>{{$data->number_invoice}}</b></p>
    
                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="padding: 0">
    
                                <p>Date issue :</p>
                                
                            </td>
                            <td align="right" style="padding: 0">
    
                                <p><b>{{date('d/m/Y', strtotime($data->created_at))}}</b></p>
    
                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="padding: 0">
    
                                <p>Due date :</p>
                                
                            </td>
                            <td align="right" style="padding: 0">
    
                                <p><b>{{date('d/m/Y', strtotime($data->deadline_invoice))}}</b></p>
    
                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="padding: 0">
    
                                <p>Status :</p>
                                
                            </td>
                            <td align="right" style="padding: 0">
    
                              @if ($data->paidOf)
                              
                              <p class="paid"><b>paid</b></p>
                              @else
                              <p class="unpaid"><b>unpaid</b></p>

                              @endif

    
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </table>

        <p class="address"><b>Great Crystal School</b> {{date('l, d F Y')}}, Jl. Raya Darmo Permai III, Surabaya, Indonesia</p>


        
        <table class="detail table_detail">
            <thead class="detail header_table">
                <th class="detail" align="left">{{$data->type == 'Book' ? $data->subject : 'Description'}}</th>
                <th class="detail" align="left">Price</th>
            </thead>
            
            <tbody >
                @if ($data->type == 'Book')
                
                @foreach ($data->bill_collection as $el)
                
                    <tr class="detail body_table">

                        <td class="detail"><strong>{{$el->name}}</strong></td>
                        <td class="detail">Rp. {{number_format($el->amount, 0, ',', '.')}}</td>
                    </tr>

                @endforeach
                
                @elseif ($data->installment)


                  <tr class="detail body_table">

                      <td class="detail"><strong>{{$data->type}} installment ({{$data->subject}})</strong></td>
                      <td class="detail">Rp. {{number_format($data->amount_installment - $data->charge, 0, ',', '.')}}</td>
                  </tr>

                @else

                
                    <tr class="detail body_table">

                        <td class="detail"><strong>{{$data->type}}</strong></td>
                        <td class="detail">Rp. {{number_format($data->amount - $data->charge, 0, ',', '.')}}</td>
                    </tr>

               @endif
               <tr>

                  <td ></td>
                  <td>
                     <table style="width:100%; margin-top: 60px;">
                        <thead>
                            @if ($data->installment)
                              <tr>  
                                 <td align="right" class="subtotal">Subtotal :</td>
                                 <td align="right" class="subtotal">Rp. {{number_format($data->amount_installment - $data->charge, 0, ',', '.')}}</td>
                              </tr>
                            @else
                              <tr>
                                 <td align="right" class="subtotal">Subtotal :</td>
                                 <td align="right" class="subtotal">Rp. {{number_format($data->amount - $data->charge, 0, ',', '.')}}</td>
                              </tr>
                                @if ($data->dp)
                                <tr>
                                  <td align="right" style="width:50%">Done payment :</td>
                                  <td align="right" style="width:50%">- Rp.{{number_format($data->dp, 0, ',', '.')}}</td>
                                </tr>
                                @endif
                            @endif
                            @if ($data->charge > 0)
                              <tr>
                                 <td align="right" style="width:50%">Charge :</td>
                                 <td align="right" style="width:50%">+ Rp.{{number_format($data->charge, 0, ',', '.')}}</td>
                              </tr>
                            @endif
                        </thead>
                     </table>
                     
                     {{-- start garis tepi --}}
                     <table style="width:100%;">
                        <thead>
                           <tr>
                              <hr>
                           </tr>
                        </thead>
                     </table>
                     {{-- end garis tepi --}}
                     <table style="width:100%;">
                        <thead>
                            <tr>
                            @if ($data->installment)
                                <td align="right" class="total">Total :</td>
                              <td align="right" class="total">Rp. {{number_format($data->amount_installment, 0, ',', '.')}}</td>
                           @else
                           <td align="right" class="total">Total :</td>
                           <td align="right" class="total">Rp. {{number_format($data->amount-$data->dp, 0, ',', '.')}}</td>
                           @endif
                        </tr>
                        </thead>
                     </table>
               </td>
               </tr>

            </tbody>
        </table>
        
    </body>
</html>