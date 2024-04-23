@extends('layouts.admin.master')


@section('content')


<div class="container-fluid mt-5">


    <div class="row">

        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-body text-center">

                    @php
                    $imageName = $data->gradeTeacher->teacher? "user_".strtolower($data->gradeTeacher->teacher->gender) : "user_unknown"
                    @endphp

                    <img src="{{asset('images')}}/{{$imageName}}.png" alt="avatar" class="rounded-circle img-fluid"
                        style="width: 100px;">
                    <h5 class="my-3">{{$data->gradeTeacher->teacher ? $data->gradeTeacher->teacher->name : 'Unknown'}}</h5>
                    <p class="text-muted mb-1">

                        Teacher class

                    </p>
                    <p class="text-muted mb-4">{{$data->gradeTeacher->name . ' - ' . $data->gradeTeacher->class}}</p>
                </div>
            </div>
            @if(sizeof($data->gradeStudent)>0)
            <div class="mt-3 row d-flex justify-content-around">
                @if (session('role') == 'superadmin')
                    <a href="{{url('/admin/grades/promotions') . '/' . $data->gradeTeacher->id}}" role="button" class="w-100 btn btn-info col-5">
                      <i class="fa-solid fa-graduation-cap fa-beat-fade" style="color: #ffffff;"></i><br>
                      Promotion
                    </a>
                
                    <a target="_blank" href="{{url('/admin/grades/pdf') . '/' . $data->gradeTeacher->id}}" role="button" class="w-100 btn btn-warning col-5">
                        <i class="fa-solid fa-file-pdf fa-bounce" style="color: #000000;"></i><br>
                        PDF
                    </a>
                @else
                    
                    <a target="_blank" href="{{url('/admin/grades/pdf') . '/' . $data->gradeTeacher->id}}" role="button" class="w-100 btn btn-warning col-11">
                        <i class="fa-solid fa-file-pdf fa-bounce" style="color: #000000;"></i>
                        PDF
                    </a>
                    
                @endif
            </div>
            @endif
        </div>


        <div class="card card-dark col-9">
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
                                Place of birth
                            </th>
                            <th>
                                Gender
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($data->gradeStudent)>0)

                        @foreach ($data->gradeStudent as $el)
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
                        </tr>



                        @endforeach
                        @else
                        <tr>
                           <td colspan="7">

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

</div>


@endsection
