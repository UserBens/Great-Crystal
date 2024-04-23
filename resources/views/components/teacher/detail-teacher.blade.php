@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/list')}}">Teacher</a></li>
                <li class="breadcrumb-item active" aria-current="page">Teacher Profile</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-body text-center">
                <img src="{{asset('images')}}/user_{{strtolower($data->gender)}}.png" alt="avatar"
                  class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3">{{$data->name}}</h5>
                <p class="text-muted mb-1">
                                    
                     {{(date("md", date("U", mktime(0, 0, 0, 
                     explode("-", $data->date_birth)[2], 
                     explode("-", $data->date_birth)[1], 
                     explode("-", $data->date_birth)[0]))) > date("md") 
                     ? ((date("Y")-explode("-", $data->date_birth)[0])-1)
                     :(date("Y")-explode("-", $data->date_birth)[0]))
                     }} years old

                </p>
                <p class="text-muted mb-4">{{$data->home_address}}</p>
                {{-- <div class="d-flex justify-content-center mb-2">
                  <button type="button" class="btn btn-primary">Follow</button>
                  <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                </div> --}}
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Fullname</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Unique ID</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->unique_id}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">NIK or Passport</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->nik}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Status</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        @if($data->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
                        @endif
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Gender</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->gender}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Religion</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->religion}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Place Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->place_birth}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Date Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date("d/m/Y", strtotime($data->date_birth))}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Nationality</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->nationality}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Last Education</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->last_education}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Major</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->major}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Email</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->email}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Mobilephone</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->handphone}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Temporary Address</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->temporary_address}}</p>
                  </div>
                </div>
              </div>
            </div>

            
          </div>
        </div>
      </div>
    </section>
    @if(session('after_create_teacher')) 
    
      {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    
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
           title: 'Successfully registered the teacher in the database !!!',
        });
        }, 1500);


      </script>
        
    @endif 


    @if (session('after_update_teacher'))
      
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
         title: 'Successfully updated the teacher in the database !!!',
      });
      }, 1500);


    </script>

    @endif

@endsection