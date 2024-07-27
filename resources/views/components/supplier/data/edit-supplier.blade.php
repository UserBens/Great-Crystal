@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('supplier.update', $suppliers->id) }}" onsubmit="submitForm()">
                            {{-- <form id="accountForm" method="POST" action="{{ route('account.update', $accountNumbers->id) }}" --}}

                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Supplier Data</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="name">Supplier Name<span
                                                    style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <input name="name" type="text" class="form-control"
                                                    id="name" placeholder="Enter Supplier Name"
                                                    autocomplete="off" value="{{ $suppliers->name }}" required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="no_telp">Telephone<span style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">
                                                <input name="no_telp" type="text" class="form-control"
                                                    id="no_telp" placeholder="0812xxxx" autocomplete="off"
                                                    value="{{ $suppliers->no_telp }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('no_telp') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="email">Email<span style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">
                                                <input name="email" type="text" class="form-control"
                                                    id="email" placeholder="example@gmail.com"
                                                    autocomplete="off" value="{{ $suppliers->email }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="address">Address :</label>
                                            <div class="input-group">
                                                <input name="address" type="text" class="form-control"
                                                    id="address" placeholder="Jl. Darmo Permai"
                                                    autocomplete="off" value="{{ $suppliers->address }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('address') }}</p>
                                            @endif
                                        </div>                                        
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="city">City :</label>
                                            <div class="input-group">
                                                <input name="city" type="text" class="form-control"
                                                    id="city" placeholder="Enter City"
                                                    autocomplete="off" value="{{ $suppliers->city }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('city') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="province">Province :</label>
                                            <div class="input-group">
                                                <input name="province" type="text" class="form-control"
                                                    id="province" placeholder="Enter Province"
                                                    autocomplete="off" value="{{ $suppliers->province }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('province') }}</p>
                                            @endif
                                        </div>                                   
                                    </div>
                                   
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="post_code">Post Code :</label>
                                            <div class="input-group">
                                                <input name="post_code" type="text" class="form-control"
                                                    id="post_code" placeholder="611xxx" autocomplete="off"
                                                    value="{{ $suppliers->post_code }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('post_code') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="accountnumber">Account Number :</label>
                                            <div class="input-group">
                                                <input name="accountnumber" type="text"
                                                    class="form-control" id="accountnumber"
                                                    placeholder="1177999xxxx" autocomplete="off"
                                                    value="{{ $suppliers->accountnumber }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('accountnumber') }}
                                                </p>
                                            @endif
                                        </div>                                      
                                    </div>

                                    <div class="form-group row">                                    
                                        <div class="col-md-6">
                                            <label for="accountnumber_holders_name">Account Holder's Name
                                                :</label>
                                            <div class="input-group">
                                                <input name="accountnumber_holders_name" type="text"
                                                    class="form-control" id="accountnumber_holders_name"
                                                    placeholder="A/N" autocomplete="off"
                                                    value="{{ $suppliers->accountnumber_holders_name }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">
                                                    {{ $errors->first('accountnumber_holders_name') }}</p>
                                            @endif
                                        </div>
   
                                        <div class="col-md-6">
                                            <label for="bank_name">Bank Name :</label>
                                            <div class="input-group">
                                                <input name="bank_name" type="text" class="form-control"
                                                    id="bank_name" placeholder="Enter Bank Name"
                                                    autocomplete="off" value="{{ $suppliers->bank_name }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('bank_name') }}</p>
                                            @endif
                                        </div>                                       
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="description">Description :</label>
                                            <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30"
                                                rows="5" placeholder="Enter description">{{ $suppliers->description }}</textarea>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('description') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-12 mt-3">
                            </div>
                        </form>

                       

                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function removeThousandSeparator(input) {
            // Remove thousand separator (.)
            let value = input.value.replace(/\./g, '');

            // Update input value
            input.value = value;
        }

        // Fungsi untuk menghapus pemisah ribuan sebelum formulir disubmit
        function submitForm() {
            // Hapus pemisah ribuan dari input amount_spent
            let amountInput = document.getElementById("amount");
            removeThousandSeparator(amountInput);

            // Submit formulir
            document.getElementById("transferForm").submit();
        }

        // Inisialisasi Select2 pada elemen select
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
