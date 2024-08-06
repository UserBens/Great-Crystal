@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card card-dark mt-5">
                        <div class="card-header">
                            <h3 class="card-title">List Expense</h3>
                        </div>
                        <div class="card-body">
                            <h4 class="p-3 m-0">Total Expens : Rp {{ number_format($totalAmountInvoiceSupplier, 0, ',', '.') }}</h4>

                            <div class="table-responsive">
                                <table class="table projects">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No. Invoice</th>
                                            <th>Supplier</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoiceSuppliers as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->no_invoice }}</td>
                                                <td>{{ $item->supplier->name }}</td>
                                                <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                                <td>{{ $item->date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-4">
                                    {{ $invoiceSuppliers->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
