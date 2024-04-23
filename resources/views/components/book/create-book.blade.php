@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action="{{route('action.create.book')}}">
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create book</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="name">Book name<span style="color: red">*</span> :</label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter book name" value="{{old('name')}}" autocomplete="off" required>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
    
                                        <label for="nisb">NISB :</label>
                                        
                                            <input name="nisb" type="text" class="form-control" id="nisb"
                                                placeholder="Enter nisb"
                                                value="{{ old('nisb') }}"
                                                autocomplete="off">
    
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nisb')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="grade_id">Grades<span style="color: red">*</span> :</label>
                                        <select name="grade_id" class="form-control">

                                            <option class="text-center" value="" disabled {{old('grade_id') ? '' : 'selected'}}> ------ SELECT GRADE -------</option>

                                            @foreach ($grade as $el)

                                                <option class="text-center" value="{{$el->id}}" {{old('grade_id') == $el->id ? 'selected' : ''}}>{{$el->name . ' - ' . $el->class}}</option>
                                                
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
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount"
                                                value="{{old('amount') ? number_format(old('amount'), 0, ',', '.') : ''}}" required>
                                        </div>
    
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" value="Create book" class="btn btn-success center col-11 m-3">
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
