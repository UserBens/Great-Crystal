@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <a type="button" href="{{url('/admin/grades/create')}}" class="btn btn-success btn mt-5 mx-2">
         <i class="fa-solid fa-user-plus"></i>
         </i>   
         Add grade
      </a>

    <div class="card card-dark mt-5">
        <div class="card-header">
            <h3 class="card-title">Grades</h3>

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
                           Grades
                        </th>
                        <th>
                           Class teacher
                        </th>
                        <th style="width: 20%">
                           Total students
                        </th>
                        <th style="width: 25%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                           <a>
                                 {{$el->name . ' - ' . $el->class}}
                           </a>
                        </td>
                        <td>
                           @if ($el->teacher)
                              
                              <a  a href="/admin/teachers/detail/{{$el->teacher->unique_id}}">
                                 {{$el->teacher->name}}
                              </a>
                           @else 
                              <h6 style="color: red;">{{'Unknown'}}</h6>
                           @endif
                        </td>
                        <td>
                           {{$el->active_student_count}}
                        </td>
                        
                        <td class="project-actions text-right toastsDefaultSuccess">
                           <a class="btn btn-primary btn"
                              href="{{url('/admin/grades') . '/' . $el->id}}">
                              <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                           <a class="btn btn-info btn"
                              href="{{url('/admin/grades') . '/edit/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                           </a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_grade')) 

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
              title: 'Successfully created new grade in the database.',
        });
        }, 1500);


      </script>

  @endif


  
  
  @if(session('after_update_grade')) 
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
            title: 'Successfully updated the grade in the database.',
      });
      }, 1500);

    
    </script>
   @endif

  @if(session('levelup'))
  
   @php
      $gradePromotion = session('levelup')
   @endphp
      <script>

      var grade = "{{ $gradePromotion }}"
     
      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
  
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully level up students from grade ' + grade,
      });
      }, 1500);

    
    </script>
   @endif

   @if(session('graduate'))
  
      @php
         $gradePromotion = session('graduate')
      @endphp

      <script>

      var grade = "{{ $gradePromotion }}"
     
      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
  
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully graduate students from grade ' + grade,
      });
      }, 1500);

    
    </script>
   @endif


@endsection
