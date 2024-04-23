@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('actionRegisterTeacher')}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Teacher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="name">Fullname<span style="color: red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter name" value="{{old('name')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nik">NIK/Passport<span style="color: red">*</span></label>
                                        <input name="nik" type="text" class="form-control" id="nik"
                                            placeholder="Enter nik/passport" value="{{old('nik')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nik')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Gender<span style="color: red">*</span></label>
                                            <select name="gender" class="form-control" required>
                                                @php
                                                $arrGender = array('Female', 'Male');
                                                @endphp

                                                <option selected {{ old('gender')? '' : 'disabled'}}>
                                                    {{ old('gender')? old('gender') : '--- SELECT GENDER ---'}}
                                                </option>

                                                @if (old('gender'))

                                                @foreach($arrGender as $gender)

                                                @if (old('gender') !== $gender)
                                                <option>{{$gender}}</option>
                                                @endif

                                                @endforeach
                                                @else
                                                <option>Male</option>
                                                <option>Female</option>
                                                @endif
                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('gender')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="place_birth">Place of Birth<span style="color: red">*</span></label>
                                        <input name="place_birth" type="text" class="form-control" id="place_birth"
                                            placeholder="Enter city" value="{{old('place_birth')}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('place_birth')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">

                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="date_birth" type="text"
                                                class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdate" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                value="{{old('date_birth')}}"
                                                required />

                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                 <div class="col-md-6">
                                     <label for="nationality">Nationality<span style="color: red">*</span></label>
                                     <input name="nationality" type="text" class="form-control" id="nationality"
                                         placeholder="Enter country" value="{{old('nationality')}}" required>
                                     @if($errors->any())
                                     <p style="color: red">{{$errors->first('nationality')}}</p>
                                     @endif
                                 </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label>Religion<span style="color: red">*</span></label>
                                      <select name="religion" class="form-control" required>

                                            @php
                                            $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                            Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                            @endphp

                                            <option selected {{ old('religion')? '' : 'disabled'}}>
                                                  {{ old('religion')? old('religion') : '--- SELECT RELIGION ---'}}
                                            </option>

                                            @if (old('religion'))

                                            @foreach($arrReligion as $religion)

                                            @if (old('religion') !== $religion)
                                            <option>{{$religion}}</option>
                                            @endif

                                            @endforeach
                                            @else
                                            @foreach($arrReligion as $religion)
                                            <option>{{$religion}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('religion')}}</p>
                                        @endif
                                    </div>
                                 </div>
                              </div>

                                <div class="form-group row">
                                    
                                   <div class="col-md-6">
                                         <label>Last Education<span style="color: red">*</span></label>
                                         <select name="last_education" class="form-control" required>

                                               @php
                                               $arrLast_education = array('Senior High School', 'Vocational School', 'Bachelor`s Degree', 'Master Degree', 'Doctoral Degree ', 'Others');
                                               @endphp

                                               <option selected {{ old('last_education')? '' : 'disabled'}}>
                                                     {{ old('last_education')? old('last_education') : '--- SELECT LAST EDUCATION ---'}}
                                               </option>

                                               @if (old('last_education'))

                                               @foreach($arrLast_education as $last_education)

                                               @if (old('last_education') !== $last_education)
                                               <option>{{$last_education}}</option>
                                               @endif

                                               @endforeach
                                               @else
                                               @foreach($arrLast_education as $last_education)
                                               <option>{{$last_education}}</option>
                                               @endforeach
                                               @endif
                                           </select>
                                           @if($errors->any())
                                           <p style="color: red">{{$errors->first('last_education')}}</p>
                                           @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                       <label for="major">Major<span style="color: red">*</span></label>
                                       <input name="major" type="text" class="form-control" id="major"
                                           placeholder="Enter teacher major" value="{{old('major')}}" required>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('major')}}</p>
                                       @endif
                                   </div>
                                 </div>

                              <div class="form-group row">
                                    
                                    <div class="col-md-6">
                                     <label for="handphone">Mobilephone<span style="color: red">*</span></label>
                                     <input name="handphone" type="text" class="form-control"
                                     id="handphone" placeholder="Enter mobilephone" required value="{{old('handphone')}}">
 
                                     @if($errors->any())
                                                 <p style="color: red">{{$errors->first('handphone')}}</p>
                                     @endif
                                  </div>
                                  
                                  <div class="col-md-6">
                                       <label for="email">Email<span style="color: red">*</span></label>
                                     <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input name="email" type="email" class="form-control" placeholder="Enter father's email" value="{{old('email')}}" required>
 
                                        @if($errors->any())
                                                 <p style="color: red">{{$errors->first('email')}}</p>
                                        @endif
                                      </div>
                                    </div>
                                </div>

                                 

                                 <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="home_address">Home Address<span style="color: red">*</span></label>

                                            <textarea required name="home_address" class="form-control" id="" cols="10" rows="3">{{old('home_address')}}</textarea>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('home_address')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="temporary_address">Temporary Address<span style="color: red">*</span></label>

                                            <textarea required name="temporary_address" class="form-control" id="" cols="10" rows="3">{{old('temporary_address')}}</textarea>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('temporary_address')}}</p>
                                        @endif
                                    </div>
                                 </div>
                              </div>
                              
                        </div>
                        <!-- /.card-body students -->


                        <!-- /.card-body Brother or sisters -->

                        <div class="d-flex justify-content-center my-5">
                            <button type="submit" class="col-11 btn btn-success">Submit</button>
                        </div>
                    </form>

                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
