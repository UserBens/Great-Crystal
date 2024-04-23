@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">
           <h2 class="text-center display-4">Student Search</h2>
           <form class="mt-5" action="/admin/list">
               <div class="row">
                   <div class="col-md-10 offset-md-1">
                       <div class="row">
                           <div class="col-4">
                               <div class="form-group">
                                   <label>Result Type:</label>
                                   @php
                                       
                                       $selectedType = $form && $form->type? $form->type : 'name';

                                    @endphp
                                    <select name="type" class="form-control" required>
                                        <option {{$selectedType === 'name' ? 'selected' : ''}} value="name">Name</option>
                                        <option {{$selectedType === 'place_birth' ? 'selected' : ''}} value="place_birth">Place Birth</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-2">
                               <div class="form-group">

                                    @php
                                       
                                       $selectedGrade = $form->grade_id ? $form->grade_id : 'all';

                                    @endphp

                                 <label>Grade : <span style="color: red"></span></label>
                                 <select name="grade_id" class="form-control text-center">

                                     <option value="all" {{$selectedGrade === 'all' ? 'selected' : ''}}>-- All Grades --</option>

                                     @foreach ($grades as $grade)
                                         <option value="{{$grade->id}}" {{$selectedGrade == $grade->id ? 'selected' : ''}}>{{$grade->name . ' - ' . $grade->class}}</option>
                                    @endforeach
                                
                                </select>                              
                               </div>
                           </div>

                           <div class="col-2">
                               <div class="form-group">

                                    @php
                                       
                                       $selectedSort = $form->sort ? $form->sort : 'desc';

                                    @endphp

                                 <label>Sort order: <span style="color: red"></span></label>
                                 <select name="sort" class="form-control">
                                     <option value="desc" {{$selectedSort === 'desc' ? 'selected' : ''}}>Descending</option>
                                     <option value="asc" {{$selectedSort === 'asc' ? 'selected' : ''}}>Ascending</option>
                                 </select>                              
                               </div>
                           </div>
                           <div class="col-2">
                               <div class="form-group">

                                 @php

                                    $selectedOrder = $form->order? $form->order : 'created_at';

                                 @endphp

                                   <label>Sort by:</label>
                                    <select name="order" class="form-control">
                                          <option {{$selectedOrder === 'created_at'? 'selected' : ''}} value="created_at">Register</option>
                                          <option {{$selectedOrder === 'name'? 'selected' : ''}} value="name">Name</option>
                                          <option {{$selectedOrder === 'grade_id'? 'selected' : ''}} value="grade_id">Grade</option>
                                          <option {{$selectedOrder === 'gender'? 'selected' : ''}} value="gender">Gender</option>
                                          <option {{$selectedOrder === 'place_birth'? 'selected' : ''}} value="place_birth">Place Birth</option>
                                          <option {{$selectedOrder === 'status'? 'selected' : ''}} value="status">Status</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-2">
                               <div class="form-group">
                                 <label>Status: <span style="color: red"></span></label>

                                 @php
                                    
                                    $selectedStatus = $form->status ? $form->status : 'active';

                                 @endphp

                                 <select name="status" class="form-control">
                                     <option  {{$selectedStatus === 'active' ? 'selected' : ''}} value="active">Active</option>
                                     <option  {{$selectedStatus === 'inactive' ? 'selected' : ''}} value="inactive">Inactive</option>
                                     <option  {{$selectedStatus === 'graduate' ? 'selected' : ''}} value="graduate">Graduate</option>
                                 </select>                              
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="input-group input-group-lg">
                               <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
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

           @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search ))
               
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>The students you are looking for does not exist !!!</h3>
                </div>
            </div>

           @elseif (sizeof($data) == 0)

           <div class="row h-100 my-5">
            <div class="col-sm-12 my-auto text-center">
                <h3>Student has never been registered. Click the
                    button below to register student's !!!</h3>
                <a role="button" href="/admin/register" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Register Student's
                </a>
            </div>
        </div>

           @else
               
           

            <div class="card card-dark mt-5">
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
                          <th style="width: 20%">
                              Student Name
                          </th>
                          <th>
                              Place of birth
                          </th>
                          <th style="width: 10%">
                              Gender
                          </th>
                          <th>
                              Grade
                          </th>
                          <th style="width: 8%">
                              Class
                          </th>
                          <th style="width: 8%" class="text-center">
                              Status
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
                              {{$el->place_birth}}
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
                        <td class="project-state">
                        @if ($el->is_graduate)
                            

                            <h1 class="badge badge-info">Graduated</h1>

                        @else
                            
                            @if($el->is_active)
                            <h1 class="badge badge-success">Active</h1>
                            @else
                            <h1 class="badge badge-danger">Inactive</h1>
                            @endif

                        @endif
                        </td>
                            <td class="project-actions text-right toastsDefaultSuccess">
                             <a class="btn btn-primary {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="detail/{{$el->unique_id}}">
                                <i class="fas fa-folder">
                                </i>
                                View
                            </a>
                    @if($el->is_active)
                           <a class="btn btn-info {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="update/{{$el->unique_id}}">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                           </a>
                           @endif
                           @if(session('role') == 'superadmin' && $el->is_active)
                              <a href="javascript:void(0)" id="delete-student" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-danger btn-sm">
                                 <i class="fas fa fa-ban">
                                 </i>
                                 Deactive
                              </a>
                            @elseif ($el->is_graduate && (sizeof($grades) > $el->grade_id))
                                <a href="/admin/student/re-registration/{{$el->unique_id}}" class="btn btn-dark btn-sm">
                                    <i class="fas fa fa-register">
                                    </i>
                                    Re-registration
                                </a>
                            @elseif (session('role') == 'superadmin' && !$el->is_graduate )
                                    <a href="javascript:void(0)" id="active-student" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-success btn-sm">
                                        <i class="fas fa fa-register">
                                        </i>
                                        Activate
                            </a>
                        @endif
                        </td>
                     </tr>
                     
                     @endforeach
                  </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            </div>
         </div>

         {{-- pagination --}}

         <div class="d-flex justify-content-end my-5">

            <nav aria-label="...">
                <ul class="pagination" max-size="2">
                    
                    @php
                    $link= '/admin/list?type='.$selectedType.'&grade_id='.$selectedGrade.'&sort='.$selectedSort.'&order='.$selectedOrder.'&status='.$selectedStatus.'&search='.$form->search;
                    $previousLink = $link . '&page='.$data->currentPage()-1;
                    $nextLink = $link . '&page='.$data->currentPage()+1;
                    $firstLink = $link . '&page=1';
                    $lastLink = $link . '&page=' . $data->lastPage();
                    
                    $arrPagination = [];
                    $flag = false;
                    
                    if($data->lastPage() - 5 > 0){
                        
                        
                        if($data->currentPage()<=4)
                        {
                            for ($i=1; $i <= 5; $i++) { 
                                # code...
                                $temp = (object) [
                                    
                                    'page' => $i,
                                    'link' => $link . '&page=' . $i,
                                ];
                                
                                array_push($arrPagination, $temp);
                            }
                        }
                        
                        else if($data->lastPage() - $data->currentPage() > 2)
                        {
                            $flag = true;
                            $idx = array($data->currentPage()-2,$data->currentPage()-1,$data->currentPage(),$data->currentPage()+1,$data->currentPage()+2);
                            
                            foreach ($idx as $value) {
                                
                                $temp = (object) [
                                    
                                    'page' => $value,
                                    'link' => $link . '&page=' . $value,
                                ];
                                
                                array_push($arrPagination, $temp);
                            }
                            
                        } else {
                            
                            $arrFirst = [];
                            //ini buat yang current page sampai last page
                            
                            for($i=$data->currentPage(); $i<=$data->lastPage(); $i++){
        
                                $temp = (object) [
                                
                                'page' => $i,
                                'link' => $link . '&page=' . $i,
                            ];
                            
                            array_push($arrFirst, $temp);
                        }
                        
                        
                        $arrLast = [];
                            $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                            //ini yang buat current page but decrement
                            
        
                            for($i=$diff; $i < $data->currentPage(); $i++){
        
                                $temp = (object) [
                                    
                                    'page' => $i,
                                'link' => $link . '&page=' . $i,
                            ];
        
                            
                            array_push($arrLast, $temp);
                        }
                        
                        
                        $arrPagination = array_merge($arrLast, $arrFirst);
                        }
                        
                        
                        
                    } else {
        
                        for($i=1; $i<=$data->lastPage(); $i++)
                        {
                            $temp = (object) [
                                
                                'page' => $i,
                                'link' => $link . '&page=' . $i,
                            ];
        
                            array_push($arrPagination, $temp);
                        }
                    }
                    
                    @endphp
        
                <li class="mr-1 page-item {{$data->previousPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$firstLink}}" tabindex="+1">
                        << First
                    </a>
                </li>
        
                <li class="page-item {{$data->previousPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$previousLink}}" tabindex="-1">
                        Previous
                    </a>
                </li>
        
                @foreach ( $arrPagination as $el)
                
                <li class="page-item {{$el->page === $data->currentPage() ? 'active' : ''}}">
                    <a class="page-link" href="{{$el->link}}">
                        {{$el->page}}
                    </a>
                </li>
        
                @endforeach
                
                <li class="page-item {{$data->nextPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$nextLink}}" tabindex="+1">
                        Next
                    </a>
                </li>
        
                <li class="ml-1 page-item {{$data->nextPageUrl()? '' : 'disabled'}}">
                    <a class="page-link" href="{{$lastLink}}" tabindex="+1">
                        Last >>
                    </a>
                </li>
        
            </ul>
            
        </nav>
        
        </div>


         @endif
         
         @include('components.super.delete-student')
@endsection