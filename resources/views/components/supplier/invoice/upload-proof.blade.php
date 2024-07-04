@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Upload Proof of Payment</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="uploadForm{{ $invoice->id }}"
                                    action="{{ route('invoice-supplier.upload-proof', $invoice->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_invoice"># No. Invoice:</label>
                                                <input name="no_invoice" type="text" class="form-control" id="no_invoice"
                                                    value="{{ $invoice->no_invoice }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="supplier_name">Supplier Name:</label>
                                                <input name="supplier_name" type="text" class="form-control"
                                                    id="supplier_name" value="{{ $invoice->supplier_name }}" readonly>
                                                {{-- @if ($errors->has('supplier_name'))
                                                    <span class="text-danger">{{ $errors->first('supplier_name') }}</span>
                                                @endif --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nota">Nota:</label>
                                                <input name="nota" type="text" class="form-control" id="nota"
                                                    value="{{ $invoice->nota }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Description:</label>
                                                <textarea name="description" class="form-control" id="description" readonly>{{ $invoice->description }}</textarea>
                                                {{-- @if ($errors->has('description'))
                                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                                @endif --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payment_method">Payment Method:</label>
                                                <select name="payment_method" class="form-control" id="payment_method">
                                                    <option value="Cash"
                                                        {{ $invoice->payment_method == 'Cash' ? 'selected' : '' }}>Kas
                                                    </option>
                                                    <option value="Bank"
                                                        {{ $invoice->payment_method == 'Bank' ? 'selected' : '' }}>Bank
                                                    </option>

                                                </select>
                                                @if ($errors->any())
                                                    <p style="color: red">
                                                        {{ $errors->first('payment_method') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status:</label>
                                                <select name="payment_status" class="form-control" id="payment_status">
                                                    <option value="Not Yet"
                                                        {{ $invoice->payment_status == 'Not Yet' ? 'selected' : '' }}>
                                                        Not Yet</option>
                                                    <option value="Paid"
                                                        {{ $invoice->payment_status == 'Paid' ? 'selected' : '' }}>
                                                        Paid</option>
                                                </select>
                                                @if ($errors->any())
                                                    <p style="color: red">
                                                        {{ $errors->first('payment_status') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transfer_account_id">Transfer From:</label>
                                                <select name="transfer_account_id" id="transfer_account_id"
                                                    class="form-control select2">
                                                    @foreach ($accountNumbers as $accountNumber)
                                                        <option value="{{ $accountNumber->id }}">
                                                            {{ $accountNumber->account_no }} - {{ $accountNumber->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->any())
                                                    <p style="color: red">
                                                        {{ $errors->first('transfer_account_id') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="deposit_account_id">Deposit To:</label>
                                                <select name="deposit_account_id" id="deposit_account_id"
                                                    class="form-control select2">
                                                    {{-- Populate with your deposit accounts --}}
                                                    @foreach ($accountNumbers as $accountNumber)
                                                        <option value="{{ $accountNumber->id }}">
                                                            {{ $accountNumber->account_no }} - {{ $accountNumber->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->any())
                                                    <p style="color: red">
                                                        {{ $errors->first('deposit_account_id') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label for="upload_image">Upload Image<span style="color: red">*</span>:</label>
                                            <div class="image-upload-wrap" id="image-upload-wrap{{ $invoice->id }}">
                                                {{-- <input type="file" name="image_path" class="file-upload-input"
                                                    onchange="readURL(this, '{{ $invoice->id }}');" accept="image/*"> --}}

                                                <input type="file" name="image_path" class="file-upload-input"
                                                    onchange="readURL(this, '{{ $invoice->id }}');" accept="image/*">

                                                <div class="drag-text">
                                                    <h3>Drag and drop a file or select add
                                                        Image</h3>
                                                </div>
                                            </div>
                                            <div class="file-upload-content" id="file-upload-content{{ $invoice->id }}"
                                                style="display:none;">
                                                <div class="image-file-name" id="image-file-name{{ $invoice->id }}"
                                                    style="text-align: center; margin-top: 10px;">
                                                </div>
                                                <img class="file-upload-image" id="file-upload-image{{ $invoice->id }}"
                                                    src="#" alt="your image"
                                                    style="max-width: 100%; max-height: 100%;" />
                                                <div class="image-title-wrap"
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <button type="button"
                                                        onclick="removeUpload(this, '{{ $invoice->id }}')"
                                                        class="remove-image" style="margin-right: 10px">
                                                        <i class="fa-solid fa-trash fa-2xl"
                                                            style="margin-bottom: 1em;"></i>
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
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </section>

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
    </script>
@endsection
