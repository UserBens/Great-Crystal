@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('create.bill', $data->id)}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create invoice bill for {{$data->name}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="type">Type<span style="color: red">*</span> :</label>
                                        <input name="type" type="text" class="form-control" id="type"
                                        placeholder="Enter type" value="{{old('type')}}" 
                                        autocomplete="off"
                                        required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('type')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label>Deadline Invoice<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="deadline_invoice" type="text"
                                                class="form-control " placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdate" 
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                value="{{old('deadline_invoice') ? date("d/m/Y", strtotime(old('deadline_invoice'))) : $monthDefault}}"
                                                required
                                                />
                                                
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('date_birth')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="subject">Subject<span style="color: red">*</span> :</label>
                                        <input name="subject" type="text" class="form-control" id="subject"
                                            placeholder="Enter subject" value="{{old('subject')}}" 
                                            autocomplete="off"
                                            required>
        
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">

                                        <label for="amount">Amount<span style="color: red">*</span> :</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount"
                                                autocomplete="off"
                                                value="{{old('amount') ? number_format(old('amount'), 0, ',', '.') : ''}}" required>
                                        </div>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="description">Description :</label>
                                        <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30" rows="10" placeholder="Enter description">{{old('description')}}</textarea>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('description')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-12 mt-3">
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
