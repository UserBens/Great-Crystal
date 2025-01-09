@extends('layouts.admin.master')
@section('content')
    <section>
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4" style="background-color: #ffffff;">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('payment.materialfee.create', ['type' => $type]) }}">Material Fee</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Material Fee </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <form id="paymentForm" method="POST"
                        action="{{ route('payment.materialfee.store', ['type' => $type]) }}">
                        @csrf
                        <div class="card mb-4">
                            <div class="card-header bg-orange ">
                                <h3 class="card-title text-white">Create Material Fee </h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Select Student</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="students" name="student_id">
                                            <option value="" disabled selected>Select a student</option>
                                            @foreach ($students as $activeStudent)
                                                <option value="{{ $activeStudent->unique_id }}">
                                                    {{ $activeStudent->name }} -
                                                    {{ $activeStudent->grade->name ?? 'N/A' }} -
                                                    {{ $activeStudent->grade->class ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Amount</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="amount" type="text" class="form-control" id="amount"
                                                placeholder="Enter amount Material fee" value="{{ old('amount') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('amount') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Dp</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input name="dp" type="text" class="form-control" id="dp"
                                                placeholder="Enter Dp" value="{{ old('dp') ?? '' }}">
                                        </div>
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('dp') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="mb-0">Installment Terms</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input name="installment" type="number" class="form-control" id="installment"
                                            placeholder="Number of installments" value="{{ old('installment') }}"
                                            max="12" min="2">
                                        @if ($errors->any())
                                            <p style="color: red">{{ $errors->first('installment') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div id="installmentContainer" style="display: none">
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <h5 class="mb-4">Installment Details</h5>
                                            <!-- Installment fields will be generated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agree" name="agree" required>
                                <label for="agree">
                                    I agree to create this payment plan
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div id="summaryContainer" class="card mb-4" style="display: none">
                        <div class="card-header">
                            <h3 class="card-title">Payment Summary</h3>
                        </div>
                        <div class="card-body">
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
                                        <td align="left" class="p-1">Amount:</td>
                                        <td align="right" class="p-1" id="summaryAmount">Rp. 0</td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="p-1">Down Payment:</td>
                                        <td align="right" class="p-1" id="summaryDP">Rp. 0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" class="p-1 font-weight-bold">Total:</td>
                                        <td align="right" class="p-1 font-weight-bold" id="summaryTotal">Rp. 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary w-100" data-target="#confirmModal">
                        <i class="fas fa-check mr-2"></i>
                        Create Payment Plan
                    </button>
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

            function validateInstallments() {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);
                const remainingAmount = amount - dp;
                let currentTotal = 0;

                // Get all installment inputs
                const inputs = document.querySelectorAll('.installment-input');
                inputs.forEach(input => {
                    currentTotal += unformatNumber(input.value);
                });

                // Check if total matches remaining amount
                return {
                    isValid: currentTotal === remainingAmount,
                    difference: remainingAmount - currentTotal
                };
            }

            // Calculate remaining amount after installment changes
            function updateInstallmentSummary() {
                const amount = unformatNumber(amountInput.value);
                const dp = unformatNumber(dpInput.value);
                const remainingAmount = amount - dp;

                // Calculate total of installments
                let installmentTotal = 0;
                const inputs = document.querySelectorAll('.installment-input');
                inputs.forEach(input => {
                    installmentTotal += unformatNumber(input.value);
                });

                // Update summary display
                document.getElementById('summaryAmount').innerHTML = `<b>Rp. ${formatNumber(remainingAmount)}</b>`;
                document.getElementById('summaryDP').innerHTML = `Rp. ${formatNumber(dp)}`;
                document.getElementById('summaryTotal').innerHTML = `<strong>Rp. ${formatNumber(amount)}</strong>`;

                // Update summary table
                const summaryTable = document.getElementById('installmentSummary');
                let summaryHtml = '';

                inputs.forEach((input, index) => {
                    summaryHtml += `
                        <tr>
                            <td align="left">Installment ${index + 1}</td>
                            <td align="right">Rp. ${input.value}</td>
                        </tr>
                    `;
                });

                summaryTable.innerHTML = summaryHtml;

                // Show validation message if totals don't match
                const validation = validateInstallments();
                const validationMessage = document.getElementById('validationMessage');
                if (!validation.isValid) {
                    validationMessage.textContent =
                        `Difference from total: Rp. ${formatNumber(Math.abs(validation.difference))}`;
                    validationMessage.style.color = 'red';
                } else {
                    validationMessage.textContent = 'Total matches remaining amount';
                    validationMessage.style.color = 'green';
                }
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
                const installmentAmount = Math.ceil(remainingAmount / installments);
                const lastInstallmentAmount = remainingAmount - (installmentAmount * (installments - 1));

                // Show containers
                installmentContainer.style.display = 'block';
                summaryContainer.style.display = 'block';

                // Generate installment fields
                const container = document.getElementById('installmentContainer');
                let installmentHtml = `
                    <div id="validationMessage" class="mb-3"></div>
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
                                        id="installment_${i}" 
                                        name="installment_amount[]" 
                                        value="${formatNumber(currentAmount)}" 
                                        oninput="handleInstallmentInput(event)">
                                </div>
                            </div>
                        </div>
                    `;
                }

                container.innerHTML = installmentHtml;

                // Add event listeners to new inputs
                document.querySelectorAll('.installment-input').forEach(input => {
                    input.addEventListener('input', handleInstallmentInput);
                });

                // Initial summary update
                updateInstallmentSummary();
            }

            // Add form submit handler
            const form = document.getElementById('paymentForm');
            const submitBtn = document.querySelector('[data-target="#confirmModal"]');

            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validate totals before submission
                const validation = validateInstallments();
                if (!validation.isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Installment Total',
                        text: 'The sum of installments must equal the remaining amount.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

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
