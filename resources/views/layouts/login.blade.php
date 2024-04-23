<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/app.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href={{ URL::asset('style.css'); }} >
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> --}}
    
    <link rel="stylesheet" href="{{asset('template')}}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('template')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('template')}}/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('template')}}/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('template')}}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('template')}}/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('template')}}/plugins/summernote/summernote-bs4.min.css">
    <title>Login</title>
</head>

<body>
   <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>

    
    <section class="vh-100">
       <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-md-9 col-lg-6 col-xl-5">
             <img src="{{asset('/images')}}/logo-school.png"
             class="img-fluid" alt="Sample image">
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <form method="POST" action="{{ route('actionLogin') }}">
               @csrf
               <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                <p class="lead fw-normal mb-0 me-3">Sign In Admin</p>
               </div>
               
               <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0">Login</p>
              </div>
    
              <!-- Email input -->
              <div class="form-outline mb-4">
                <input type="text" id="form3Example3" class="form-control form-control-lg"
                  placeholder="Enter a valid username" name="username" autocomplete="off" value="{{old('username')}}"/>
                <label class="form-label" for="form3Example3">Username</label>
              </div>
    
              <!-- Password input -->
              <div class="form-outline mb-3">
                <input type="password" id="form3Example4" class="form-control form-control-lg"
                  placeholder="Enter password" name="password" autocomplete="off" value="{{old('password')}}"/>
                <label class="form-label" for="form3Example4">Password</label>
              </div>
    
              <div class="d-flex justify-content-between align-items-center">
                <!-- Checkbox -->
                <div class="form-check mb-0">
                  <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                  <label class="form-check-label" for="form2Example3">
                    Remember me
                  </label>
                </div>
                {{-- <a href="#!" class="text-body">Forgot password?</a> --}}
              </div>
    
              <div class="text-center text-lg-start mt-4 pt-2">
                <button type="submit" class="btn btn-primary btn-lg"
                  style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
              </div>
    
            </form>
          </div>
        </div>
      </div>
      {{-- <div
        class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
        <!-- Copyright -->
        <div class="text-white mb-3 mb-md-0">
          Copyright © 2020. All rights reserved.
        </div>
        <!-- Copyright -->
   


      </div> --}}
    </section>

  <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

  @if ($errors->any())

    @if($errors->first('invalid'))
      <script>

        Swal.fire({
            icon: "error",
            title: "Invalid username or password",
            text: "Make sure your input is correctly !!!"
      });
      
      </script>
    @endif
    
    @if($errors->first('credentials'))
      <script>

      Swal.fire({
            icon: "error",
            title: "Invalid credentials",
            text: "Make sure you login first !!!"
      });
                    
      </script>
    @endif

    @if ($errors->first('username'))
    <script>

        Swal.fire({
          icon: "error",
          title: "Username is required !!!",
        });
    
    </script>
    @elseif ($errors->first('password'))
    <script>

      Swal.fire({
        icon: "error",
        title: "Password is required !!!",
      });
  
  </script>
    @endif
  @endif


  @if(session('success.update.password'))

   <script>

   var Toast = Swal.mixin({
         toast: true,
         position: 'top-end',
         showConfirmButton: false,
         timer: 8000
   });

   setTimeout(() => {
      Toast.fire({
         icon: 'success',
         title: 'Success update password, please login again !!!',
   });
   }, 1500);


  </script>

   @endif
</body>

</html>
