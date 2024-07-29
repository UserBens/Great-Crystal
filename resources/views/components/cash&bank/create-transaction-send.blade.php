@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('transaction-send.store') }}"
                            onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Transaction Send</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="amount">Transaction No.<span style="color: red">*</span> :</label>
                                            <div class="input-group">

                                                <input name="no_transaction" type="text" class="form-control"
                                                    id="no_transaction" placeholder="Example : S-100031" autocomplete="off"
                                                    value="{{ old('no_transaction') }}" required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('no_transaction') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>Supplier : <span style="color: red">*</span></label>
                                            <select name="transaction_send_supplier_id" class="form-control select2"
                                                id="supplierSelect">
                                                <option value="" selected disabled>Select a Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }} -
                                                        {{ $supplier->supplier_role }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button class="btn text-primary" data-toggle="modal"
                                                data-target="#addSupplierModal">
                                                + Add Supplier
                                            </button>
                                            <div class="row">
                                                @if ($errors->has('supplier_name'))
                                                    <span class="text-danger">{{ $errors->first('supplier_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Pay From : <span style="color: red">*</span></label>
                                            <select name="transfer_account_id" id="transfer_account_id"
                                                class="form-control select2">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} -
                                                        {{ $accountNumber->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn text-primary" data-toggle="modal"
                                                    data-target="#addAccountModal">
                                                    + Add Account
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>For : <span style="color: red">*</span></label>
                                            <select name="deposit_account_id" id="deposit_account_id"
                                                class="form-control select2">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} -
                                                        {{ $accountNumber->name }}</option>
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
                                        <div class="col-md-12">
                                            <label for="description">Description :</label>
                                            <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30"
                                                rows="10" placeholder="Enter description">{{ old('description') }}</textarea>
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

                        {{-- modal create accountnumber --}}
                        <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog"
                            aria-labelledby="addAccountModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAccountModalLabel">Add Account</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('transaction-send.account.store') }}" id="addAccountForm"
                                            method="POST">

                                            @csrf
                                            <div class="form-group">
                                                <label for="account_no">Account Number<span style="color: red">*</span>
                                                    :</label>
                                                <input type="text" class="form-control" id="account_no"
                                                    name="account_no" placeholder="xxx.xxx"
                                                    value="{{ old('account_no') }}" required>
                                                @error('account_no')
                                                    <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Account Name<span style="color: red">*</span>
                                                    :</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter Account Name" value="{{ old('name') }}"
                                                    required>
                                                @error('name')
                                                    <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Category<span style="color: red">*</span> :</label>
                                                <div class="input-group">
                                                    <select name="account_category_id" class="form-control select2"
                                                        id="account_category_id" style="width: 100%">
                                                        @foreach ($accountCategory as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('account_category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('account_category_id')
                                                    <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description :</label>
                                                <textarea class="form-control" id="description" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            @endif

            @if ($errors->any())
                let errorMessages = "";
                @foreach ($errors->all() as $error)
                    errorMessages += "{{ $error }}<br>";
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessages,
                    timer: 5000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>

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
