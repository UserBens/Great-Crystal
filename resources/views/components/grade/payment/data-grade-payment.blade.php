@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

    <h2 class="text-center display-4">Grades Search</h2>
           <form class="my-5" action="/admin/payment-grades">
               <div class="row">
                   <div class="col-md-10 offset-md-2">
                       <div class="row">
                           <div class="col-7">
                               <div class="form-group">
                                   <label>Grades:</label>
                                   @php
                                       
                                       $selected = $form && $form->grade ? $form->grade : 'all';

                                    @endphp
                                    <select name="grade" class="form-control form-control-lg ">
                                        <option {{$selected === 'all' ? 'selected' : ''}} value="">none</option>

                                        @foreach ($grades as $grade)
                                        <option {{$selected === $grade->name ? 'selected' : ''}}>{{$grade->name}}</option>
                                        @endforeach
                                    </select>
                                  
                               </div>
                           </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Class:</label>
                                <div class="input-group input-group-lg">
                                    <input name="class" value="{{$form && $form->class? $form->class : ""}}" type="search" class="form-control form-control-lg" placeholder="Class">
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

           @if (sizeof($data)<=0)

           <div class="col-sm-12 my-auto text-center m-5">
            <h3>The payment grades you are looking for does not exist !!!</h3>
        </div>
               
           @else


    <div class="card card-dark mt-5">
        <div class="card-header">
            <h3 class="card-title">Payment Grades</h3>

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
                        <th style="width: 10%">
                           #
                        </th>
                        <th style="width: 20%">
                            Grades
                        </th>
                        <th style="width: 10%">
                            Class
                        </th>
                        <th>
                            SPP
                        </th>
                        <th>
                            Paket
                        </th>
                        <th>
                            Uniform
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
                            <a>
                                {{$el->class}}
                            </a>
                        </td>
                        <td>
                           <a>
                                @if ($el->spp)
                                    <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                                @else
                                <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                                @endif
                           </a>
                        </td>
                        <td>
                           <a>
                            @if ($el->bundle)
                            <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                            @else
                            <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                            @endif
                           </a>
                        </td>
                        <td>
                           <a>
                            @if ($el->uniform)
                            <i class="fa-solid fa-circle-check fa-2xl" style="color: #32c81e;"></i>
                            @else
                            <i class="fa-solid fa-circle-xmark fa-fade fa-2xl" style="color: #bd0000;"></i>
                            @endif
                           </a>
                        </td>
                        
                        
                        <td class="project-actions text-center toastsDefaultSuccess">
                           
                           <a class="btn btn-info btn-xl"
                              href="{{url('/admin/payment-grades') . '/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fa-solid fa-file-invoice fa-lg" style="margin-right: 10px;"></i>
                              Data payment
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

           
@endif

@endsection
