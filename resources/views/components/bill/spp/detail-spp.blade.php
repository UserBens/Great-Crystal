@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/bills')}}">Bill</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Payment</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">

          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Nomor Invoice</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->number_invoice}}</p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Student Name</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{-- @if($data->is_active)
                           <h1 class="badge badge-success">Active</h1>
                        @else
                           <h1 class="badge badge-danger">Inactive</h1>
                        @endif --}}
                        {{$data->student->name}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Type</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{$data->type}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Subject</p>
                  </div>
                  <div class="col-sm-8">
                     
                     @php

                        $subject = '-';

                        if($data->subject){
                           
                           $subject = $data->installment? $data->type. ' installment ' . '( '. $data->subject .' )' : 'Cash';

                        }
                     @endphp

                    <p class="text-muted mb-0">{{$subject}}</p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Grade</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->student->grade->name}}
                     </p>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Class</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{$data->student->grade->class}}
                     </p>
                  </div>
                </div>
                <hr>
                @php
                     $currentDate = date('y-m-d');
                     $dateInvoice1 = date_create($currentDate);
                     $dateInvoice2 = date_create(date('y-m-d', strtotime($data->deadline_invoice)));
                     $dateInvoiceWarning = date_diff($dateInvoice1, $dateInvoice2);
                     $invoice = $dateInvoiceWarning->format('%a');
                @endphp
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Invoice</p>
                  </div>
                  <div class="col-sm-8">
                     <div class="mb-0">

                     <p class="text-muted">
                           {{date('d/m/Y', strtotime($data->deadline_invoice))}}
                     </p>
                     @if ($data->paidOf)
                        <span class="badge badge-pill badge-success"> Paid </span>
                     @elseif (strtotime($data->deadline_invoice) < strtotime($currentDate))
                        <span class="badge badge-pill badge-danger"> Past Due </span>
                     @else
                        <span class="badge badge-pill badge-warning"> {{$invoice == 0? 'Today' : $invoice . ' Days'}}</span>
                     @endif
                  </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Created</p>
                  </div>
                  <div class="col-sm-8">
                     <p class="text-muted mb-0">
                        {{date('d/m/Y', strtotime($data->created_at))}}
                     </p>
                  </div>
                </div>
                <hr>
                @if ($data->installment)
                   
                <div class="row">
                   <div class="col-sm-4">
                      <p class="mb-0">Installment</p>
                     </div>
                     <div class="col-sm-8">
                        <p class="text-muted mb-0">
                           {{$data->installment}}x
                        </p>
                     </div>
                  </div>
                  <hr>
               @endif
              </div>
            </div>
            @if (sizeof($data->bill_installments) > 0)

            <div class="card">
             <div class="card-header">
               <h3 class="card-title">
                  <i class="fa-solid fa-file-invoice mr-1"></i>
                 Installments
               </h3>
               <div class="card-tools">
                 {{-- <ul class="nav nav-pills ml-auto">
                   <li class="nav-item">
                     <a class="nav-link active" href="#revenue-chart" data-toggle="tab">New</a>
                   </li>
                 </ul> --}}
               </div>
             </div><!-- /.card-header -->
             <div class="card-body">
               <div class="tab-content p-0">
                 <!-- Morris chart - Sales -->
                 <div class="chart tab-pane active" id="revenue-chart"
                      style="position: relative;">
                    
                     {{-- <h1>New Bills</h1> --}}
                     <div>
                      <!-- /.card-header -->
                      <div>
                        <ul class="todo-list" data-widget="todo-list">

                        @php
                          $currentDate = date('y-m-d');
                       @endphp 

                          @foreach ($data->bill_installments as $el)
                            
                          
                          <li>
                            <!-- drag handle -->
                            <span class="handle">
                              <i class="fas fa-ellipsis-v"></i>
                              <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <!-- checkbox -->
                            <div  class="icheck-primary d-inline ml-2">
                              <span class="text-muted">[ {{date( 'd F Y',strtotime($el->deadline_invoice))}} ]</span>
                            </div>
                            <!-- todo text -->
                            <span class="text">( {{$el->type}} ) {{$el->student->name}}</span>
                            <!-- Emphasis label -->
                            

                            @if ($el->paidOf)
                              
                              <small class="badge badge-success"><i class="far fa-checklist"></i> Success</small>

                            @elseif (strtotime($el->deadline_invoice) < strtotime($currentDate))

                            <small class="badge badge-danger"><i class="far fa-clock"></i> Past Due</small>
                            @else
                            @php
                                  $date1 = date_create($currentDate);
                                  $date2 = date_create(date('y-m-d', strtotime($el->deadline_invoice)));
                                  $dateWarning = date_diff($date1, $date2);
                                  $dateDiff = $dateWarning->format('%a') == 0? 'Today' : $dateWarning->format('%a'). ' days';
                                  @endphp
                              <small class="badge badge-warning"><i class="far fa-clock"></i> {{
                                 
                                 $dateDiff
                                 
                              }}</small>
                            @endif
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                               <a href="/admin/bills/detail-payment/{{$el->id}}" target="_blank">
                                 <i class="fas fa-search"></i>
                              </a>
                           </div>
                          </li>
                          
                          @endforeach
                        </ul>
                     </div>
                  </div>

                  
                  
               </div>
            </div>
         </div><!-- /.card-body -->
      </div>
      <!-- /.card -->
      <a target="_blank" href="/admin/bills/installment-pdf/{{$data->id}}" class="btn btn-dark w-100 mb-2" id='report-pdf'><i class="fa-solid fa-file-pdf fa-bounce" style="color: white; margin-right:2px;"></i>Report PDF</a>

      @endif
      </div>

      <div class="col-lg-4 p-1">
            <div class="card mb-4 p-4">
               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>



                     @if (sizeof($data->bill_collection)>0)

                     @foreach ($data->bill_collection as $el)

                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              {{$el->name}} :
                           </td>
                           <td align="right" class="p-1">
                              Rp. {{number_format($el->amount, 0, ',', '.')}}
                           </td>
                           
                        </tr>
                        
                        @endforeach

                        @if ($data->charge > 0)

                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Charge:
                           </td>
                           <td align="right">
                             + Rp. {{ number_format($data->charge,0,',','.') }}
                           </td>

                        </tr>
                        @endif
                        
                     @else
                        
                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Amount :
                           </td>
                           <td align="right">
                              Rp.{{number_format($data->amount - $data->charge, 0, ',', '.')}}
                           </td>
                           
                        </tr>
                        
                        @if ($data->dp)
                        
                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Done payment : 
                           </td>
                           <td align="right">
                              -Rp.{{number_format($data->dp, 0, ',', '.')}}
                           </td>

                        </tr>
                        
                        @endif
                        
                     @if ($data->installment)
                     
                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Installment : 
                           </td>
                           <td align="right">
                              {{ $data->installment }}x
                           </td>

                        </tr>
                        
                        @endif

                        @if ($data->discount)
                        
                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Discount:
                           </td>
                           <td align="right">
                              {{$data->discount ? $data->discount : 0}}%
                           </td>

                        </tr>
                     @endif
                     @if ($data->charge > 0)

                        <tr>
                           <td align="left" class="p-1" style="width:50%;">
                              Charge:
                           </td>
                           <td align="right">
                             + Rp. {{ number_format($data->charge,0,',','.') }}
                           </td>

                        </tr>
                     @endif
                     @endif
                     
                  </tbody>
               </table>

               @if ($data->bill_collection && $data->installment && $data->type === 'Book') 

               <hr>
               
               
               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>

                  <tbody>
                     <tr>
                        <td align="left" class="p-1 font-weight-bold" style="width:65%;">Total amount :</td>
                        <td align="right">Rp. {{$data->amount}} </td>
                     </tr>
                     <tr>
                        <td align="left" class="p-1 font-weight-bold" style="width:65%;">Installment :</td>
                        <td align="right"> {{$data->installment}}x </td>
                     </tr>
                  </tbody>
               </table>
                  
               @endif

               <hr>

               <table>
                  <thead>
                     <th></th>
                     <th></th>
                  </thead>
                  <tbody>
                     @php 
                        if ($data->type == "SPP") {
                           # code...   
                           $total = $data->discount ? $data->amount - $data->amount * $data->discount/100 : $data->amount;
                           $total = $total;
                        } else {
                           $total = $data->installment ? $data->amount_installment : $data->amount-$data->dp;
                           $total = $total;
                        }
                     @endphp
                     <tr>
                        <td align="left" class="p-1 font-weight-bold" style="width:65%;">
                           Total :
                        </td>
                        <td align="right" class="font-weight-bold">
                           Rp. {{number_format($total, 0, ',', '.')}}
                        </td>
                        
                     </tr>
                     
                  </tbody>
               </table>
            </div>
            <a target="_blank" href="/admin/bills/paid/pdf/{{$data->id}}" class="btn btn-warning w-100 mb-2" id="change-paket"><i class="fa-solid fa-file-pdf fa-bounce" style="color: #000000; margin-right:2px;"></i>Print PDF</a>
            @if (!$data->paidOf)
               @if (strtolower($data->type) == 'paket' && !$data->installment)
               <a id="changes-paket" href="/admin/bills/change-paket/{{$data->student->unique_id}}/{{$data->id}}" class="btn btn-info w-100 mb-2">Change Paket</a>
               <a id="paket-installment" href="/admin/bills/intallment-paket/{{$data->id}}" class="btn btn-secondary w-100 mb-2">Installment Paket</a>
               @endif
               @if(strtolower($data->type) == 'book')
                  <a href="javascript:void(0)" id="update-status-book" data-id="{{ $data->id }}" data-name="{{ $data->student->name }}" data-student-id="{{ $data->student->id }}" class="btn btn-success w-100 mb-2">Paid book success</a>
               @else
                  <a href="javascript:void(0)" id="update-status" data-id="{{ $data->id }}" data-name="{{ $data->student->name }}" data-subject="{{ $data->subject }}" class="btn btn-success w-100 mb-2">Paid success</a>
               @endif
                  
               
            @endif
          </div>
        </div>
      </div>
    </section>

    @includeIf('components.super.update-paid')

   @if(session('after_create')) 
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

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
         title: 'Data has been saved !!!',
   });
   }, 1500);


   </script>
        
    @endif

@endsection