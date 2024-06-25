@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('invoice-supplier.store') }}"
                            onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Invoice Supplier</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="no_invoice">No. Invoice<span style="color: red">*</span> :</label>
                                            <div class="input-group">

                                                <input name="no_invoice" type="text" class="form-control" id="no_invoice"
                                                    placeholder="Example : S-100031" autocomplete="off"
                                                    value="{{ old('no_invoice') }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('no_invoice') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>Supplier Name <span style="color: red">*</span></label>
                                            <select name="supplier_name" id="supplier_name" class="form-control select2">
                                                @foreach ($supplierDatas as $supplierData)
                                                    <option value="{{ $supplierData->name }}">
                                                        {{ $supplierData->name }}</option>
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
                                                    value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('amount') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="date">Date<span style="color: red">*</span> :</label>
                                            <input type="date" name="date" class="form-control"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('date') }}</p>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="nota">Nota<span style="color: red">*</span> :</label>
                                            <div class="input-group">

                                                <input name="nota" type="text" class="form-control" id="nota"
                                                    placeholder="Example : S-100031" autocomplete="off"
                                                    value="{{ old('nota') }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('nota') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="deadline_invoice">Deadline Invoice<span style="color: red">*</span>
                                                :</label>
                                            <input type="date" name="deadline_invoice" class="form-control"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('deadline_invoice') }}</p>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="ppn">PPH 23<span style="color: red">*</span> :</label>
                                            <select name="ppn_status" class="form-control" id="ppn_status">
                                                <option value="null">null</option>
                                                <option value="2%">2%</option>
                                                <option value="15%">15%</option>
                                            </select>
                                            @if ($errors->any())
                                                <p style="color: red">
                                                    {{ $errors->first('payment_status') }}</p>
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

        // function submitForm() {
        //     // Hapus pemisah ribuan dari input amount_spent
        //     let amountInput = document.getElementById("amount");
        //     removeThousandSeparator(amountInput);

        //     // Get the selected PPH status
        //     let pphStatus = document.getElementById("ppn_status").value;

        //     // Calculate the amount after PPH deduction
        //     let amount = parseFloat(amountInput.value);
        //     if (pphStatus === 'pph23') {
        //         amount = amount * 0.98; // Deduct 2%
        //     } else if (pphStatus === 'pph24') {
        //         amount = amount * 0.85; // Deduct 15%
        //     }

        //     // Update the input value with the new amount
        //     amountInput.value = amount.toFixed(2);

        //     // Submit the form
        //     document.getElementById("transferForm").submit();
        // }

        // Inisialisasi Select2 pada elemen select
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
