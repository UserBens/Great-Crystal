@extends('layouts.admin.master')

@section('content')
<div class="row d-flex justify-content-center">

   <div class="register-box m-5">
      <div class="register-logo">
         <a><b>Change My</b> Password</a>
      </div>
      
      <div class="card">
         <div class="card-body register-card-body">
            <p class="login-box-msg">Change password</p>
 
      <form action="/admin/user/change-password" method="post" enctype="multipart/form-data">
         @csrf
         @method('PUT')

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