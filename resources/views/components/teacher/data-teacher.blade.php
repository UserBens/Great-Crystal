@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
   <div class="container-fluid">
      <h2 class="text-center display-4">Teacher Search</h2>
      <form class="mt-5" action="/admin/teachers">
         <div class="row">
             <div class="col-md-10 offset-md-1">
                 <div class="row">
                     <div class="col-6">
                         <div class="form-group">
                             <label>Result Type:</label>
                             @php
                                 
                                 $selectedType = $form && $form->type ? $form->type : 'name';

                              @endphp
                              <select name="type" class="form-control" required>
                                  <option {{$selectedType === 'name' ? 'selected' : ''}} value="name">Name</option>
                                  <option {{$selectedType === 'place_birth' ? 'selected' : ''}} value="place_birth">Place Birth</option>
                                  <option {{$selectedType === 'nationality' ? 'selected' : ''}} value="nationality">Nationality</option>
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
                              
                              $selectedStatus = $form->status ? $form->status : 'true';
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

     @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
         
     <div class="row h-100 my-5">
        <div class="col-sm-12 my-auto text-center">
            <h3>The teachers you are looking for does not exist !!!</h3>
        </div>
    </div>

     @elseif (sizeof($data) == 0)

     <div class="row h-100 my-5">
        <div class="col-sm-12 my-auto text-center">
            <h3>Teacher has never been registered. Click the
                button below to register teacher's !!!</h3>
            <a role="button" href="/admin/teachers/register" class="btn btn-success mt-4">
                <i class="fa-solid fa-plus"></i>
                Register Teacher
            </a>
        </div>
    </div>

     @else

      <a type="button" href="teachers/register" id="#" class="btn btn-success btn mt-5 mx-2">
         <i class="fa-solid fa-user-plus"></i>
         </i>   
         Register
      </a>

      <div class="card card-dark mt-5">
       <div class="card-header">
         <h3 class="card-title">Teacher</h3>

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
                     <th style="width: 3%">
                         #
                     </th>
                     <th>
                           Teacher Name
                     </th>
                     <th>
                           Place of birth
                     </th>
                     <th>
                           Nationality
                     </th>
                     <th>
                           Gender
                     </th>
                     <th>
                           Register at
                     </th>
                     <th style="width: 8%;" class="text-center">
                           Status
                     </th>
                     <th style="width: 25%">
                     </th>
                 </tr>
             </thead>
             <tbody>
               <tr>
                  @foreach($data as $el)
                  <tr id="{{'index_teacher_' . $el->id}}">
                     <td>{{$loop->index + 1}}</td>
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
                     <td>{{$el->place_birth}}</td>
                     <td>{{$el->nationality}}</td>
                     <td>{{$el->gender}}</td>
                     <td>{{date('d/m/Y', strtotime($el->created_at))}}</td>
                     <td>
                        @if($el->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
                        @endif
                        
                     </td>
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a class="btn btn-primary {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="teachers/detail/{{$el->unique_id}}">
                           <i class="fas fa-folder">
                         </i>
                         View
                      </a>
                      <a class="btn btn-info {{session('role') == 'admin'? 'btn' : 'btn-sm'}}" href="teachers/{{$el->unique_id}}">
                         <i class="fas fa-pencil-alt">
                         </i>
                         Edit
                      </a>
                        @if(session('role') == 'superadmin')
                            @if (!$el->is_active)
                                <a href="javascript:void(0)" id="active-teacher" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-success btn-sm">
                                   <i class="fas fa fa-ban"></i>
                                   Activate
                                </a>
                            @else
                                <a href="javascript:void(0)" id="delete-teacher" data-id="{{ $el->id }}" data-name="{{ $el->name }}" class="btn btn-danger btn-sm">
                                   <i class="fas fa fa-ban"></i>
                                   Deactive
                                </a>
                            @endif
                        @endif
                     </td>
                  </tr>
                  @endforeach
               </tr>
             </tbody>
         </table>
       </div>
       <!-- /.card-body -->
     </div>
    </div>
    @include('components.super.delete-teacher')


    <div class="d-flex justify-content-end my-5">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $link= '/admin/teachers?type='.$selectedType.'&sort='.$selectedSort.'&order='.$selectedOrder.'&status='.$selectedStatus.'&search='.$form->search;
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

@endsection