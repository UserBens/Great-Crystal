@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    <h2 class="text-center display-4">SPP Student</h2>
    <form class="my-5" action="/admin/spp-students">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Grade:</label>
                            @php
    
                            $selectedGrade = $form && $form->grade? $form->grade : 'all';
    
                            @endphp
                            <select name="grade" class="form-control text-center" required>
                                <option {{$selectedGrade === 'all' ? 'selected' : ''}} value="all">-- All Grades --</option>
                                @foreach ($grade as $el)
                                
                                    <option {{$selectedGrade == $el->id ? 'selected' : ''}} value="{{$el->id}}">{{$el->name. ' - ' .$el->class}}</option>
                                    
                                @endforeach
                            </select>
    
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
    
                            @php
    
                            $selectedOrder = $form && $form->sort? $form->sort : 'desc';
    
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
                            <label>Set Spp: <span style="color: red"></span></label>
    
                            @php
    
                            $selectedStatus = $form && $form->status ? $form->status : 'all';
    
                            @endphp
    
                            <select name="status" class="form-control text-center">
                                <option {{$selectedStatus == 'all'? 'selected' : ''}} value="all">
                                    -- All --
                                </option>
                                <option  {{$selectedStatus == 'true'? 'selected' : ''}} value="true">
                                    Already
                                </option>
                                <option  {{$selectedStatus == 'false'? 'selected' : ''}} value="false">
                                    Not yet
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input name="search" value="{{$form? $form->search : ''}}" type="search" class="form-control form-control-lg"
                            placeholder="Type your keywords here">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@if(sizeof($data) == 0 && ($form->search || $form->order || $form->sort || $form->status || $form->grade)) 
<div class="row h-100">
    <div class="col-sm-12 my-auto text-center mt-5">
        <h3>The spp students you are looking for does not exist !!!</h3>
    </div>
</div>

@elseif (sizeof($data) == 0)
<div class="row h-100">
    <div class="col-sm-12 my-auto text-center mt-5">
        <h3>The spp students never been created !!!</h3>
    </div>
</div>

@else



<div class="card card-dark mt-5">
    <div class="card-header">
        <h3 class="card-title">SPP</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects" style="margin-left:auto;margin-right:auto;">
            <thead>
                <tr>
                    <th style="width: 12%">
                        #
                    </th>
                    <th  style="width: 20%">
                        Student
                    </th>
                    <th >
                        Grade
                    </th>
                    <th  style="width: 8%">
                        Class
                    </th>
                    <th  style="width: 15%">
                        Spp
                    </th>
                    
                    <th class="text-center" style="width: 30%">
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $el)
                <tr id={{'index_student_' . $el->id}}>
                    <td>
                        {{ $loop->index + 1 }}
                    </td>
                    <td >
                        {{$el->name}}
                    </td>
                    <td >
                       {{$el->grade->name}}
                     </td>
                     <td >
                        {{$el->grade->class}}
                     <td >
                           <a>
                               @if($el->spp_student)
                               {{-- <h1 class="badge badge-success">already set</h1> --}}
                               IDR {{number_format($el->spp_student->amount - $el->spp_student->amount*$el->spp_student->discount/100, 0, ',', '.')}} <br>
                               @if ($el->spp_student->discount && $el->spp_student->discount>0)
                                    <small>Discount: {{$el->spp_student->discount}}%</small>
                               @endif
                               @else
                               <h1 class="badge badge-danger">not set yet</h1>
                               @endif
                           </a>
                        </td>
                        <td class="project-actions text-center toastsDefaultSuccess">

                            @if ($el->spp_student)
                                
                            <a class="btn btn-primary btn-lg"
                                href="/admin/spp-students/detail/{{$el->unique_id}}">
                                <i class="fas fa-folder">
                                </i>
                                View
                            </a>
                            @else
                            <a class="btn btn-success btn-lg"
                                href="/admin/spp-students/create/{{$el->unique_id}}">
                                <i class="fa-solid fa-plus"></i>
                                </i>
                                  Create
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


<div class="d-flex justify-content-end">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $link= '/admin/spp-students?grade='.$selectedGrade.'&order='.$selectedSort.'&sort='.$selectedOrder.'&status='.$selectedStatus.'&search='.$form->search;
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

@endsection
