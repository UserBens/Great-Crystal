@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (sizeof($data->payment_grade)<=0) <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-12 my-auto text-center">
                <h6>Payment data per grade has never been created for <b>{{$data->name}} {{$data->class}}</b>. Click the
                    button below to get started !!!</h6>
                <a role="button" href="/admin/payment-grades/{{$data->id}}/choose-type" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Create payment
                </a>
            </div>
        </div>



        @else
        <a role="button" href="/admin/payment-grades/{{$data->id}}/choose-type" class="btn btn-success mt-4">
            <i class="fa-solid fa-plus"></i>
            Create payment
        </a>
        <div class="card card-dark mt-5">
            <div class="card-header">
                <h3 class="card-title"><em>{{$data->name}} {{$data->class}}</em></h3>

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
                            <th style="width: 10%">
                                #
                            </th>
                            <th style="width: 25%">
                                Type
                            </th>
                            <th>
                                Amount
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->payment_grade as $el)
                        <tr id={{'index_payment_' . $el->id}}>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                <a>
                                    {{$el->type}}
                                </a>
                            </td>
                            <td>
                                Rp. {{number_format($el->amount, 0, ',', '.')}},00
                            </td>

                            <td class="project-actions text-right toastsDefaultSuccess">
                                <a class="btn btn-info btn"
                                    href="{{url('/admin/payment-grades/')}}/{{$el->id}}/edit">
                                    {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                <a class="btn btn-danger btn" href="javascript:void(0)" id="delete-payment"
                                    data-id="{{ $el->id }}" data-type="{{ $el->type }}">
                                    <i class="fas fa-trash">
                                    </i>
                                    Delete
                                </a>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @endif
        <!-- /.card-body -->
</div>

@if(session('after_create_payment_grade')) 
         <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
         <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
        
   @if(session('after_update_payment_grade')) 
      
         <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
         <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
               title: 'Data has been updated !!!',
         });
         }, 1500);
      
      
         </script>
        
   @endif

@include('components.grade.payment.delete-payment')

@endsection
