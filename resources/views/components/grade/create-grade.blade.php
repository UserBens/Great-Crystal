@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('actionCreateGrade')}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create grade</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                       <label for="name">Grade<span style="color: red">*</span></label>
                                       <select required name="name" class="form-control" id="name">
                                          <option selected disabled>--- SELECT GRADE ---</option>
                                          <option>Preeschool</option>
                                          <option>Primary</option>
                                          <option>Junior High School</option>
                                          <option>Junior College</option>
                                       </select>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('name')}}</p>
                                       @endif
                                    </div>
                                    <div class="col-md-6">
                                       <label for="class">Class<span style="color: red">*</span></label>
                                       <input name="class" type="text" class="form-control" id="class"
                                          placeholder="Enter class" value="{{old('class')}}" autocomplete="off" required>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('class')}}</p>
                                       @endif
                                    </div>
                                 </div>
                                 <div class="form-group row">
                                 <div class="col-md-12">
                                    <label for="teacher_id">Teacher class<span style="color: red">*</span></label>
                                    <select required name="teacher_id" class="form-control" id="teacher_id">
                                       <option selected disabled>--- SELECT TEACHER CLASS ---</option>
                                       @foreach($data as $el)
                                          <option value="{{$el->id}}">{{$el->name}}</option>
                                       @endforeach
                                    </select>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('teacher_id')}}</p>
                                       @endif
                                    </div>
                                 </div>
                                 </div>
                              
                                 <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                 </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
