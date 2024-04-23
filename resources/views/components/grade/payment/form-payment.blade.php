@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('create.payment-grades', [ 'id' => $data->id, "type" => $type])}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create {{$type}} payment static for {{$data->name}} {{$data->class}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                {{-- <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="type">Type<span style="color: red">*</span></label>
                                        <select required name="type" class="form-control" id="type">
                                            <option selected disabled>--- SELECT TYPE ---</option>
                                            <option>SPP</option>
                                            <option>Uniform</option>
                                            <option>Entrance Fee</option>
                                            <option>Book</option>
                                            <option>Other</option>
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('type')}}</p>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       
                                       <label for="amount">Amount<span style="color: red">*</span></label>
                                       <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input name="amount" type="text" class="form-control" id="amount"
                                            placeholder="Enter amount" value="{{old('amount')}}" required>
                                       </div>
                          
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
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
