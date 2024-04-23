@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    @if (sizeof($data) <= 0 && ($form->grade_id || $form->book_name))


    <h2 class="text-center display-4">Book Search</h2>
           <form class="mt-5" action="/admin/books">
               <div class="row">
                   <div class="col-md-10 offset-md-2">
                       <div class="row">
                           <div class="col-3">
                               <div class="form-group">
                                   <label>Grade :</label>
                                   @php
                                       
                                       $selected = $form && $form->grade_id ? $form->grade_id : 'all';

                                    @endphp
                                    <select name="grade_id" class="form-control form-control-lg ">
                                        <option {{$selected === 'all' ? 'selected' : ''}} value="all">none</option>

                                        @foreach ($grades as $grade)
                                        <option {{$selected == $grade->id ? 'selected' : ''}} value="{{$grade->id}}">{{$grade->name}} - {{$grade->class}}</option>
                                        @endforeach
                                    </select>
                                  
                               </div>
                           </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label>Book name :</label>
                                <div class="input-group input-group-lg">
                                    <input name="book_name" value="{{$form && $form->book_name? $form->book_name : ""}}" type="search" class="form-control form-control-lg" placeholder="Search book name">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                       </div>
                   </div>
               </div>
           </form >


    <a role="button" href="/admin/books/create" class="btn btn-success mt-4">
        <i class="fa-solid fa-plus"></i>
        Create Book
    </a>
        
    <div class="col-sm-12 my-5 my-auto text-center">
        <h3>The bills you are looking for does not exist !!!</h3>
        {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
            <i class="fa-solid fa-plus"></i>
            Create bill for student
        </a> --}}
    </div>

    @elseif (sizeof($data) <= 0 )

    <div class="row h-100">
        <div class="col-sm-12 my-auto text-center">
            <h6>Book has never been created. Click the
                button below to get started !!!</h6>
            <a role="button" href="/admin/books/create" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Create Book
            </a>
        </div>
    </div>

    @else    


    <h2 class="text-center display-4">Book Search</h2>
           <form class="mt-5" action="/admin/books">
               <div class="row">
                   <div class="col-md-10 offset-md-2">
                       <div class="row">
                           <div class="col-3">
                               <div class="form-group">
                                   <label>Grade :</label>
                                   @php
                                       
                                       $selectedGrade = $form && $form->grade_id ? $form->grade_id : 'all';

                                    @endphp
                                    <select name="grade_id" class="form-control form-control-lg ">
                                        <option {{$selectedGrade === 'all' ? 'selected' : ''}} value="all">none</option>

                                        @foreach ($grades as $grade)
                                        <option {{$selectedGrade == $grade->id ? 'selected' : ''}} value="{{$grade->id}}">{{$grade->name}} - {{$grade->class}}</option>
                                        @endforeach
                                    </select>
                                  
                               </div>
                           </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label>Book name :</label>
                                <div class="input-group input-group-lg">
                                    <input name="book_name" value="{{$form && $form->book_name? $form->book_name : ""}}" type="search" class="form-control form-control-lg" placeholder="Search book name">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                       </div>
                   </div>
               </div>
           </form >


    <a role="button" href="/admin/books/create" class="btn btn-success mt-4">
        <i class="fa-solid fa-plus"></i>
        Create Book
    </a>

    <div class="card card-dark mt-5">
        <div class="card-header">
            <h3 class="card-title">Book</h3>

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
                        <th>
                           #
                        </th>
                        <th style="width: 25%">
                           Book name
                        </th>
                        <th style="width: 25%">
                           Grade
                        </th>
                        <th style="width: 25%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                           <a>
                                 {{$el->name}}
                           </a>
                        </td>
                        <td>
                           @if ($el->grade)
                              
                              <a  a href="/admin/grades/{{$el->grade->id}}">
                                    {{$el->grade->name}} - {{$el->grade->class}}
                              </a>
                           @else 
                              <h6 style="color: red;">{{'Unknown'}}</h6>
                           @endif
                        </td>
                        <td>
                           {{$el->active_student_count}}
                        </td>
                        
                        <td class="project-actions text-right toastsDefaultSuccess text-center">
                           <a class="btn btn-primary btn"
                              href="{{url('/admin/books/') . '/detail/' . $el->id}}">
                              <i class="fas fa-folder">
                              </i>
                              View
                           </a>
                           <a class="btn btn-info btn"
                              href="{{url('/admin/books/') . '/edit/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                           </a>
                        </td>
                    </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        <!-- /.card-body -->
        </div>


        <div class="d-flex justify-content-end my-5">

            <nav aria-label="...">
                <ul class="pagination" max-size="2">
                    
                    @php
                    $link= '/admin/books?grade_id='.$selectedGrade.'&book_name='.$form->book_name;
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

        {{-- @endif --}}
     </div>
    @endif
</div>


@if(session('after_create_book')) 
    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>

      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
    
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully registered the books in the database !!!',
      });
      }, 1500);


    </script>

  @endif

  @if(session('after_update_book')) 
    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>

      var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
      });
    
      setTimeout(() => {
         Toast.fire({
            icon: 'success',
            title: 'Successfully updated the book in the database !!!',
      });
      }, 1500);


    </script>
        
  @endif


@endsection
