@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">
           <h2 class="text-center display-4">Payment Books Search</h2>
           <form class="mt-5" action="/admin/payment-books">
               <div class="row">
                   <div class="col-md-10 offset-md-1">
                       <div class="row">
                        
                        <div class="col-4">
                            <div class="form-group">

                              @php

                                 $selectedGrade = $form && $form->grade_id? $form->grade_id : 'all';

                              @endphp

                                <label>Grade :</label>
                                 <select name="grade_id" class="form-control">
                                            <option {{$selectedGrade == 'all' ? 'selected' : ''}} value="all">All Grades</option>
                                       @foreach ($grade as $el)
                                       
                                            <option {{$selectedGrade == $el->id? 'selected' : ''}} value="{{$el->id}}">{{$el->name . '-' . $el->class}}</option>
                                           
                                       @endforeach
                                 </select>
                               
                            </div>
                        </div>
                        <div class="col-4">
                               <div class="form-group">

                                 @php

                                    $selectedSort = $form && $form->order? $form->order : 'id';

                                 @endphp

                                   <label>Sort by:</label>
                                    <select name="order" class="form-control">
                                          <option {{$selectedSort === 'id'? 'selected' : ''}} value="id">Register</option>
                                          <option {{$selectedSort === 'name'? 'selected' : ''}} value="name">Name</option>
                                          <option {{$selectedSort === 'grade_id'? 'selected' : ''}} value="grade_id">Grade</option>
                                    </select>
                                  
                               </div>
                           </div>
                           <div class="col-2">
                            <div class="form-group">

                                 @php
                                    
                                    $selectedOrder = $form && $form->sort ? $form->sort : 'desc';

                                 @endphp

                              <label>Sort order: <span style="color: red"></span></label>
                              <select name="sort" class="form-control">
                                  <option value="desc" {{$selectedOrder === 'desc' ? 'selected' : ''}}>Descending</option>
                                  <option value="asc" {{$selectedOrder === 'asc' ? 'selected' : ''}}>Ascending</option>
                              </select>                              
                            </div>
                        </div>
                           <div class="col-2">
                               <div class="form-group">
                                 <label>Status: <span style="color: red"></span></label>

                                 @php
                                 
                                    $selectedStatus = $form && $form->status ? $form->status : 'true';
                                    $option = $selectedStatus === 'false' ? 'true' : 'false';

                                 @endphp

                                 <select name="status" class="form-control">
                                     <option  selected value="{{$selectedStatus}}">{{$selectedStatus === 'true' ? 'Active' : 'Inactive'}}</option>
                                     <option  value="{{$option}}">{{$option === 'true' ? 'Active' : 'Inactive'}}</option>
                                 </select>                              
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <div class="input-group input-group-lg">
                               <input name="search" value="{{$form->search}}" type="search" class="form-control form-control-lg" placeholder="Type students name here">
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

        @if (sizeof($data) <=0 && ($form->search || $form->sort ||  $form->order || $form->grade_id || $form->status))
            
            <div class="row h-100 mt-5">
                <div class="col-sm-12 my-auto text-center ">
                    <h3>Payment book you are looking for does not exist !!!</h3>
                </div>
            </div>

        @elseif (sizeof($data) <=0)
    
        <div class="row h-100 mt-5">
            <div class="col-sm-12 my-auto text-center ">
                <h3>Payment book has never been created !!!</h3>
            </div>
        </div>

        @else
            
        


            <div class="card my-5 card-dark">
            <div class="card-header">
              <h3 class="card-title">Book payments</h3>
    
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
                              Grade
                          </th>
                          <th style="width: 8%" class="text-center">
                              Class
                          </th>
                          <th style="width: 8%" class="text-center">
                              Total books
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
                              <a>
                                 {{$el->grade->name}}
                           </a>
                        </td>
                        <td class="text-center">
                           {{$el->grade->class}}
                        </td>
                        <td class="text-center">
                            {{$el->book_count}}
                        </td>
                        <td class="project-state">
                           @if($el->is_active)
                           <h1 class="badge badge-success">Active</h1>
                           @else
                           <h1 class="badge badge-danger">Inactive</h1>
                           @endif
                        </td>
                          <td class="project-actions text-center toastsDefaultSuccess">
                             <a class="btn btn-primary btn-lg" href="/admin/payment-books/{{$el->unique_id}}">
                                <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                        </td>
                     </tr>
                     
                     @endforeach
                  </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            </div>


            <div class="d-flex justify-content-end">

                <nav aria-label="...">
                    <ul class="pagination" max-size="2">
                        
                        @php
                        $link= '/admin/payment-books?grade_id='.$selectedGrade.'&order='.$selectedSort.'&sort='.$selectedOrder.'&status='.$selectedStatus.'&search='.$form->search;
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
         </div>
{{--         
        @if($data->currentPage() > 1) --}}

         

        {{-- @endif --}}
         
         @include('components.super.delete-student')
@endsection