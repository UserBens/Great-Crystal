@extends('layouts.admin.master')
@section('content')

   <!-- Content Wrapper. Contains page content -->
       <div class="container-fluid">

        <section style="background-color: #eee;">
        <div class="container py-5">
          <div class="row">
            <div class="col">
              <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item"><a href="{{url('/admin/payment-books')}}">payment-books</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ucwords($data->name)}} Books Data</li>
                </ol>
              </nav>
            </div>
          </div>

           @if (sizeof($data->book)<=0)
           
           <div class="row d-flex justify-content-center my-5">
                <p>{{$data->name}} doesn't have any <b> {{$data->grade->name . ' - ' . $data->grade->class}} </b> books at all. Press the button bellow to add a book for {{$data->name}}</p>
                
              </div>
              
            
            <div class="row d-flex justify-content-center my-5">
                <a role="button" href="/admin/payment-books/{{$data->unique_id}}/add-books" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i>
                    Add Book
                </a>
              </div>
              @else
              
              
              <div class="card card-dark mt-5 p-4">
                <div class="card-header">
                  <h3 class="card-title">Book List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                  <a role="button" href="/admin/payment-books/{{$data->unique_id}}/add-books" class="btn btn-success my-5 ml-1">
                    <i class="fa-solid fa-plus"></i>
                    Add Book
                  </a>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Book name</th>
                        <th>Grade</th>
                        <th>Date Added</th>
                </thead>
                <tbody>
                  
                  @foreach ($data->book as $el)
                  <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$el->name}}</td>
                    <td>
                        {{$data->grade->name . ' - ' . $data->grade->class}}
                      </td>
                      <td>
                        {{ date('l, d/m/Y', strtotime($el->pivot->created_at)) }}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
            </div>

          </div>
          <!-- /.card -->
          
          
        </section>
         </div>
         
         @endif
@endsection