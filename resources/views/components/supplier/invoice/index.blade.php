@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Invoice Supplier Search</h2>
        <form action="{{ route('invoice-supplier.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="date">Type Transaction</label>
                    <select name="type" class="form-control">
                        <option value="">-- All Data --</option>
                        <option value="transaction_transfer" {{ $form->type === 'transaction_transfer' ? 'selected' : '' }}>
                            Transaction Transfer</option>
                        <option value="transaction_send" {{ $form->type === 'transaction_send' ? 'selected' : '' }}>
                            Transaction Send</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort">Sort By</label>
                    <select name="sort" class="form-control" id="sort-select">
                        <option>-- All Data --</option>
                        <option value="oldest" {{ $form->sort === 'oldest' ? 'selected' : '' }}>Date (Oldest First)</option>
                        <option value="newest" {{ $form->sort === 'newest' ? 'selected' : '' }}>Date (Newest First)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $form->date ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date">Search Data</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ $form->search ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Not found on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no transfer data found -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Click the button below to create Invoice Supplier!</h3>
                    <div class="btn-group">
                        <a type="button" href="{{ route('create-invoice-supplier.create') }}" class="btn btn-success mt-3">
                            <i class="fa-solid fa-plus"></i> Create Invoice
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="btn-group">
                <a type="button" href="{{ route('create-invoice-supplier.create') }}" class="btn btn-success mt-3">
                    <i class="fa-solid fa-plus"></i> Create Invoice
                </a>
            </div>
            <!-- Display Cash or Bank data in a table -->
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">Invoice Supplier List</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. Invoice</th>
                                <th>Supplier Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Nota</th>
                                <th>Deadline Invoice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through transfer data -->
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>


                                    <td>{{ $item->no_invoice }} </td>
                                    <td>{{ $item->supplier_name }} </td>
                                    <td>Rp. {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('j F Y') }}</td>
                                    <td>{{ $item->nota }} </td>
                                    <td>{{ \Carbon\Carbon::parse($item->deadline_invoice)->format('j F Y') }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                style="margin-right: 12px" data-toggle="modal"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>

                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#importModal{{ $item->id }}">
                                                <i class="fas fa-upload" style="margin-right: 4px"></i>Upload
                                            </button>
                                        </div>
                                    </td>

                                    <td class="project-actions">


                                        <div class="modal fade" id="importModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document"
                                                style="max-width: 60%; margin: 1.75rem auto;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="importModalLabel">Upload Proof of
                                                            Payment </h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('invoice-supplier.upload-proof', $item->id) }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body" style="width: 100%; height: auto;">
                                                            {{-- <label># No. Invoice : <span
                                                                    id="invoice_number">{{ $item->no_invoice }}</span></label> --}}
                                                            <div class="file-upload"
                                                                style=" display: flex; justify-content: center; align-items: center;">
                                                                <div class="form-group row">
                                                                    <div class="col-md-6">

                                                                        <label for="no_invoice"># No. Invoice :
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <input name="no_invoice" type="text"
                                                                                class="form-control" id="no_invoice"
                                                                                placeholder="" autocomplete="off"
                                                                                value="{{ $item->no_invoice }}"
                                                                                required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="name">Supplier
                                                                            Name<span></span>:</label>
                                                                        <div class="input-group">
                                                                            <input name="supplier_name" type="text"
                                                                                class="form-control" id="supplier_name"
                                                                                placeholder="" autocomplete="off"
                                                                                value="{{ $item->supplier_name }}"
                                                                                required>
                                                                        </div>
                                                                        @if ($errors->any())
                                                                            <p style="color: red">
                                                                                {{ $errors->first('name') }}</p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="nota">Nota<span></span>:</label>
                                                                        <div class="input-group">
                                                                            <input name="nota" type="text"
                                                                                class="form-control" id="nota"
                                                                                placeholder="" autocomplete="off"
                                                                                value="{{ $item->nota }}" required>
                                                                        </div>
                                                                        @if ($errors->any())
                                                                            <p style="color: red">
                                                                                {{ $errors->first('nota') }}</p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-md-6 mt-3">
                                                                        <label for="description">Description<span
                                                                                style="color: red">*</span>:</label>
                                                                        <textarea autocomplete="off" name="description" class="form-control" id="description"
                                                                            placeholder="Enter description">{{ old('description') }}</textarea>
                                                                        @if ($errors->any())
                                                                            <p style="color: red">
                                                                                {{ $errors->first('description') }}</p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-md-6 mt-3">
                                                                        <label for="payment_status">Payment Status<span
                                                                                style="color: red">*</span>:</label>
                                                                        <select name="payment_status" class="form-control"
                                                                            id="payment_status">
                                                                            <option value="Paid">Paid</option>
                                                                            <option value="Not Yet">Not Yet</option>
                                                                        </select>
                                                                        @if ($errors->any())
                                                                            <p style="color: red">
                                                                                {{ $errors->first('payment_status') }}</p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-md-12 mt-3">
                                                                        <label for="upload_image">Upload Image<span
                                                                                style="color: red">*</span>:</label>
                                                                        <div class="image-upload-wrap">
                                                                            <input type="file" name="image_path"
                                                                                class="file-upload-input"
                                                                                onchange="readURL(this);"
                                                                                accept="image/*">
                                                                            <div class="drag-text">
                                                                                <h3>Drag and drop a file or select add Image
                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="file-upload-content" style="display:none;">
                                                                    <img class="file-upload-image" src="#"
                                                                        alt="your image"
                                                                        style="max-width: 100%; max-height: 100%;" />
                                                                    <div class="image-title-wrap"
                                                                        style="display: flex; justify-content: space-between; align-items: center;">
                                                                        <button type="button" onclick="removeUpload()"
                                                                            class="remove-image"
                                                                            style="margin-right: 10px">
                                                                            <i class="fa-solid fa-trash fa-2xl"
                                                                                style="margin-bottom: 1em;"></i> <br>
                                                                            Remove
                                                                            <span class="image-title">Image</span>
                                                                        </button>
                                                                        <button type="submit" role="button"
                                                                            class="upload-image">
                                                                            <i class="fa-solid fa-cloud-arrow-up fa-2xl fa-bounce"
                                                                                style="margin-bottom: 1em;"></i> <br> Post
                                                                            <span class="image-title">Image</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Upload</button>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>



                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination with adjusted layout -->
                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div class="mb-3">
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of
                            {{ $data->total() }} results
                        </div>
                        <div>
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- SweetAlert --}}
    @if (session('success'))
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            swal({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK",
            });
        </script>
    @endif


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("body").addEventListener("click", function(event) {
                if (event.target.id === "download-template") {
                    event.preventDefault();
                    console.log("terklik");
                    window.location.href = 'journal/journal/templates/import';
                }
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.querySelector(".image-upload-wrap").style.display = 'none';
                        document.querySelector(".file-upload-image").src = e.target.result;
                        document.querySelector(".file-upload-content").style.display = 'block';
                        document.querySelector(".image-title").innerHTML = input.files[0].name;
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    removeUpload();
                }
            }

            window.readURL = readURL; // Ensure readURL is globally available

            function removeUpload() {
                document.querySelector(".file-upload-input").value = '';
                document.querySelector(".file-upload-content").style.display = 'none';
                document.querySelector(".image-upload-wrap").style.display = 'block';
            }

            window.removeUpload = removeUpload; // Ensure removeUpload is globally available

            document.querySelector(".image-upload-wrap").addEventListener("dragover", function() {
                document.querySelector(".image-upload-wrap").classList.add("image-dropping");
            });

            document.querySelector(".image-upload-wrap").addEventListener("dragleave", function() {
                document.querySelector(".image-upload-wrap").classList.remove("image-dropping");
            });
        });
    </script>

@endsection