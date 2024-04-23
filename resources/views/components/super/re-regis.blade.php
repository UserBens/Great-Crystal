@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action="{{route('action.re-regis', $data->id)}}">
                        @csrf
                        @method('PATCH')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Re-registration {{$data->name}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">

                                        @php 

                                            $selected = old('grade_id')? old('grade_id') : $data->grade_id;

                                        @endphp
                                       
                                       <label for="grade_id" >Select Level<span style="color: red">*</span></label>
                                       
                                        <select class="form-control text-center" name="grade_id" id="grade_id">
                                            @foreach ($grade as $el)
                                                <option value="{{$el->id}}" 
                                                    {{$el->id == $selected ? 'selected' : ''}}
                                                    {{$el->id == $selected ? 'disabled' : ''}}
                                                    > 
                                                    {{$el->name}} - {{$el->class}}
                                                </option>
                                            @endforeach
                                        </select>
                          
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('grade_id')}}</p>
                                        @endif
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
