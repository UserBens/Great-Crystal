@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('transaction-transfer.store') }}"
                            onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Transaction Transfer</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Transfer From : <span style="color: red">*</span></label>
                                            <select name="transfer" class="form-control">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} - {{ $accountNumber->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label>To : <span style="color: red">*</span></label>
                                            <select name="deposit" class="form-control">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} - {{ $accountNumber->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="amount">Amount<span style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="amount" type="text" class="form-control" id="amount"
                                                    placeholder="Enter amount" autocomplete="off"
                                                    value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('amount') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label>Date <span style="color: red">*</span></label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input name="date" type="text" class="form-control "
                                                    placeholder="{{ date('d/m/Y') }}" data-target="#reservationdate"
                                                    data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                    data-mask required />
                                                <div class="input-group-append" data-target="#reservationdate"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('date') }}</p>
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
            document.getElementById("transferForm").submit();
        }
    </script>
@endsection
