@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('supplier.store') }}" onsubmit="submitForm()">
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
                                                    value="{{ old('no_invoice') }}" required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('no_invoice') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label for="supplier_name">Supplier Name<span style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">

                                                <input name="supplier_name" type="text" class="form-control"
                                                    id="supplier_name" placeholder="" autocomplete="off"
                                                    value="{{ old('supplier_name') }}" required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('supplier_name') }}</p>
                                            @endif

                                            {{-- <button class="btn text-primary" data-toggle="modal"
                                                data-target="#addSupplierModal">
                                                + Add Supplier
                                            </button> --}}
                                            <div class="row">
                                                @if ($errors->has('supplier_name'))
                                                    <span class="text-danger">{{ $errors->first('supplier_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{--  --}}
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

                                        {{-- <div class="col-md-6">
                                            <label>Date <span style="color: red">*</span></label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input name="date" type="text" class="form-control"
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
                                        </div> --}}

                                        <div class="col-md-6">
                                            <label for="date">Date<span style="color: red">*</span> :</label>
                                            <input type="date" name="date" class="form-control" data-inputmask-inputformat="dd/mm/yyyy"
                                            data-mask required>

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
                                                    value="{{ old('nota') }}" required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('nota') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="deadline_invoice">Deadline Invoice<span style="color: red">*</span>
                                                :</label>
                                            <input type="date" name="deadline_invoice" class="form-control" data-inputmask-inputformat="dd/mm/yyyy"
                                            data-mask required>

                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('deadline_invoice') }}</p>
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

                        <!-- Modal -->
                        <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog"
                            aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('transaction-send-supplier.store') }}"
                                            id="addSupplierForm" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="supplier_name">Supplier Name :</label>
                                                <input type="text" class="form-control" id="supplier_name"
                                                    name="supplier_name" required>
                                                @if ($errors->has('supplier_name'))
                                                    <span class="text-danger">{{ $errors->first('supplier_name') }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier_role">Role :</label>
                                                <input type="text" class="form-control" id="supplier_role"
                                                    name="supplier_role" required>
                                                @if ($errors->has('supplier_role'))
                                                    <span class="text-danger">{{ $errors->first('supplier_role') }}</span>
                                                @endif
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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
