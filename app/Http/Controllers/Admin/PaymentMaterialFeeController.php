<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Grade;
use App\Models\Payment_materialfee;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMaterialFeeController extends Controller
{
    public function chooseTypeIndex(Request $request)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'payments',
                'child' => 'payment-materialfee',
            ]);

            // Get list of students
            $students = Student::with(['grade'])->get();

            return view('components.student.materialfee.choose-type-materialfee', compact('students'));
        } catch (Exception $err) {
            return dd($err);
        }
    }

    // public function listViewStudent(Request $request, $type)
    // {
    //     try {
    //         session()->flash('page', (object)[
    //             'page' => 'payments',
    //             'child' => 'payment-materialfee',
    //         ]);

    //         $selectedGrade = $request->grade ?? 'all';
    //         $selectedOrder = $request->sort ?? 'desc';
    //         $selectedSort = $request->order ?? 'id';
    //         $selectedStatus = $request->status ?? 'all';
    //         $search = $request->search ?? '';

    //         $query = Student::with(['grade', 'material_fee'])
    //             ->where('is_active', true);

    //         // Filter by grade
    //         if ($selectedGrade !== 'all') {
    //             $query->where('grade_id', $selectedGrade);
    //         }

    //         // Search functionality
    //         if ($search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         }

    //         // Sorting
    //         $query->orderBy($selectedSort, $selectedOrder);

    //         $data = $query->paginate(20);
    //         $grade = Grade::all();

    //         $form = (object)[
    //             'grade' => $selectedGrade,
    //             'sort' => $selectedOrder,
    //             'order' => $selectedSort,
    //             'status' => $selectedStatus,
    //             'search' => $search
    //         ];

    //         return view('components.student.materialfee.view-list-student', compact('data', 'grade', 'form', 'type'));
    //     } catch (Exception $err) {
    //         return dd($err);
    //     }
    // }


    public function listViewStudent(Request $request, $type)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'payments',
                'child' => 'payment-materialfee',
            ]);

            $selectedGrade = $request->grade ?? 'all';
            $selectedOrder = $request->sort ?? 'desc';
            $selectedSort = $request->order ?? 'id';
            $selectedStatus = $request->status ?? 'all';
            $search = $request->search ?? '';

            // Start with payment_materialfees and join with students and grades
            $query = Payment_materialfee::with(['student.grade'])
                ->whereHas('student', function ($q) {
                    $q->where('is_active', true);
                })
                ->where('type', $type);

            // Filter by grade
            if ($selectedGrade !== 'all') {
                $query->whereHas('student', function ($q) use ($selectedGrade) {
                    $q->where('grade_id', $selectedGrade);
                });
            }

            // Search functionality
            if ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            // Sorting
            if ($selectedSort === 'name') {
                $query->orderBy(
                    Student::select('name')
                        ->whereColumn('students.id', 'payment_materialfees.student_id')
                        ->limit(1),
                    $selectedOrder
                );
            } else {
                $query->orderBy($selectedSort, $selectedOrder);
            }

            $data = $query->paginate(20);
            $grade = Grade::all();

            $form = (object)[
                'grade' => $selectedGrade,
                'sort' => $selectedOrder,
                'order' => $selectedSort,
                'status' => $selectedStatus,
                'search' => $search
            ];

            return view('components.student.materialfee.view-list-student', compact('data', 'grade', 'form', 'type'));
        } catch (Exception $err) {
            return dd($err);
        }
    }



    public function viewCreateForm(Request $request, $type)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'payments',
                'child' => 'payment-materialfee',
            ]);

            $students = Student::where('is_active', true)
                ->with(['grade']) // Pastikan relasi grade dan class didefinisikan di model
                ->get();

            return view('components.student.materialfee.material-fee-form', compact('students', 'type'));
        } catch (Exception $err) {
            return dd($err);
        }
    }


    // Updated Controller Method
    public function storePaymentMaterialFee(Request $request, $type)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'student_id' => 'required',  // Validasi untuk select2
                'amount' => 'required',
                'dp' => 'required',
                'installment' => 'nullable|numeric|min:2|max:12',
                'agree' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get student data
            $student = Student::where('unique_id', $request->student_id)->firstOrFail();

            // Format amount and dp (remove thousand separator)
            $amount = (int) str_replace('.', '', $request->amount);
            $dp = (int) str_replace('.', '', $request->dp);

            // Validate formatted amount and dp
            if ($dp > $amount) {
                return redirect()
                    ->back()
                    ->withErrors(['dp' => 'DP cannot be greater than the total amount'])
                    ->withInput();
            }

            // Calculate discount if applicable
            $discount = $request->input('discount', 0); // Default to 0 if no discount provided
            $discountedAmount = $amount - $discount;

            // Handle installments if provided
            $installment = $request->installment ?? null;

            // Calculate amount_installment (per month installment amount)
            $amount_installment = 0;
            if ($installment) {
                $remainingAmount = $amount - $dp;
                $amount_installment = ceil($remainingAmount / $installment);
            }

            // Save to `payment_materialfees` table
            Payment_materialfee::create([
                'student_id' => $student->id,
                'type' => $type,
                'amount' => $discountedAmount,
                'dp' => $dp,
                'discount' => $discount,
                'installment' => $installment,
                'amount_installment' => $amount_installment
            ]);

            return redirect()
                ->route('payment.materialfee.create', ['type' => $type])
                ->with('success', 'Material fee payment plan has been created successfully');
        } catch (Exception $e) {
            // Log the error message to storage/logs/laravel.log
            Log::error('Error in storePaymentMaterialFee:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to create payment plan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showStudentMaterialFees($student_id)
    {
        try {
            // Get student data
            $student = Student::with(['grade'])->findOrFail($student_id);

            // Get all material fees for this student
            $materialFees = Payment_materialfee::where('student_id', $student_id)
                ->orderBy('type', 'asc')
                ->get();

            return view('components.student.materialfee.show-detail', compact('student', 'materialFees'));
        } catch (Exception $err) {
            return dd($err);
        }
    }
}