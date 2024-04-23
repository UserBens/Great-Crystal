@extends('layouts.admin.master')


@section('content')

<div class="container-fluid mt-5">

    <div class="row d-flex justify-content-center">

        @if($errors->any())
                                        
        <div class="col-10 alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            {{$errors->first('bill')}}
        </div>
        
        @endif
    
    </div>


    <div class="mb-5 d-flex justify-content-center ">
        <div class="text-center">
            <h3 class="text-center display-4">{{$student->name}}</h3>
            <h3 class="text-center display-4">{{$student->grade->name . ' - ' . $student->grade->class}}</h3>
        </div>
    </div>

    <form action="{{route('action.add.book', $student->id)}}" method="POST">
      @csrf
      @method('POST')
    <div class="row d-flex justify-content-center">


       <div class="card card-dark col-11">
            <div class="card-header">
                <h3 class="card-title">Books ( {{$student->grade->name . ' - ' . $student->grade->class}} )</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">
                                    Unique ID
                                </th>
                                <th class="text-center">
                                    Book Name
                                </th>
                                <th class="text-center">
                                    Grade
                                </th>
                                <th class="text-center">
                                    Amount
                                </th>
                                <th class="text-center">
                                    Label
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(sizeof($data)>0)

                            @foreach ($data as $el)
                            <tr>
                                <td class="text-center">
                                    #{{$el->id}}
                                </td>
                                <td class="text-center">
                                    <a>
                                        {{$el->name}}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{$student->grade->name . ' - ' . $student->grade->class}}
                                </td>
                                <td class="text-center">
                                    IDR {{number_format($el->amount, 0, ',', '.')}}
                                </td>
                                <td class="text-center">
                                    <div class="custom-control form-control-lg custom-checkbox">
                                        <input type="checkbox" value="{{$el->id}}" id="defaultCheck1" name="{{'book-'.$loop->index}}">
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                            @else

                                <tr class="text-center">
                                    <td colspan="5">

                                        <h1>You haven't books data for {{$student->grade->name . ' - ' . $student->grade->class}} yet</h1>
                                    </td>

                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
               </div>
               
            </div>
            <div class="d-flex justify-content-center row">
               <input type="submit" role="button" class="btn btn-info btn-lg m-5 col-10" value="Add book for {{$student->name}}">
            </div>
         </form>

</div>

@endsection
