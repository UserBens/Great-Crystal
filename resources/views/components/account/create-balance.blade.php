@extends('layouts.admin.master')
@section('content')
    {{-- <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div>
                        <form id="accountForm" method="POST" action="{{ route('account.store') }}" onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Balance</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Receive From : <span style="color: red">*</span></label>
                                            <select name="transfer_account_id" id="transfer_account_id"
                                                class="form-control select2">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} -
                                                        {{ $accountNumber->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="account_no">Account Number<span style="color: red">*</span>
                                                :</label>
                                            <input name="account_no" type="text" class="form-control" id="account_no"
                                                placeholder="Enter Account Number" value="{{ old('account_no') }}"
                                                autocomplete="off" required>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('account_no') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">                                    
                                        <div class="col-md-6 mt-3">
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
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 mt-2">
                                            <label for="beginning_balance">Beginning Balance<span
                                                    style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="beginning_balance" type="text" class="form-control"
                                                    id="beginning_balance" placeholder="Enter Beginning Balance"
                                                    autocomplete="off"
                                                    value="{{ old('beginning_balance') ? number_format(old('beginning_balance'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('beginning_balance') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <label for="ending_balance">Ending Balance<span style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="ending_balance" type="text" class="form-control"
                                                    id="ending_balance" placeholder="Enter Ending Balance"
                                                    autocomplete="off"
                                                    value="{{ old('ending_balance') ? number_format(old('ending_balance'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('ending_balance') }}</p>
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
                        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog"
                            aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('account-category.store') }}" id="addCategoryForm"
                                            method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="category_name">Category Name</label>
                                                @error('category_name')
                                                    <p style="color: red;">{{ $message }}</p>
                                                @enderror
                                                <input type="text" class="form-control" id="category_name"
                                                    name="category_name" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Category</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div>
                        <form id="accountForm" method="POST" action="{{ route('balance.store') }}" onsubmit="submitForm()">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Create Balance</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Receive From : <span style="color: red">*</span></label>
                                            <select name="accountnumber_id" id="accountnumber_id"
                                                class="form-control select2">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} -
                                                        {{ $accountNumber->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="beginning_balance">Beginning Balance<span style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input name="beginning_balance" type="text" class="form-control" id="amount"
                                                    placeholder="Enter amount" autocomplete="off"
                                                    value="{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}"
                                                    required>
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('beginning_balance') }}</p>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 offset-6">
                                                <button type="submit" class="btn btn-primary"
                                                    style="float: right">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        // Fungsi untuk menghapus pemisah ribuan sebelum formulir disubmit
        // function submitForm() {
        //     // Hapus pemisah ribuan dari input amount_spent
        //     let amountInput = document.getElementById("beginning_balance");
        //     removeThousandSeparator(amountInput);

        //     // Submit formulir
        //     document.getElementById("accountForm").submit();
        // }
        // // Fungsi untuk menghapus pemisah ribuan sebelum formulir disubmit
        // function submitForm() {
        //     // Hapus pemisah ribuan dari input amount_spent
        //     let amountInput = document.getElementById("ending_balance");
        //     removeThousandSeparator(amountInput);

        //     // Submit formulir
        //     document.getElementById("accountForm").submit();
        // }
    </script>
@endsection
