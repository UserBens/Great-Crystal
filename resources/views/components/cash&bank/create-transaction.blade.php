@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div>
                        <form id="expenditureForm" method="POST" action="{{ route('expenditure.store') }}"
                            onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Transaction</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="subject">Name<span style="color: red">*</span> :</label>
                                            <input name="subject" type="text" class="form-control" id="subject"
                                                placeholder="Enter subject" value="{{ old('subject') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('subject') }}</p>
                                            @endif
                                        </div>

                                        {{-- <div class="col-md-6">
                                            <label for="subject">Account Number<span style="color: red">*</span> :</label>
                                            <input name="subject" type="text" class="form-control" id="subject"
                                                placeholder="Enter subject" value="{{ old('subject') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('subject') }}</p>
                                            @endif
                                        </div> --}}

                                        <div class="col-md-6">
                                            <label>Account Number : <span style="color: red"></span></label>
                                            <select name="account_no" class="form-control">
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
                                            <label for="subject">Bank Name<span style="color: red">*</span> :</label>
                                            <input name="subject" type="text" class="form-control" id="subject"
                                                placeholder="Enter subject" value="{{ old('subject') }}" autocomplete="off"
                                                required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('subject') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="amount_spent">Amount<span style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="amount_spent" type="text" class="form-control"
                                                    id="amount" placeholder="Enter amount" autocomplete="off"
                                                    value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('amount_spent') }}</p>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">

                                        {{-- <div class="col-md-6">
                                            <label>Type : <span style="color: red"></span></label>
                                            <select name="type" class="form-control">
                                                <option value="inside the school">
                                                    inside the school</option>
                                                <option value="out of school">out of school
                                                </option>
                                            </select>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <label>Date <span style="color: red">*</span></label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input name="spent_at" type="text" class="form-control "
                                                    placeholder="{{ date('d/m/Y') }}" data-target="#reservationdate"
                                                    data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                    data-mask required />
                                                <div class="input-group-append" data-target="#reservationdate"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('date_birth') }}</p>
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
            document.getElementById("expenditureForm").submit();
        }
    </script>
@endsection
