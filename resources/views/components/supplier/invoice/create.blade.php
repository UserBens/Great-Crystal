@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('invoice-supplier.store') }}"
                            enctype="multipart/form-data" onsubmit="submitForm()">
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
                                                    placeholder="Enter No Invoice" autocomplete="off"
                                                    value="{{ old('no_invoice') }}">
                                            </div>
                                            @error('no_invoice')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="supplier_id">Supplier Name<span style="color: red">*</span>
                                                :</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control select2">
                                                @foreach ($supplierDatas as $supplierData)
                                                    <option value="{{ $supplierData->id }}">{{ $supplierData->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                            <div class="input-group-append">
                                                <button type="button" class="btn text-primary" data-toggle="modal"
                                                    data-target="#importModal">
                                                    + Add Supplier
                                                </button>
                                            </div>
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
                                            @error('amount')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="nota">Nota<span style="color: red">*</span> :</label>
                                            <div class="input-group">
                                                <input name="nota" type="text" class="form-control" id="nota"
                                                    placeholder="Enter Nota" autocomplete="off" value="{{ old('nota') }}">
                                            </div>
                                            @error('nota')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Debit : <span style="color: red">*</span></label>
                                            <select name="deposit_account_id" id="deposit_account_id"
                                                class="form-control select2">
                                                @foreach ($accountNumbers as $accountNumber)
                                                    <option value="{{ $accountNumber->id }}">
                                                        {{ $accountNumber->account_no }} -
                                                        {{ $accountNumber->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Kredit : <span style="color: red">*</span></label>
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
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="date">Date<span style="color: red">*</span> :</label>
                                            <input type="date" name="date" class="form-control"
                                                value="{{ old('date') }}" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask>

                                            @error('date')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="deadline_invoice">Deadline Invoice<span style="color: red">*</span>
                                                :</label>
                                            <input type="date" name="deadline_invoice" class="form-control"
                                                value="{{ old('deadline_invoice') }}"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                                            @error('deadline_invoice')
                                                <p style="color: red">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="pph">PPH :</label>
                                            <div class="input-group">
                                                <input name="pph" type="text" class="form-control" id="pph"
                                                    placeholder="Enter PPH" autocomplete="off"
                                                    value="{{ old('pph') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pph_percentage">PPH Percentage :</label>
                                            <div class="input-group">
                                                <input name="pph_percentage" type="text" class="form-control"
                                                    id="pph_percentage" placeholder="Enter Percentage" autocomplete="off"
                                                    value="{{ old('pph_percentage') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="description">Description :</label>
                                            <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30"
                                                rows="5" placeholder="Enter description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="upload_image">Upload Image <span style="color: red">*</span> :</label>
                                        <div class="image-upload-wrap" id="image-upload-wrap">
                                            <input type="file" name="image_invoice" class="file-upload-input"
                                                onchange="readURL(this, '');" accept="image/*">

                                            <div class="drag-text">
                                                <h3>Drag and drop a file or select add
                                                    Image</h3>
                                            </div>
                                        </div>
                                        <div class="file-upload-content" id="file-upload-content" style="display:none;">
                                            <div class="image-file-name" id="image-file-name"
                                                style="text-align: center; margin-top: 10px;">
                                            </div>
                                            <img class="file-upload-image" id="file-upload-image" src="#"
                                                alt="your image" style="max-width: 100%; max-height: 100%;" />
                                            <div class="image-title-wrap"
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <button type="button" onclick="removeUpload(this, '')"
                                                    class="btn btn-danger btn-sm" style="border: none; background: none;">
                                                    <i class="fa-solid fa-trash"
                                                        style="font-size: 1.5rem; color: black;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <input id="submitButton" type="submit" class="btn btn-success center col-12 mt-3">
                            </div>
                        </form>

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
                                        <form action="{{ route('invoice-supplier-createinvoice.account.store') }}"
                                            id="addAccountForm" method="POST">
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

                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog"
                            aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%; margin: 1.75rem auto;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="importModalLabel">Create Supplier</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="uploadForm" action="{{ route('invoice-create-supplier.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body" style="width: 100%; height: auto;">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label for="name">Supplier Name<span
                                                                style="color: red">*</span> :</label>
                                                        <div class="input-group">
                                                            <input name="name" type="text" class="form-control"
                                                                id="name" placeholder="Enter Supplier Name"
                                                                autocomplete="off" value="{{ old('name') }}" required>
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
                                                                value="{{ old('no_telp') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('no_telp') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label for="email">Email :</label>
                                                        <div class="input-group">
                                                            <input name="email" type="text" class="form-control"
                                                                id="email" placeholder="example@gmail.com"
                                                                autocomplete="off" value="{{ old('email') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('email') }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="accountnumber">Account Number :</label>
                                                        <div class="input-group">
                                                            <input name="accountnumber" type="text"
                                                                class="form-control" id="accountnumber"
                                                                placeholder="1177999xxxx" autocomplete="off"
                                                                value="{{ old('accountnumber') }}">
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
                                                                value="{{ old('accountnumber_holders_name') }}">
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
                                                                autocomplete="off" value="{{ old('bank_name') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('bank_name') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label for="address">Address :</label>
                                                        <div class="input-group">
                                                            <input name="address" type="text" class="form-control"
                                                                id="address" placeholder="Jl. Darmo Permai"
                                                                autocomplete="off" value="{{ old('address') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('address') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="city">City :</label>
                                                        <div class="input-group">
                                                            <input name="city" type="text" class="form-control"
                                                                id="city" placeholder="Enter City"
                                                                autocomplete="off" value="{{ old('city') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('city') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label for="province">Province :</label>
                                                        <div class="input-group">
                                                            <input name="province" type="text" class="form-control"
                                                                id="province" placeholder="Enter Province"
                                                                autocomplete="off" value="{{ old('province') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('province') }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="post_code">Post Code :</label>
                                                        <div class="input-group">
                                                            <input name="post_code" type="text" class="form-control"
                                                                id="post_code" placeholder="611xxx" autocomplete="off"
                                                                value="{{ old('post_code') }}">
                                                        </div>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('post_code') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <label for="description">Description :</label>
                                                        <textarea autocomplete="off" name="description" class="form-control" id="description" cols="30"
                                                            rows="5" placeholder="Enter description">{{ old('description') }}</textarea>
                                                        @if ($errors->any())
                                                            <p style="color: red">{{ $errors->first('description') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('file-upload-content' + id).style.display = 'block';
                    document.getElementById('image-upload-wrap' + id).style.display = 'none';
                    document.getElementById('file-upload-image' + id).src = e.target.result;
                    document.getElementById('image-file-name' + id).innerHTML = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                removeUpload(input, id);
            }
        }

        function removeUpload(input, id) {
            document.getElementById('file-upload-content' + id).style.display = 'none';
            document.getElementById('image-upload-wrap' + id).style.display = 'block';
            input.value = '';
        }

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


        document.addEventListener('DOMContentLoaded', function() {
            let pphSelect = document.getElementById('pph');
            let ppnStatusContainer = document.getElementById('pph_percentage_container');
            let ppnStatusSelect = document.getElementById('pph_percentage');

            pphSelect.addEventListener('change', function() {
                if (pphSelect.value !== '') {
                    ppnStatusContainer.style.display = 'block';
                } else {
                    ppnStatusContainer.style.display = 'none';
                }

                let options = ['2%', '15%', '2,5%', '7,5%', '20%'];
                ppnStatusSelect.innerHTML = '';
                options.forEach(function(option) {
                    let opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = option;
                    ppnStatusSelect.appendChild(opt);
                });
            });

            // Inisialisasi Select2 pada elemen select
            $(document).ready(function() {
                $('.select2').select2();
            });
        });

        // Menonaktifkan aksi default dari tombol "Upload Proof Image"
        document.getElementById('uploadProofButton').addEventListener('click', function(e) {
            e.preventDefault();
        });

        // Menangani klik tombol "Submit" di luar divisi upload gambar
        document.getElementById('submitButton').addEventListener('click', function() {
            submitForm();
        });
    </script>
@endsection
