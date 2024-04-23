@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action="{{route('create.installment', $data->id)}}">
                        @csrf
                        @method('POST')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create intallment paket for {{$data->student->name}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-10 offset-1">
                                       
                                       <label for="amount">Amount<span style="color: red">*</span></label>
                                       <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input name="amount" type="text" class="form-control" id="amount"
                                            placeholder="Enter amount" value="{{number_format($data->amount, 0, ',' , '.')}}" readonly>
                                        <div class="input-group-append">
                                           <span class="input-group-text">.00</span>
                                          </div>
                                       </div>
                          
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-10 offset-1">
                                       
                                       <label for="installment">Installment<span style="color: red">*</span></label>
                                       <div class="input-group">
                                        <input name="installment" type="number" class="form-control" id="installment"
                                            placeholder="Enter installment ( cicilan )" value="" min="2" max="12" value="{{old('installment')}}" required>
                                        <div class="input-group-append">
                                           <span class="input-group-text">Month</span>
                                          </div>
                                       </div>
                          
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('installment')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-10 m-3">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
