@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<h2 class="text-center display-4">Bills Search</h2>
<form class="my-5" action="/admin/bills/status">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row mb-1">
                <div class="col-lg-4">
                    <div class="form-group">

                        @php

                        $selectedType = $form && $form->type? $form->type : 'all';

                        @endphp

                        <label>Type : </label>
                        <select name="type" class="form-control text-center">
                            <option class="bg-primary" {{$selectedType === 'all'? 'selected' : ''}} value="all">-- All Type --</option>
                            <option {{$selectedType === 'Capital Fee'? 'selected' : ''}} value="capital fee">Capital Fee</option>
                            <option {{$selectedType === 'Uniform'? 'selected' : ''}} value="uniform">Uniform</option>
                            <option {{$selectedType === 'Paket'? 'selected' : ''}} value="paket">Paket</option>
                            <option {{$selectedType === 'Book'? 'selected' : ''}} value="book">Book</option>
                            <option {{$selectedType === 'SPP'? 'selected' : ''}} value="spp">SPP</option>
                            <option {{$selectedType === 'Others'? 'selected' : ''}} value="others">Others</option>
                        </select>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">

                        @php

                        $selectedInvoice = $form && $form->invoice ? $form->invoice : 'all';

                        @endphp

                        <label>Invoice : <span style="color: red"></span></label>
                        <select name="invoice" class="form-control text-center">
                            <option {{$selectedInvoice === 'all'? 'selected' : ''}} value="all" >-- All Invoice --</option>
                            <option {{$selectedInvoice === 'create'? 'selected' : ''}} value="create" >Bill created</option>
                            <option {{$selectedInvoice === 'charge'? 'selected' : ''}} value="charge" >Bill charge</option>
                            <option {{$selectedInvoice === 'past_due'? 'selected' : ''}} value="past_due" >Bill past due</option>
                            <option {{$selectedInvoice === 'is_paid'? 'selected' : ''}} value="is_paid" >Bill payment success</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Status: <span style="color: red"></span></label>

                        @php

                            $selectedStatus = $form->status != 'all' && $form->status ? $form->status : 'all'

                        @endphp

                        <select name="status" class="form-control text-center">
                            <option {{$selectedStatus == 'all'? 'selected' : ''}} value="all">-- All Status --</option>
                            <option {{$selectedStatus == 'true'? 'selected' : ''}} value="true">Success</option>
                            <option {{$selectedStatus == 'false'? 'selected' : ''}} value="false">Failed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <input name="search" value="{{$form->search}}" type="number" class="form-control form-control-lg"
                        placeholder="Type invoice number here">
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


@if(sizeof($data) <= 0 && ($form->search || $form->type || $form->invoice || $form->status)) 
    <div class="container-fluid mt-5">
        <div class="row h-100">
            <div class="col-sm-12 my-auto text-center">
                <h3>The bills you are looking for does not exist !!!</h3>
                {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Create bill for student
                </a> --}}
            </div>
        </div>
        
        @elseif (sizeof($data) <= 0)
        <div class="container-fluid mt-5">
            <div class="row h-100">
                <div class="col-sm-12 my-auto text-center">
                <h3>The bills never been created !!!</h3>
                {{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
                    <i class="fa-solid fa-plus"></i>
                    Create bill for student
                </a> --}}
            </div>
    </div>

@else


{{-- <a role="button" href="/admin/bills/create" class="btn btn-success mt-4">
    <i class="fa-solid fa-plus"></i>
    Create bill for student
</a> --}}

<div class="card mt-5 card-dark">
    <div class="card-header">
        <h3 class="card-title">Status notification emails</h3>

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
                    <th class="text-center" style="width: 10%">
                        Nomor Invoice
                    </th>
                    <th style="width: 10%" class="text-center">
                        Type
                    </th>
                    <th>
                        Student
                    </th>
                    <th class="text-left">
                        Description
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
                        {{ $loop->index+1 }}
                    </td>
                    <td class="text-center">
                        <a>
                            {{$el->bill->number_invoice}}
                        </a>
                    </td>
                    <td class="text-center">
                        {{$el->bill->type}}
                    </td>
                    <td>
                        {{$el->bill->student->name}}  
                    </td>
                    <td>
                        @if ($el->charge)
                            Send <b>charge past due</b> notification by email
                        @elseif ($el->past_due)
                            Send bill is <b>past due</b> notification by email
                        @elseif ($el->is_change)
                            Send bill has been <b>changed</b> notification by email
                        @elseif ($el->is_paid)
                            Send <b>payment received</b> notification by email
                        @else
                            Send bill has been <b>created</b> notification by email
                        @endif
                    </td>
                        <td class="project-state text-center">
                            @if($el->status)
                            <h1 class="badge badge-success">success</h1>
                            @else
                            <h1 class="badge badge-danger">failed</h1>
                            @endif
                        </td>
                        <td class="project-actions text-right toastsDefaultSuccess text-center">
                            <a class="btn btn-primary"
                                href="/admin/bills/status/{{$el->id}}">
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

@if ($data->lastPage() > 1)
    

<div class="d-flex justify-content-end my-5">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $link= '/admin/bills/status?invoice='.$selectedInvoice.'&type='.$selectedType.'&status='.$selectedStatus.'&search='.$form->search;
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

@endif
</div>


{{-- @if(session('create_installment_bill')) 
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
            title: 'Successfully create installment bills for student paket',
      });
      }, 1500);


    </script>

  @endif


  <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('change_type_paket')) 

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
            title: 'Successfully change bills for student paket',
      });
      }, 1500);


    </script>

  @endif

@if (!$flag_date)

<script>

    var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
    });
  
    setTimeout(() => {
       Toast.fire({
          icon: 'warning',
          title: "Warning !!!, The from date cannot be more than to date.",
    });
    }, 1500);


  </script>
    
@endif --}}

<script>
    // Listen for the browser's back button click
    window.onpopstate = function (event) {
        // Reload the page to force a new request to the server
        alert('back ++++++++++++++++++++++++++++++++++++++++++++');
    };
</script>


@endsection
