@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/spp-students')}}">spp-students</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Payment {{$data->name}}</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">

          <div class="col-lg-8">
            <div class="card card-dark mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Type</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->spp_student->type}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Student</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{-- @if($data->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
                        @endif --}}
                        {{$data->name}}
                     </p>
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
                    <p class="mb-0">Created</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{date('d/m/Y', strtotime($data->spp_student->created_at))}}
                     </p>
                  </div>
                </div>
                <hr>
              </div>
            </div>

            
          </div>

          <div class="col-4">
            <div class="card mb-4 p-4">
               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>
                     <tr>
                        <td align="left">
                           Amount:
                        </td>
                        <td align="right">
                           {{number_format($data->spp_student->amount, 0, ',', '.')}}
                        </td>
                        
                     </tr>

                     
                     <tr>
                        <td align="left">
                           Discount:
                        </td>
                        <td align="right">
                           {{$data->spp_student->discount? $data->spp_student->discount : 0}}%
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
               <hr>

               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>
                     @php
                        $total = $data->spp_student->discount ? $data->spp_student->amount - $data->spp_student->amount * $data->spp_student->discount/100 : $data->spp_student->amount;   
                     @endphp
                     <tr>
                        <td align="left">
                           Total:
                        </td>
                        <td align="right">
                           IDR. {{number_format($total, 0, ',', '.')}}
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
            </div>

            <a role="button" href="/admin/spp-students/edit/{{$data->unique_id}}" class="btn btn-primary w-100">Edit</a>
          </div>
        </div>
      </div>
    </section>
   @if(session('after_create_spp_student')) 
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
        
   @if(session('after_update_spp_student')) 
      
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

@endsection