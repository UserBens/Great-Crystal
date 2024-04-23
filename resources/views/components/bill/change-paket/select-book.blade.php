@extends('layouts.admin.master')


@section('content')

<div class="container-fluid mt-5">

    <div class="row d-flex justify-content-center">

        @if($errors->any())
                                        
        <div class="col-10 alert alert-warning alert-dismissible zindex-1">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            {{$errors->first('bill')}}
        </div>
        
        @endif
    
    </div>


    <div class="mb-5 d-flex justify-content-center ">
        <div class="text-center">
            <h3 class="text-center display-4">Change Bill</h3>
            <h3 class="text-center display-4">{{$student->name}}</h3>
        </div>
    </div>

    <form action="{{route('action.edit.paket', ['bill_id' => $bill_id, 'student_id' => $student->id])}}" method="POST">
      @csrf
      @method('PUT')
    <div class="row d-flex justify-content-center">


       <div class="card card-secondary col-10">
            <div class="card-header">
                <h3 class="card-title">Books ( {{ $student->grade->name . ' ' . $student->grade->class }} )</h3>


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
                                Code
                            </th>
                            <th class="text-center">
                                Book Name
                            </th>
                            <th class="text-center">
                                Grade
                            </th>
                            <th align="text-left">
                                Amount
                            </th>
                            <th class="text-center" style="width: 5%">
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
                                <td class="text-left">
                                    IDR {{number_format($el->amount, 0, ',', '.')}}
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input-lg m-auto" type="checkbox" value="{{$el->id}}" id="defaultCheck1" name="{{'book-'.$loop->index}}">
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="5" class="text-center">
                                    <h1>You haven't books data for {{$student->grade->name . ' - ' . $student->grade->class}} yet</h1>
                                </td>
                                
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->

                
            </div>
            
            @if($uniform)
            
            <div class="card card-dark col-10 mt-5">
            <div class="card-header w-100">
                <h3 class="card-title">Uniform ( {{ $student->grade->name . ' ' . $student->grade->class }} )</h3>
                
                <div class="card-tools">                        
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%">
                                
                            </th>
                            <th class="text-center">
                                
                            </th>
                            <th class="text-center">
                                Grade
                            </th>
                            <th align="text-left">
                                Amount
                            </th>
                            <th class="text-center" style="width: 5%">
                                    Label
                                </th>
                            </tr>
                        </thead>
                        <tbody>


                            {{-- @foreach ($data as $el) --}}
                            <tr>
                                <td class="text-center">
                                    
                                </td>
                                <td class="text-center">
                                    <a>
                                        {{$uniform->type}}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{$student->grade->name . ' - ' . $student->grade->class}}
                                </td>
                                <td class="text-left">
                                    IDR {{number_format($uniform->amount, 0, ',', '.')}}
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input-lg m-auto" type="checkbox" value="{{$uniform->id}}" id="defaultCheck1" name="uniform">
                                </td>
                            </tr>
                            
                            {{-- @endforeach --}}
                            
                        </tbody>

                    </table>
                </div>
                <!-- /.card-body -->
               </div>

            @endif
            
            </div>
            <div class="d-flex justify-content-center row">
                <input type="submit" role="button" class="btn btn-success btn-lg m-5 col-10" value="Update paket for {{$student->name}}">
            </div>
        </form>
        
</div>

@endsection
