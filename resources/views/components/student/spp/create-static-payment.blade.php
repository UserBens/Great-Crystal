@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('create.static.student', ['id' => $data->unique_id, 'type' => $type])}}>
                        
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create invoice {{$type}} for {{$data->name}}</h3>
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
                                            $amount = old('amount') ? old('amount') : ($data->grade->spp?
                                            $data->grade->spp->amount : null);
                                            @endphp
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount"
                                                value="{{$amount ? number_format($amount, 0, ',', '.') : ''}}" required autocomplete="off">
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
                                                value="{{old('discount')? old('discount') : '0'}}">

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
                                @if($type != 'SPP')
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="installment">Installment</label>
                                        <input name="installment" type="number" class="form-control" id="installment"
                                            placeholder="(cicilan)" value="{{old('installment')}}" max="12">

                                          @if($errors->any())
                                          <p style="color: red">{{$errors->first('installment')}}</p>
                                          @endif
                                       </div>
                                    </div>
                                 @endif
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