@extends('layouts.admin.master')


@section('content')



<div class="container-fluid mt-5">


    <div class="mb-5 d-flex justify-content-center ">
        <div class="text-center">
            <h1 class="text-muted">Promotions</h1>
            <h3 class="text-muted">{{$grade->name . ' - ' . $grade->class}}</h3>
        </div>
    </div>

    <form action="{{route('actionPromotion')}}" method="POST">
      @csrf
      @method('PUT')
    <div class="row d-flex justify-content-center">


       <div class="card col-10">
            <div class="card-header">
                <h3 class="card-title">Student</h3>

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
                                <th style="width: 1%">
                                    #
                                </th>
                                <th>
                                    Student Name
                                </th>
                                <th>
                                    Unique ID
                                </th>
                                <th>
                                    Place of birth
                                </th>
                                <th>
                                    Gender
                                </th>
                                <th class="text-center">
                                    Status
                                </th>
                                <th class="text-center">
                                    Promote
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(sizeof($data)>0)

                            @foreach ($data as $el)
                            <tr>
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td>
                                    <a>
                                        {{$el->name}}
                                    </a>
                                    <br />
                                    <small>
                                        @php

                                        $birthDate = explode("-", $el->date_birth);

                                        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1],
                                        $birthDate[0]))) > date("md")
                                        ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                                        @endphp
                                        {{$age}} years old
                                    </small>
                                </td>
                                <td>
                                    {{$el->unique_id}}
                                </td>
                                <td>
                                    {{$el->place_birth}}
                                </td>
                                <td>
                                    {{$el->gender}}
                                </td>
                                <td class="project-state">
                                    @if($el->is_active)
                                    <h1 class="badge badge-success">Active</h1>
                                    @else
                                    <h1 class="badge badge-danger">Inactive</h1>
                                    @endif
                                </td>
                                <td class="text-center">
                                @if (sizeof($el->bill)<=0)
                                    
                                    <input class="form-check-input" type="checkbox" value="{{$el->id}}" id="defaultCheck1" name="{{'promote'.$loop->index+1}}">
                                @else
                                    
                                    <p class="text-muted">There are unpaid bills</p>
                                @endif
                                </td>
                            </tr>



                            @endforeach
                            @else
                            <tr>
                                <td colspan="8 text-center">

                                    <h1>You haven't added student data yet</h1>
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
               <input type="submit" role="button" class="btn btn-info m-5 col-9" value="Promote now">
            </div>
         </form>

</div>

@if($errors->any())
                                       <p style="color: red">{{$errors->first('checklist')}}</p>
@endif

@endsection
