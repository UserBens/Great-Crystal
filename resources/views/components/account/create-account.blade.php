@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div>
                        <form id="accountForm" method="POST" action="{{ route('account.store') }}"
                            onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Account Number</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="name">Name<span style="color: red">*</span> :</label>
                                            <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Enter Name" value="{{ old('name') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="account_no">Account Number<span style="color: red">*</span> :</label>
                                            <input name="account_no" type="text" class="form-control" id="account_no"
                                                placeholder="Enter Account Number" value="{{ old('account_no') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('account_no') }}</p>
                                            @endif
                                        </div>


                                    </div>

                                    <div class="form-group row">
                                        

                                        <div class="col-md-6">
                                            <label>Type : <span style="color: red"></span></label>
                                            <select name="type" class="form-control">
                                                <option value="cash">
                                                    Cash</option>
                                                <option value="bank">Bank
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="bank_name">Bank Name<span style="color: red">*</span> :</label>
                                            <input name="bank_name" type="text" class="form-control" id="bank_name"
                                                placeholder="Enter Bank Name" value="{{ old('bank_name') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('bank_name') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="amount">Amount<span style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="amount" type="text" class="form-control"
                                                    id="amount" placeholder="Enter amount" autocomplete="off"
                                                    value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('amount') }}</p>
                                            @endif
                                        </div>
                                    </div>


                                   

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="description">Description :</label>
                                            <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30" rows="10"
                                                placeholder="Enter description">{{ old('description') }}</textarea>
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
            document.getElementById("accountForm").submit();
        }
    </script>
@endsection
