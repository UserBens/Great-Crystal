@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Studen Bills</h2>

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Not found on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no grade data found -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No grades available!</h3>
                    <div class="btn-group">
                        <a type="button" href="{{ route('grades.index') }}" class="btn btn-primary btn-sm mt-3">
                            <i class="fas fa-graduation-cap"></i> Go to Grades
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Display Cash or Bank data in a table -->
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">Student Bills List</h3>
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
                                <th style="width: 10%">#</th>
                                <th style="width: 25%">Grades</th>
                                <th style="width: 20%">Total students</th>
                                <th style="width: 25%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $el)
                                <tr id={{ 'index_grade_' . $el->id }}>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a>{{ $el->name . ' - ' . $el->class }}</a>
                                    </td>
                                    <td>{{ $el->active_student_count }}</td>
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                        <a class="btn btn-primary btn" href="{{ route('reports.grade-bills', ['grade_id' => $el->id]) }}">
                                            <i class="fas fa-folder"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- Include jQuery and SweetAlert library -->
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/projects.js') }}" defer></script>

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

            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mengirim request DELETE menggunakan Ajax
                        $.ajax({
                            url: '{{ route('invoice-supplier.destroy', ['id' => ':id']) }}'
                                .replace(':id', id),
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Refresh halaman setelah menghapus
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Failed!',
                                    response.responseJSON.error ? response
                                    .responseJSON.error :
                                    'There was an error deleting the invoice supplier.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('sort-select').addEventListener('change', function() {
            let order = this.options[this.selectedIndex].getAttribute('data-order');
            document.getElementById('sort-order').value = order;
            this.form.submit();
        });
    </script>

    <script>
        document.getElementById('status-select').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('sort-select').addEventListener('change', function() {
            let order = this.options[this.selectedIndex].getAttribute('data-order');
            document.getElementById('sort-order').value = order;
            this.form.submit();
        });
    </script>


    <script>
        $('#yourModal').on('shown.bs.modal', function() {
            $('.select2').select2({
                width: '100%' // Sesuaikan lebar sesuai kebutuhan Anda
            });
        });
    </script>
@endsection