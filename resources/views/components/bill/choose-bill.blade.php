@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">
           <h2 class="text-center display-4">Choose Student</h2>
           <form class="mt-5" action="/admin/bills/create">
               <div class="row">
                   <div class="col-md-10 offset-md-1">
                       <div class="row">
                           <div class="col-6">
                               <div class="form-group">
                                   <label>Select Grade:</label>
                                   @php
                                       
                                       $selectedGradeId = $form && $form->grade_id? $form->grade_id : 'all';

                                    @endphp
                                    <select name="grade_id" class="form-control text-center" >
                                        <option {{$selectedGradeId === 'all' ? 'selected' : ''}} value="all">---- SELECT ALL ----</option>
                                        @foreach ($grade as $el)
                                        
                                        <option {{$selectedGradeId == $el->id ? 'selected' : ''}} value="{{$el->id}}">{{$el->name . ' ' . $el->class}}</option>
                                            
                                        @endforeach
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-3">
                               <div class="form-group">

                                    @php
                                       
                                       $selected = $form->sort ? $form->sort : 'desc';

                                    @endphp

                                 <label>Sort order: <span style="color: red"></span></label>
                                 <select name="sort" class="form-control">
                                     <option value="desc" {{$selected === 'desc' ? 'selected' : ''}}>Descending</option>
                                     <option value="asc" {{$selected === 'asc' ? 'selected' : ''}}>Ascending</option>
                                 </select>                              
                               </div>
                           </div>
                           <div class="col-3">
                               <div class="form-group">

                                 @php

                                    $selected = $form->order? $form->order : 'created_at';

                                 @endphp

                                   <label>Sort by:</label>
                                    <select name="order" class="form-control">
                                          <option {{$selected === 'created_at'? 'selected' : ''}} value="created_at">Register</option>
                                          <option {{$selected === 'name'? 'selected' : ''}} value="name">Name</option>
                                          <option {{$selected === 'grade_id'? 'selected' : ''}} value="grade_id">Grade</option>
                                    </select>
                                  
                               </div>
                           </div>
                           </div>
                       <div class="form-group">
                           <div class="input-group input-group-lg">
                               <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg" placeholder="Type name of students here">
                               <div class="input-group-append">
                                   <button type="submit" class="btn btn-lg btn-default">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </form >

            <div class="card card-footer mt-5">
            <div class="card-header">
              <h3 class="card-title">Choose Student</h3>
    
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <table class="table table-bordered projects">
                  <thead>
                     <tr>
                          <th style="width: 10%">
                              #
                          </th>
                          <th>
                              Student Name
                          </th>
                          <th style="width: 10%">
                              Gender
                          </th>
                          <th style="width: 15%">
                              Grade
                          </th>
                          <th style="width: 8%">
                              Class
                          </th>
                          <th style="width: 25%">
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                     @foreach ($data as $el)
                     <tr id={{'index_student_' . $el->id}}>
                        <td>
                           {{ $loop->index + 1 }}
                          </td>
                          <td>
                              <a>
                                 {{$el->name}}
                              </a>
                              <br/>
                              <small>
                                 @php
                                    
                                    $birthDate = explode("-", $el->date_birth);

                                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") 
                                    ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                                 @endphp
                                 {{$age}} years old
                              </small>
                           </td>
                           <td>
                              {{$el->gender}}
                           </td>
                           <td>
                              <a>
                                 {{$el->grade->name}}
                              </a>
                        </td>
                        <td>
                           {{$el->grade->class}}
                        </td>
                          <td class="project-actions text-center toastsDefaultSuccess">
                           <a class="btn btn-success" href="/admin/bills/create-bills/{{$el->unique_id}}">
                            <i class="fa-solid fa-square-plus mr-2"></i>
                                       Create bill
                           </a>
                        </td>
                     </tr>
                     
                     @endforeach
                  </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            </div>
         </div>
         
         @include('components.super.delete-student')
@endsection