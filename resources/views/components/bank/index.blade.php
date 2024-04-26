@extends('layouts.admin.master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">

        <h2 class="text-center display-4 mb-3">Bank Search</h2>

{{-- code here       --}}

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

    {{-- search button --}}
    <script>
        // Tangani klik tombol pencarian
        document.getElementById('searchButton').addEventListener('click', function() {
            // Dapatkan nilai dari input pencarian
            var searchValue = document.getElementById('searchInput').value;

            // Redirect ke halaman pencarian dengan parameter 'search'
            window.location.href = '/admin/expenditure?search=' + searchValue;
        });
    </script>

    {{-- delete button --}}
    <script>
        // Tangani klik tombol delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let expenditureId = this.getAttribute('data-id');

                // Tampilkan modal konfirmasi penghapusan
                $('#deleteModal' + expenditureId).modal('show');

                // Hentikan tindakan default penghapusan
                return false;
            });
        });
    </script>
@endsection
