@extends('layouts.admin.master')
@section('content')


   <section style="background-color: #eee; ">
      <div class="container py-5">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/admin/bills/status')}}">Status Bills</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
              </ol>
            </nav>
          </div>
        </div>
    
        <div class="row">
          <div class="col-lg-12">
            <div class="card mb-4">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Number Invoice</p>
                  </div>
                  <div class="col-sm-8">
                    <a target="_blank" href="/admin/bills/detail-payment/{{$data->bill->id}}" class="text-muted mb-0">#{{$data->bill->number_invoice}}</a>
                  </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Type</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">{{$data->bill->type}}</p>
                    </div>
                  </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Student</p>
                    </div>
                    <div class="col-sm-8">
                      <a target="_blank" href="/admin/detail/{{$data->bill->student->unique_id}}" class="text-muted mb-0">{{$data->bill->student->name}}</a>
                    </div>
                  </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Description</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">
  
                        @if ($data->charge)
                            Send <b>charge past due</b> notification by email
                        @elseif ($data->past_due)
                            Send <b>bill is past due</b> notification by email
                        @elseif ($data->is_change)
                            Send <b>bill has been changed</b> notification by email
                        @elseif ($data->is_paid)
                            Send <b>payment received</b> notification by email
                        @else
                            Send bill <b>bill has been created</b> notification by email
                        @endif
  
                      </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Status</p>
                  </div>
                  <div class="col-sm-8">
                     @if($data->status)
                           <h1 class="badge badge-success">success</h1>
                     @else
                           <h1 id="status-invoice" class="badge badge-danger">failed</h1>
                     @endif
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Send Date</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">{{date('l, d F Y', strtotime($data->created_at))}}</p>
                  </div>
                </div>
                <hr>
                </div>
              </div>
              @if (!$data->status)
                <button id="re-send-mail" href="javascript:void(0)" status-id="{{$data->id}}" class="btn btn-success btn-lg w-100"><i class="fa-solid fa-envelope fa-fade mr-3"></i>Send Email</button>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>


    @include('components.bill.status.action-send-mail');



@endsection