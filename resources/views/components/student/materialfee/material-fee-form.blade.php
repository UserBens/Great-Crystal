{{-- @extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <form method="POST"
                        action="{{ route('payment.materialfee.store', ['student_id' => $student->unique_id, 'type' => $type]) }}">
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header" style="background-color: #e85500">
                                <h3 class="card-title">{{ $type }} Fee for {{ $student->name }}</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount capital fee" value="{{ old('amount') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('amount') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="dp">DP</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="dp" type="text" class="form-control" id="dp"
                                                placeholder="Enter done payment" value="{{ old('dp') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('dp') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="installment">Installment / Month</label>
                                        <input name="installment" type="number" class="form-control" id="installment"
                                            placeholder="(Cicilan)" value="{{ old('installment') }}" max="12"
                                            min="2">
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('installment') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div id="installmentContainer" class="mt-4" style="display: none">
                                    <hr>
                                    <h5 class="mb-4">Installment Details</h5>
                                    <!-- Installment fields will be generated here -->
                                </div>

                                <!-- Tambahkan div untuk summary setelah installmentContainer -->
                                <div id="summaryContainer" class="mt-4" style="display: none">
                                    <hr>
                                    <h5 class="mb-4">Payment Summary</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless">
                                                <tbody id="installmentSummary">
                                                    <!-- Installment summary will be generated here -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><b>Amount</b></td>
                                                        <td align="right" id="summaryAmount"><b>Rp. 0</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left">Done Payment (DP)</td>
                                                        <td align="right" id="summaryDP">Rp. 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><strong>Total</strong></td>
                                                        <td align="right" id="summaryTotal"><strong>Rp. 0</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-4">
                                    <div class="col-12">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="agree" name="agree" required>
                                            <label for="agree">
                                                I agree to create this payment plan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Modal trigger button -->
                        <div class="d-flex justify-content-end my-3">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#confirmModal">
                                Create Payment Plan
                            </button>
                        </div>

                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Payment Plan</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to create this payment plan for {{ $student->name }}?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Yes, Create</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Format currency input
            function formatCurrency(input) {
                let value = input.value.replace(/[^\d]/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                input.value = value;
            }

            // Add event listeners to format currency inputs
            document.getElementById('amount').addEventListener('input', function() {
                formatCurrency(this);
            });

            document.getElementById('dp').addEventListener('input', function() {
                formatCurrency(this);
            });
        </script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const dpInput = document.getElementById('dp');
            const installmentInput = document.getElementById('installment');
            const installmentContainer = document.getElementById('installmentContainer');
            const summaryContainer = document.getElementById('summaryContainer');
            let installmentInputs = [];

            // Format number with thousand separator
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Remove format and convert to number
            function unformatNumber(str) {
                return parseInt(str.replace(/\./g, '')) || 0;
            }

            // Format currency inputs
            [amountInput, dpInput].forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = unformatNumber(this.value);
                    this.value = formatNumber(value);
                    generateInstallments();
                });
            });

            installmentInput.addEventListener('input', generateInstallments);

            // Calculate remaining amount after installment changes
            function updateInstallmentSummary(installmentAmount, lastInstallmentAmount, installments) {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);

                // Update summary
                document.getElementById('summaryAmount').innerHTML = `<b>Rp. ${formatNumber(amount - dp)}</b>`;
                document.getElementById('summaryDP').innerHTML = `Rp. ${formatNumber(dp)}`;
                document.getElementById('summaryTotal').innerHTML = `<strong>Rp. ${formatNumber(amount)}</strong>`;

                // Update summary table
                const summaryTable = document.getElementById('installmentSummary');
                let summaryHtml = '';

                for (let i = 0; i < installments; i++) {
                    const currentAmount = i === installments - 1 ? lastInstallmentAmount : installmentAmount;
                    summaryHtml += `
                        <tr>
                            <td align="left">Installment ${i + 1}</td>
                            <td align="right">Rp. ${formatNumber(currentAmount)}</td>
                        </tr>
                    `;
                }

                summaryTable.innerHTML = summaryHtml;
            }

            // Handle installment input changes
            function handleInstallmentInput(e) {
                let value = unformatNumber(e.target.value);
                e.target.value = formatNumber(value);
                updateInstallmentSummary();
            }

            // Generate installment fields and summary
            function generateInstallments() {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);
                const installments = parseInt(installmentInput.value) || 0;

                installmentContainer.style.display = 'none';
                summaryContainer.style.display = 'none';

                if (!amount || !dp || !installments || installments < 2 || installments > 12 || dp >= amount) {
                    return;
                }

                const remainingAmount = amount - dp;
                const installmentAmount = Math.ceil(remainingAmount / installments); // Changed to Math.ceil
                const lastInstallmentAmount = remainingAmount - (installmentAmount * (installments - 1));

                // Show containers
                installmentContainer.style.display = 'block';
                summaryContainer.style.display = 'block';

                // Generate installment fields
                const container = document.getElementById('installmentContainer');
                let installmentHtml = '';

                // Add hidden input for amount_installment
                installmentHtml += `
                    <input type="hidden" name="amount_installment" value="${installmentAmount}">
                         `;

                for (let i = 0; i < installments; i++) {
                    const currentAmount = i === installments - 1 ? lastInstallmentAmount : installmentAmount;

                    installmentHtml += `
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="installment_${i}">Installment ${i + 1}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control installment-input" 
                                        id="installment_${i}" value="${formatNumber(currentAmount)}" readonly>
                                </div>
                            </div>
                        </div>
                    `;
                }

                container.innerHTML = installmentHtml;

                // Update summary
                updateInstallmentSummary(installmentAmount, lastInstallmentAmount, installments);
            }

            // Add form submit handler
            document.querySelector('form').addEventListener('submit', function(e) {

            });
        });
    </script>
@endsection --}}


@extends('layouts.admin.master')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item"><a href="{{ url('/admin/payment-materialfee') }}">Material Fee</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Material Fee Package</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <form id="paymentForm" method="POST"
                        action="{{ route('payment.materialfee.store', ['type' => $type]) }}">

                        @csrf
                        <div class="card card-dark">
                            <div class="card-header" style="background-color: #e85500">
                                <h5>Create Material Fee Package</h5>
                            </div>

                            <div class="card-body">
                                <div class="form-group row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="students">Select Active Student</label>
                                            <select class="form-control select2" id="students" name="student_id">
                                                <option value="" disabled selected>Select a student</option>
                                                @foreach ($students as $activeStudent)
                                                    <option value="{{ $activeStudent->unique_id }}">
                                                        {{ $activeStudent->name }}
                                                        - {{ $activeStudent->grade->name ?? 'N/A' }}
                                                        - {{ $activeStudent->grade->class ?? 'N/A' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount capital fee" value="{{ old('amount') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('amount') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="dp">DP</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="dp" type="text" class="form-control" id="dp"
                                                placeholder="Enter done payment" value="{{ old('dp') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('dp') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="installment">Installment / Month</label>
                                        <input name="installment" type="number" class="form-control" id="installment"
                                            placeholder="(Cicilan)" value="{{ old('installment') }}" max="12"
                                            min="2">
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('installment') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div id="installmentContainer" class="mt-4" style="display: none">
                                    <hr>
                                    <h5 class="mb-4">Installment Details</h5>
                                    <!-- Installment fields will be generated here -->
                                </div>

                                <!-- Tambahkan div untuk summary setelah installmentContainer -->
                                <div id="summaryContainer" class="mt-4" style="display: none">
                                    <hr>
                                    <h5 class="mb-4">Payment Summary</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless">
                                                <tbody id="installmentSummary">
                                                    <!-- Installment summary will be generated here -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><b>Amount</b></td>
                                                        <td align="right" id="summaryAmount"><b>Rp. 0</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left">Done Payment (DP)</td>
                                                        <td align="right" id="summaryDP">Rp. 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><strong>Total</strong></td>
                                                        <td align="right" id="summaryTotal"><strong>Rp. 0</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-4">
                                    <div class="col-12">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="agree" name="agree" required>
                                            <label for="agree">
                                                I agree to create this payment plan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Modal trigger button -->
                        <div class="d-flex justify-content-end my-3">
                            <button type="button" class="btn btn-primary btn-sm" data-target="#confirmModal">
                                Create Payment Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Format currency input
            function formatCurrency(input) {
                let value = input.value.replace(/[^\d]/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                input.value = value;
            }

            // Add event listeners to format currency inputs
            document.getElementById('amount').addEventListener('input', function() {
                formatCurrency(this);
            });

            document.getElementById('dp').addEventListener('input', function() {
                formatCurrency(this);
            });
        </script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const dpInput = document.getElementById('dp');
            const installmentInput = document.getElementById('installment');
            const installmentContainer = document.getElementById('installmentContainer');
            const summaryContainer = document.getElementById('summaryContainer');
            let installmentInputs = [];

            // Format number with thousand separator
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Remove format and convert to number
            function unformatNumber(str) {
                return parseInt(str.replace(/\./g, '')) || 0;
            }

            // Format currency inputs
            [amountInput, dpInput].forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = unformatNumber(this.value);
                    this.value = formatNumber(value);
                    generateInstallments();
                });
            });

            installmentInput.addEventListener('input', generateInstallments);

            // Calculate remaining amount after installment changes
            function updateInstallmentSummary(installmentAmount, lastInstallmentAmount, installments) {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);

                // Update summary
                document.getElementById('summaryAmount').innerHTML = `<b>Rp. ${formatNumber(amount - dp)}</b>`;
                document.getElementById('summaryDP').innerHTML = `Rp. ${formatNumber(dp)}`;
                document.getElementById('summaryTotal').innerHTML = `<strong>Rp. ${formatNumber(amount)}</strong>`;

                // Update summary table
                const summaryTable = document.getElementById('installmentSummary');
                let summaryHtml = '';

                for (let i = 0; i < installments; i++) {
                    const currentAmount = i === installments - 1 ? lastInstallmentAmount : installmentAmount;
                    summaryHtml += `
                        <tr>
                            <td align="left">Installment ${i + 1}</td>
                            <td align="right">Rp. ${formatNumber(currentAmount)}</td>
                        </tr>
                    `;
                }

                summaryTable.innerHTML = summaryHtml;
            }

            // Handle installment input changes
            function handleInstallmentInput(e) {
                let value = unformatNumber(e.target.value);
                e.target.value = formatNumber(value);
                updateInstallmentSummary();
            }

            // Generate installment fields and summary
            function generateInstallments() {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);
                const installments = parseInt(installmentInput.value) || 0;

                installmentContainer.style.display = 'none';
                summaryContainer.style.display = 'none';

                if (!amount || !dp || !installments || installments < 2 || installments > 12 || dp >= amount) {
                    return;
                }

                const remainingAmount = amount - dp;
                const installmentAmount = Math.ceil(remainingAmount / installments); // Changed to Math.ceil
                const lastInstallmentAmount = remainingAmount - (installmentAmount * (installments - 1));

                // Show containers
                installmentContainer.style.display = 'block';
                summaryContainer.style.display = 'block';

                // Generate installment fields
                const container = document.getElementById('installmentContainer');
                let installmentHtml = '';

                // Add hidden input for amount_installment
                installmentHtml += `
                    <input type="hidden" name="amount_installment" value="${installmentAmount}">
                         `;

                for (let i = 0; i < installments; i++) {
                    const currentAmount = i === installments - 1 ? lastInstallmentAmount : installmentAmount;

                    installmentHtml += `
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="installment_${i}">Installment ${i + 1}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control installment-input" 
                                        id="installment_${i}" value="${formatNumber(currentAmount)}" readonly>
                                </div>
                            </div>
                        </div>
                    `;
                }

                container.innerHTML = installmentHtml;

                // Update summary
                updateInstallmentSummary(installmentAmount, lastInstallmentAmount, installments);
            }

            // Add form submit handler
            const form = document.getElementById('paymentForm');
            const submitBtn = document.querySelector('[data-target="#confirmModal"]');

            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validasi checkbox agree
                const agreeCheckbox = document.getElementById('agree');
                if (!agreeCheckbox.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Agreement Required',
                        text: 'Please agree to create this payment plan first.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Validasi student
                const studentSelect = document.getElementById('students');
                if (!studentSelect.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Student Required',
                        text: 'Please select a student first.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Ambil nilai-nilai form untuk ditampilkan di konfirmasi
                const studentName = studentSelect.options[studentSelect.selectedIndex].text;
                const amount = document.getElementById('summaryAmount').textContent;
                const dp = document.getElementById('summaryDP').textContent;
                const installment = document.getElementById('installment').value;

                Swal.fire({
                    title: 'Confirm Payment Plan',
                    html: `
                <div class="text-left">
                    <p><strong>Student:</strong> ${studentName}</p>
                    <p><strong>Amount:</strong> ${amount}</p>
                    <p><strong>DP:</strong> ${dp}</p>
                    <p><strong>Installment:</strong> ${installment} terms</p>
                </div>
                <p class="mt-3">Are you sure you want to create this payment plan?</p>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while we create the payment plan.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        form.submit();
                    }
                });
            });

            // Add form submit handler
            document.querySelector('form').addEventListener('submit', function(e) {

            });
        });
    </script>
@endsection
