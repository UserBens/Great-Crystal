@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/list')}}">Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Profile</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-body text-center">
                <img src="{{asset('images')}}/user_{{strtolower($data->student->gender)}}.png" alt="avatar"
                  class="rounded-circle img-fluid" style="width: 150px;">
                <h5 class="my-3">{{$data->student->name}}</h5>
                <p class="text-muted mb-1">
                                    
                     {{(date("md", date("U", mktime(0, 0, 0, 
                     explode("-", $data->student->date_birth)[2], 
                     explode("-", $data->student->date_birth)[1], 
                     explode("-", $data->student->date_birth)[0]))) > date("md") 
                     ? ((date("Y")-explode("-", $data->student->date_birth)[0])-1)
                     :(date("Y")-explode("-", $data->student->date_birth)[0]))
                     }} years old

                </p>
                <p class="text-muted mb-4">{{$data->student->place_birth}}</p>
                {{-- <div class="d-flex justify-content-center mb-2">
                  <button type="button" class="btn btn-primary">Follow</button>
                  <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                </div> --}}
              </div>
            </div>
            @if(sizeof($data->brother_or_sisters)>0)
            <div class="card mb-4 mb-lg-0">
              <div class="card-body p-0">
               <p style="font-size: 1.4em;" class="mb-4 m-4"><span class="text-secondary font-italic me-1">Brothers Or Sisters</span>
              </p>
               <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Grade</th>
                      <th scope="col">Age</th>
                    </tr>
                  </thead>
                  <tbody>
                     @foreach($data->brother_or_sisters as $el)
                    <tr>
                      <th scope="row">{{$loop->index + 1}}</th>
                      <td>{{$el->name}}</td>
                      <td>{{$el->grade}}</td>
                      <td style="width:10%">{{
                        
                           (date("md", date("U", mktime(0, 0, 0, 
                           explode("-", $el->date_birth)[2], 
                           explode("-", $el->date_birth)[1], 
                           explode("-", $el->date_birth)[0]))) > date("md") 
                           ? ((date("Y")-explode("-", $el->date_birth)[0])-1)
                           :(date("Y")-explode("-", $el->date_birth)[0]))
                           
                        
                        }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif
          </div>


          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Fullname</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">NISN</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->nisn? $data->student->nisn : "-"}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Status</p>
                  </div>
                  <div class="col-sm-8">
                     @if($data->student->is_active)
                           <h1 class="badge badge-success">Active</h1>
                     @else
                           <h1 class="badge badge-danger">Inactive</h1>
                     @endif
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Grade</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->grade->name}} {{$data->student->grade->class}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Gender</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->gender}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Religion</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->religion}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Place Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->place_birth}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Date Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date("d/m/Y", strtotime($data->student->date_birth))}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Nationality</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->nationality}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Id or Passport</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->id_or_passport}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Register at</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date('d/m/Y', strtotime($data->student->created_at))}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Place of issue</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->place_of_issue? $data->student->place_of_issue: '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Expiry Id or Passport</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->student->date_exp? date("d/m/Y", strtotime($data->student->date_exp)) : '-'}}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
               @foreach($data->student->relationship as $el)
               <div class="col-md-6">
                <div class="card mb-4 mb-md-0">
                  <div class="card-body">
                     <p style="font-size: 1.4em;" class="mb-4"><span class="text-secondary font-italic me-1">{{$el->relation}}'s</span>
                     </p>
                        
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Fullname</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->name}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Religion</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->religion}}</p>
                  </div>
                </div>
                <hr>
                
                <div class="row">
                  <div class="col-sm-4">
                     <p class="mb-0">Place of Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->place_birth}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Date of Birth</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date("d/m/Y", strtotime($el->date_birth))}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Nationality</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->nationality}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                    <p class="mb-0">Occupation</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->occupation? $el->occupation : '-'}}</p>
                  </div>
               </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                    <p class="mb-0">Company Name</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->compant_name? $el->compant_name : '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                    <p class="mb-0">Company Address</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->company_address? $el->company_address : '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                    <p class="mb-0">Company Number</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->phone? $el->phone : '-'}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                     <p class="mb-0">Home Address</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$el->home_address}}</p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-sm-4">
                     <p class="mb-0">Email</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">{{$el->email}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                      <p class="mb-0">Telephone</p>
                     </div>
                     <div class="col-sm-8">
                        <p class="text-muted mb-0">{{$el->telephone? $el->telephone : '-'}}</p>
                     </div>
                  </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                      <p class="mb-0">Mobilephone</p>
                     </div>
                     <div class="col-sm-8">
                        <p class="text-muted mb-0">{{$el->mobilephone? $el->mobilephone : '-'}}</p>
                     </div>
                  </div>
                  </div>
               </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @if(session('after_create_student')) 
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
            title: 'Successfully register student in the database !!!',
      });
      }, 1500);


    </script>

  @endif

  @if(session('after_update_student')) 
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
            title: 'Successfully update the students in the database !!!',
      });
      }, 1500);


    </script>
        
  @endif

  {{-- @if(session('after_create_student')) 
    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
      var notif = '{{session('after_create_student')}}'

      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
    
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: notif,
      });
      }, 1500);


    </script>
        
  @endif --}}



@endsection