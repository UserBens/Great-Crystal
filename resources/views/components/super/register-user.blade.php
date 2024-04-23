@extends('layouts.admin.master')


@section('content')

<div class="row d-flex justify-content-center">

   <div class="register-box m-5">
      <div class="register-logo">
         <a><b>Super Admin</b> Access</a>
      </div>
      
      <div class="card">
         <div class="card-body register-card-body">
            <p class="login-box-msg">Register a new user</p>
 
      <form action="/admin/user/register-action" method="post" enctype="multipart/form-data">
         @csrf
         @method('post')
         <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Username" name="username" value="{{old('username')}}">
           <div class="input-group-append">
              <div class="input-group-text">
               <span class="fas fa-user"></span>
            </div>
         </div>
      </div>
      @if($errors->any())
         <p style="color: red">{{$errors->first('username')}}</p>
      @endif   
      <div class="input-group mb-3">
           <input type="password" class="form-control" placeholder="Password" name="password" value="{{old('password')}}">
           <div class="input-group-append">
              <div class="input-group-text">
               <span class="fas fa-lock"></span>
            </div>
         </div>
      </div>
      @if($errors->any())
         <p style="color: red">{{$errors->first('password')}}</p>
      @endif
         <div class="input-group mb-3">
           <input value="{{old('reinputPassword')}}" type="password" class="form-control" placeholder="Retype password" name="reinputPassword">
           <div class="input-group-append">
              <div class="input-group-text">
               <span class="fas fa-lock"></span>
            </div>
         </div>
      </div>
      @if($errors->any())
         <p style="color: red">{{$errors->first('reinputPassword')}}</p>
      @endif

      @php
         $allRole = ['superadmin', 'admin', 'accounting'];
      @endphp
      <div class="input-group mb-3">
         <select name="role" class="form-control form-control">
            <option selected {{old('role')? '' : 'disabled'}} value="{{old('role')}}"> {{old('role')? (old('role') == 'superadmin' ? 'Super Admin' : ucwords(old('role'))) : '--- SELECT ROLE ---'}}</option>

            @foreach ($allRole as $el)
               @if (old('role'))

                  @if (old('role') != $el)
                  <option value="{{$el}}">{{$el == 'superadmin' ? 'Super Admin' : ucwords($el)}}</option>
                  @endif
                  
                  @else
                  <option value="{{$el}}">{{$el == 'superadmin' ? 'Super Admin' : ucwords($el)}}</option>

               @endif
            @endforeach
          </select>
          @if($errors->any())
            <p style="color: red">{{$errors->first('role')}}</p>
         @endif
        <div class="input-group-append">
           <div class="input-group-text">
            <span class="fa-regular fa-address-card"></span>
         </div>
      </div>
   </div>
         <div class="row">
           <div class="col-12">
             <div class="icheck-primary">
               <input type="checkbox" id="agreeTerms" name="terms" value="term" required>
               <label for="agreeTerms">
                  I agree to the terms
               </label>
             </div>
           </div>
           <!-- /.col -->
         </div>

         
           <button type="submit" class="mt-3 btn btn-primary btn-block">Register</button>
   
         <!-- /.col -->
       </form>
   </div>
   <!-- /.form-box -->
   </div><!-- /.card -->
</div>
<!-- /.register-box -->
</div>
@endsection