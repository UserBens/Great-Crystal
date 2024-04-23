@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/books')}}">Books</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail book {{$data->name}}</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row d-flex justify-content-center">

          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Book name</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">NISB</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->nisb ? $data->nisb : 'unknown'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Grade</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->grade->name}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Class</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->grade->class}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Amount:</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{number_format($data->amount, 0, ',', '.')}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Created</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{date('d/m/Y', strtotime($data->created_at))}}
                     </p>
                  </div>
                </div>
                <hr>
              </div>
            </div>

            
          </div>
            <a role="button" class="col-lg-8 btn btn-danger btn-lg" href="javascript:void(0)" id="delete-book" data-id="{{ $data->id }}" data-name="{{ $data->name }}"> 
                {{-- <i class="fas fa-trash"> --}}
                Delete
            </a>
        </div>
      </div>
    </section>

    @include('components.super.delete-book')
    
    @if(session('after_create')) 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>

   var Toast = Swal.mixin({
         toast: true,
         position: 'top-end',
         showConfirmButton: false,
         timer: 3000
   });

   setTimeout(() => {
      Toast.fire({
         icon: 'success',
         title: 'Data has been saved !!!',
   });
   }, 1500);


   </script>
        
    @endif

@endsection