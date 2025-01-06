@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>Book</h3>
                            <p>Book Costs</p>
                        </div>
                        <div class="icon">
                            <i class="fa-regular fa-calendar-plus"></i>
                        </div>
                        <a href="{{ route('payment.materialfee.create', 'book') }}" class="small-box-footer">Create Book cost <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>

                </div>
                <div class="col-lg-6 col-6">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>Uniform</h3>
                            <p>Uniform costs</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-child-dress"></i>
                        </div>
                        <a href="{{ route('payment.materialfee.create', 'uniform') }}" class="small-box-footer">Create
                            uniform
                            cost <i class="fas fa-arrow-circle-right"></i></a>

                    </div>
                </div>
                <div class="col-lg-12 col-12">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>Paket<sup style="font-size: 20px"></sup></h3>
                            <p>Paket cost of uniform and book</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <a href="{{ route('payment.materialfee.create', 'paket') }}" class="small-box-footer">Create Paket
                            cost
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
