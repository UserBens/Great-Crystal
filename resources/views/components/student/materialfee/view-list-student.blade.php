@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4">{{ ucwords(strtolower($type)) }} Student</h2>
        <form class="my-3" action="{{ route('payment.materialfee.create', ['type' => $type]) }}">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Grade:</label>
                                <select name="grade" class="form-control text-center" required>
                                    <option {{ $form->grade === 'all' ? 'selected' : '' }} value="all">-- All Grades --
                                    </option>
                                    @foreach ($grade as $el)
                                        <option {{ $form->grade == $el->id ? 'selected' : '' }} value="{{ $el->id }}">
                                            {{ $el->name . ' - ' . $el->class }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Sort order:</label>
                                <select name="sort" class="form-control">
                                    <option value="desc" {{ $form->sort === 'desc' ? 'selected' : '' }}>Descending
                                    </option>
                                    <option value="asc" {{ $form->sort === 'asc' ? 'selected' : '' }}>Ascending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Sort by:</label>
                                <select name="order" class="form-control">
                                    <option {{ $form->order === 'id' ? 'selected' : '' }} value="id">Register</option>
                                    <option {{ $form->order === 'name' ? 'selected' : '' }} value="name">Name</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" class="form-control text-center">
                                    <option {{ $form->status == 'all' ? 'selected' : '' }} value="all">-- All --
                                    </option>
                                    <option {{ $form->status == 'true' ? 'selected' : '' }} value="true">Already</option>
                                    <option {{ $form->status == 'false' ? 'selected' : '' }} value="false">Not yet
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input name="search" value="{{ $form->search }}" type="search"
                                class="form-control form-control-lg" placeholder="Type your keywords here">
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

        @if (sizeof($data) == 0)
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="mt-4 mb-5">
                        <a href="{{ route('payment.materialfee.create-form', ['type' => $type]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fa-solid fa-plus"></i> Create Material Fee
                        </a>
                    </div>
                    <h3>No payment records found!</h3>
                    <p class="text-muted">Click the button above to create a new payment material fee</p>
                </div>
            </div>
        @else
            <div class="btn-group">
                <a type="button" href="{{ route('payment.materialfee.create-form', ['type' => $type]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fa-solid fa-plus"></i> Create Material Fee
                </a>
            </div>

            <div class="card card-dark mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ ucwords(strtolower($type)) }} Student</h3>

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
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">Student</th>
                                <th>Grade</th>
                                <th style="width: 8%">Class</th>
                                <th style="width: 12%">Amount</th>
                                <th style="width: 12%">Installment</th>
                                <th style="width: 12%">Amount/Term</th>
                                <th style="width: 7%">Type</th>
                                <th style="width: 7%">Status</th>
                                <th class="text-center" style="width: 30%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $payment)
                                <tr id={{ 'index_payment_' . $payment->id }}>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $payment->student->name }}</td>
                                    <td>{{ $payment->student->grade->name }}</td>
                                    <td>{{ $payment->student->grade->class }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $payment->installment ?? 0 }} terms</td>
                                    <td>Rp {{ number_format($payment->amount_installment, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($payment->type) }}</td>
                                    <td>
                                        <span class="badge badge-success">Already set</span>
                                    </td>
                                    <td class="project-actions text-center">
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('payment.materialfee.detail', $payment->student_id) }}">
                                            <i class="fas fa-folder"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4 px-3">
                <div class="mb-3">
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of
                    {{ $data->total() }} results
                </div>
                <div>
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            // SweetAlert for session success
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // SweetAlert for errors
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
