@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div>
                        <form id="transferForm" method="POST" action="{{ route('invoice-supplier.store') }}" enctype="multipart/form-data"
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
                                                    placeholder="Enter No Invoice" autocomplete="off"
                                                    value="{{ old('no_invoice') }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('no_invoice') }}</p>
                                            @endif
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
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('supplier_id') }}</p>
                                            @endif
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
                                                    placeholder="Enter Nota" autocomplete="off" value="{{ old('nota') }}">
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
                                            <label for="pph">PPH<span style="color: red">*</span> :</label>
                                            <div class="input-group">

                                                <input name="pph" type="text" class="form-control" id="pph"
                                                    placeholder="Enter PPH" autocomplete="off" value="{{ old('pph') }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('pph') }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pph_percentage">Percentage<span style="color: red">*</span>
                                                :</label>
                                            <div class="input-group">

                                                <input name="pph_percentage" type="text" class="form-control"
                                                    id="pph_percentage" placeholder="Enter Percentage" autocomplete="off"
                                                    value="{{ old('pph_percentage') }}">
                                            </div>
                                            @if ($errors->any())
                                                <p style="color: red">{{ $errors->first('pph_percentage') }}</p>
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

                                    <div class="col-md-12 mt-3">
                                        <label for="upload_image">Upload Image :</label>
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
                                                    class="remove-image" style="margin-right: 10px">
                                                    <i class="fa-solid fa-trash fa-2xl" style="margin-bottom: 1em;"></i>
                                                    <br> Remove
                                                    <span class="image-title">Image</span>
                                                </button>
                                                <button type="submit" role="button" class="upload-image">
                                                    <i class="fa-solid fa-cloud-arrow-up fa-2xl fa-bounce"
                                                        style="margin-bottom: 1em;"></i>
                                                    <br> Upload Proof
                                                    <span class="image-title">Image</span>
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
                                                        <label for="email">Email<span style="color: red">*</span>
                                                            :</label>
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
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
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

            $('.select2').select2();
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
