@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="row col-10">
            <div class="col-lg-7">
                <!-- general form elements -->
                <div>
                    {{-- <form method="POST" action={{route('actionRegister')}}> --}}
                    <form method="POST" action={{route('action.edit.installment', [ 'bill_id' => $data->id ])}}>
                    @csrf
                    @method('PUT')
                     <!-- form start -->
                    <div class="card card-dark">
                     <div class="card-header">
                         <h3 class="card-title">Edit {{strtolower($data->type)}} installment for {{$data->student->name}}
                         </h3>
                     </div>
                     <!-- /.card-header -->
                     <!-- form start -->
                     <div class="card-body">
                         
                        @if( sizeof($data->bill_installments) > 0 )
                            @foreach ($data->bill_installments as $idx => $item)


                                <div class="form-group row">
                                    
                                    <div class="col-md-12">
                                        <label for="amount">{{$item->type . ' intallment ' . $item->subject}}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="{{'index_'.$item->id}}" type="text" class="form-control"
                                            id="amount{{$idx}}" placeholder="Enter amount capital fee" value="{{old('index_'.$item->id) ? old('index_'.$item->id) : number_format($item->amount_installment, 0, ',', '.')}}">
                                        </div>
                                        @if($errors->any())
                                                 <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                                


                           
                           <div class="row">
                            <div class="col-12 ml-2 mt-4">
                              <div class="icheck-primary">
                                <input type="checkbox" id="sendEmail" name="sendEmail" value="{{true}}" required>
                                <label for="sendEmail">
                                   I agree with the term.
                                </label>
                              </div>
                            </div>
                            <!-- /.col -->
                          </div>
                        </div>
                     </div>
                 </div>
                 <!-- /.card-body Brother or sisters -->
                        
                        {{-- <div class="d-flex justify-content-center my-5">
                            <button type="submit" class="col-12 btn btn-success">Register Now</button>
                        </div> --}}

                        <!-- Button trigger modal -->
                        <div class="d-flex justify-content-end my-5">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModalCenter">
                            Update installment
                        </button>
                    </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Register student</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure want to update installment {{$data->type}} from {{$data->student->name}}?
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                                <button type="submit" class="btn btn-primary">Yes update</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-lg-3 p-1">
                    <div class="card mb-4 p-4">
                       <table>
                          <thead>
                             <th></th>
                             <th></th>
                          </thead>
                          <tbody>
                            
                            @if( sizeof($data->bill_installments) > 0 )
                                @foreach ($data->bill_installments as $idx => $item)
                                    <tr>
                                        <td align="left">{{$item->type}} ({{$idx+1}})</td>
                                        <td align="right">Rp. {{number_format((string)$item->amount_installment, 0, ",", ".")}}</td>
                                    </tr>
                                @endforeach
                            @endif

                            
                        </tbody>
                    </table>
                    <hr>

                    <table>
                        <thead>
                           <th></th>
                           <th></th>
                        </thead>
                        <tbody>
                                  <tr>
                                      <td align="left"><b>Amount</b></td>
                                      <td align="right"><b>Rp. {{number_format((string)$data->amount - $data->dp, 0, ",", ".")}}</b></td>
                                  </tr>
                                  <tr>
                                      <td align="left">Done Payment</td>
                                      <td align="right">Rp. {{number_format((string)$data->dp, 0, ",", ".")}}</td>
                                  </tr>
                      </tbody>
                  </table>

                    <hr>

                    <table>
                        <thead>
                           <th></th>
                           <th></th>
                        </thead>
                        <tbody>
                                  <tr>
                                      <td align="left">Total</td>
                                      <td align="right">Rp. {{number_format((string)$data->amount, 0, ",", ".")}}</td>
                                  </tr>
                      </tbody>
                  </table>
        
                  </div>
                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>



<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

  @if ($errors->any())

    @if($errors->first('paket'))

    <script>

        var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 8000
        });
      

           Toast.fire({
              icon: 'error',
              title: 'Invalid amount paket, please check the grade data payment first !!!',
        });
      
    </script>

    @endif

    @if ($errors->first('bill'))

        <script>
            var errorText = "{{$errors->first('bill')}}";
            
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: errorText,
              footer: '<a href="#">Why do I have this issue?</a>'
            });
        </script>
        
    @endif

    @endif

    <script>
    // Mendapatkan elemen input
    let input0 = document.getElementById("amount0");

    // Menambahkan event listener pada input saat pengguna mengetik
    input0.addEventListener("input", function () {
        const rawValue = input0.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input0.value = formattedValue;
    });
    let input1 = document.getElementById("amount1");

    // Menambahkan event listener pada input saat pengguna mengetik
    input1.addEventListener("input", function () {
        const rawValue = input1.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input1.value = formattedValue;
    });
    let input2 = document.getElementById("amount2");

    // Menambahkan event listener pada input saat pengguna mengetik
    input2.addEventListener("input", function () {
        const rawValue = input2.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input2.value = formattedValue;
    });
    let input3 = document.getElementById("amount3");

    // Menambahkan event listener pada input saat pengguna mengetik
    input3.addEventListener("input", function () {
        const rawValue = input3.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input3.value = formattedValue;
    });
    let input4 = document.getElementById("amount4");

    // Menambahkan event listener pada input saat pengguna mengetik
    input4.addEventListener("input", function () {
        const rawValue = input4.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input4.value = formattedValue;
    });
    let input5 = document.getElementById("amount5");

    // Menambahkan event listener pada input saat pengguna mengetik
    input5.addEventListener("input", function () {
        const rawValue = input5.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input5.value = formattedValue;
    });
    let input6 = document.getElementById("amount6");

    // Menambahkan event listener pada input saat pengguna mengetik
    input6.addEventListener("input", function () {
        const rawValue = input6.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input6.value = formattedValue;
    });
    let input7 = document.getElementById("amount7");

    // Menambahkan event listener pada input saat pengguna mengetik
    input7.addEventListener("input", function () {
        const rawValue = input7.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input7.value = formattedValue;
    });
    let input8 = document.getElementById("amount8");

    // Menambahkan event listener pada input saat pengguna mengetik
    input8.addEventListener("input", function () {
        const rawValue = input8.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input8.value = formattedValue;
    });
    let input9 = document.getElementById("amount9");

    // Menambahkan event listener pada input saat pengguna mengetik
    input9.addEventListener("input", function () {
        const rawValue = input9.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input9.value = formattedValue;
    });
    let input10 = document.getElementById("amount10");

    // Menambahkan event listener pada input saat pengguna mengetik
    input10.addEventListener("input", function () {
        const rawValue = input10.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input10.value = formattedValue;
    });
    let input11 = document.getElementById("amount11");

    // Menambahkan event listener pada input saat pengguna mengetik
    input11.addEventListener("input", function () {
        const rawValue = input11.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input11.value = formattedValue;
    });
    let input12 = document.getElementById("amount12");

    // Menambahkan event listener pada input saat pengguna mengetik
    input12.addEventListener("input", function () {
        const rawValue = input12.value.replace(/[^0-9]/g, '');
        const formattedValue = addThousandSeparator(rawValue);
        input12.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Mendapatkan elemen input
    const inputdp = document.getElementById("dp");

    // Menambahkan event listener pada input saat pengguna mengetik
    inputdp.addEventListener("input", function () {
        // Mengambil nilai input tanpa tanda titik dan karakter non-angka
        const rawValue = inputdp.value.replace(/[^0-9]/g, '');

        // Mengubah nilai input dengan menambahkan tanda titik setiap 3 digit
        const formattedValue = addThousandSeparator(rawValue);

        // Memasukkan nilai yang telah diformat kembali ke dalam input
        inputdp.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

</script>

@endsection
