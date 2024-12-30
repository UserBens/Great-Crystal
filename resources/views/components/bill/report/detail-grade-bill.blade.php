@extends('layouts.admin.master')

@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4 mb-3">Student Bills - {{ $current_grade->name }} {{ $current_grade->class }}</h2>
        <form action="{{ route('reports.grade-bills', ['grade_id' => $current_grade->id]) }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="status">Payment Status</label>
                    <select name="status" class="form-control" id="status-select">
                        <option value="">-- All Status --</option>
                        <option value="true" {{ $form->status === 'true' ? 'selected' : '' }}>Paid</option>
                        <option value="false" {{ $form->status === 'false' ? 'selected' : '' }}>Not Yet</option>
                    </select>
                    <input type="hidden" name="order" id="sort-order" value="{{ $form->order }}">
                </div>

                <div class="col-md-3">
                    <label for="sort">Sort By</label>
                    <select name="sort" class="form-control" id="sort-select">
                        <option value="">Default</option>
                        <option value="oldest" {{ $form->sort === 'oldest' ? 'selected' : '' }}>Date (Oldest First)</option>
                        <option value="newest" {{ $form->sort === 'newest' ? 'selected' : '' }}>Date (Newest First)</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $form->date ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date">Search Data</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                            value="{{ $form->search ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Conditional rendering based on data availability -->
        @if (sizeof($data) == 0 && ($form->type || $form->sort || $form->order || $form->status || $form->search))
            <!-- Display message when no data found based on search criteria -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>Not found on your search criteria!</h3>
                </div>
            </div>
        @elseif (sizeof($data) == 0)
            <!-- Display message when no bills found -->
            <div class="row h-100 my-5">
                <div class="col-sm-12 my-auto text-center">
                    <h3>No bills available!</h3>
                    <div class="btn-group">
                        <a type="button" href="{{ route('reports.grade-bills', ['grade_id' => $current_grade->id]) }}"
                            class="btn btn-primary btn-sm mt-3">
                            <i class="fas fa-sync"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Display Bills data in a table -->
            <div class="card card-dark mt-4">
                <div class="card-header">
                    <h3 class="card-title">Student Bills List - {{ $current_grade->name }} {{ $current_grade->class }}
                    </h3>
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
                                <th style="width: 2%">#</th>
                                <th style="width: 20%">Student Name</th>
                                <th style="width: 5%">Bill Type</th>
                                <th style="width: 10%">Amount</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 10%">Due Date</th>
                                <th style="width: 8%">Paid Date</th>
                                <th style="width: 6%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bill)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a>{{ $bill->student->name }}</a>
                                    </td>
                                    <td>{{ $bill->type }}</td>
                                    <td>Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($bill->paidOf)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-danger">Not Yet</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($bill->deadline_invoice)->format('j F Y') }}</td>
                                    <td>
                                        @if ($bill->paidOf)
                                            {{ \Carbon\Carbon::parse($bill->paid_date)->format('j F Y') }}
                                        @else
                                            <div class="text-danger">
                                                <small><i class="fas fa-exclamation-circle"></i> Belum terbayar</small><br>
                                                <small><i class="fas fa-exclamation-triangle"></i> Terkena charge</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('reports.student-bill-detail', $bill->student_id) }}">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between mt-4 px-3">
                        <div class="mb-3">
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of
                            {{ $data->total() }} results
                        </div>
                        <div>
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
@endsection
