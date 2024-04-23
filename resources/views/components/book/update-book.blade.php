@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action="{{route('action.update.book', $book->id)}}">
                        @csrf
                        @method('PATCH')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Update book {{$book->name}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">

                                    @php
                                        $bookName= old('name') ? old('name') : $book->name;
                                    @endphp

                                    <div class="col-md-6">
                                        <label for="name">Book name<span style="color: red">*</span> :</label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter book name" value="{{$bookName}}" required>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    @php
                                        $nisb = old('nisb') ? old('nisb') : $book->nisb;
                                    @endphp
                                    
                                    <div class="col-md-6">
                                        <label for="nisb">NISB :</label>
                                        <input name="nisb" type="text" class="form-control" id="nisb"
                                            placeholder="Enter book nisb" value="{{$nisb}}">

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nisb')}}</p>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="grade_id">Grades<span style="color: red">*</span> :</label>
                                        @php
                                            $grade_id = old('grade_id') ? old('grade_id') : $book->grade_id;
                                        @endphp
                                        <select name="grade_id" class="form-control">

                                            @foreach ($grade as $el)

                                                <option class="text-center" 
                                                {{$el->id == $grade_id ? 'selected' : ''}} 
                                                value="{{$el->id}}" >{{$el->name . ' - ' . $el->class}}</option>
                                                
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('grade_id')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
    
                                        <label for="amount">Amount<span style="color: red">*</span> :</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            @php
                                                $amount = old('amount') ? old('amount') : $book->amount
                                            @endphp
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount"
                                                value="{{number_format($amount, 0, ',', '.')}}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
    
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" value="Update book" class="btn btn-success center col-11 m-3">
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

</section>
</script>


@endsection
