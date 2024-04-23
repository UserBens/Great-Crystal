@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('update.payment.student-static', ['id' => $data->unique_id, 'id_student_payment' => $data->spp_student->id, 'type' => $data->spp_student->type])}}>
                        
                        @csrf
                        @method("PUT")
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit invoice {{$data->spp_student->type}} for {{$data->name}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                
                                <div class="form-group row">
                                    <div class="col-md-12">

                                        <label for="amount">Amount<span style="color: red">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            @php
                                            $amount = old('amount') ? old('amount') : ($data->spp_student?
                                            $data->spp_student->amount : '');
                                            @endphp
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount"
                                                value="{{$amount ? number_format($amount, 0, ',', '.') : ''}}" autocomplete="off" required>
                                            
                                        </div>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>

                                    {{-- <div class="col-md-3">

                                        <label for="discount">Discount</label>

                                        <div class="input-group">

                                            <input name="discount" type="text" class="form-control" id="percentageInput"
                                                placeholder="0" maxlength="2" oninput="addPercentageSymbol()"
                                                value="{{old('discount')? old('discount') : ($data->spp_student?
                                                $data->spp_student->discount : '')}}">

                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>

                                        </div>
                                    </div> --}}
                                    @if ($amount)   
                                    <small class="text-muted ml-3">Amount auto input from spp {{$data->grade->name}}-{{$data->grade->class}}</small>
                                    @endif

                                    @if($errors->any())
                                    <p style="color: red">{{$errors->first('discount')}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

</section>


<script>
    function addPercentageSymbol() {
        var inputElement = document.getElementById("percentageInput");
        var resultElement = document.getElementById("result");

        // Menghapus spasi dan karakter non-angka
        var inputValue = inputElement.value.replace(/\D/g, "");

        // Membatasi panjang input menjadi 2 karakter
        if (inputValue.length > 2) {
            inputValue = inputValue.substring(0, 2);
        }

        // Menetapkan nilai input sesuai dengan yang telah dimodifikasi
        inputElement.value = inputValue;
    }

</script>


@endsection
