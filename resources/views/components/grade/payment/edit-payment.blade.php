@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('edit.payment-grades', $data->id)}}>
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit {{$data->type}} static for {{$data->grade->name}}
                                    {{$data->grade->class}}</h3>
                            </div>

                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                 <div class="col-md-12">

                                    
                                    <label for="amount">Amount<span style="color: red">*</span></label> <br>
                                    @php
                                    $value = old('amount')? old('amount') : $data->amount;
                                    @endphp

                                    <div class="input-group">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text">Rp.</span>
                                        </div>

                                        <input name="amount" type="text" class="form-control" id="amount"
                                            placeholder="Enter amount" value="{{number_format($value, 0, ',', '.')}}"
                                            required>
                                        <div class="input-group-append">
                                           <span class="input-group-text">.00</span>
                                          </div>
                                       </div>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('amount')}}</p>
                                       @endif
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <input role="button" value="Update" type="submit"
                                        class="btn btn-success center col-11 m-3">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
