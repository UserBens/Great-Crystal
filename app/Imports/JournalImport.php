<?php

namespace App\Imports;

use App\Models\Accountnumber;
use App\Models\Student;
use Exception;
use Carbon\Carbon;
use App\Models\Transaction_send;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction_receive;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction_transfer;
use App\Models\TransactionSendSupplier;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class JournalImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();

            foreach ($collection as $idx => $row) {
                // Ensure $row is an associative array
                $rowArray = $row->toArray();


                Log::info('Processing row: ' . json_encode($rowArray));

                if (!isset($rowArray['transaction_type'])) {
                    throw new Exception("Missing 'transaction_type' column at line " . ($idx + 1));
                }

                // Logging the raw date values for debugging
                Log::info('Raw date values - date: ' . ($rowArray['date'] ?? 'null') . ', deadline_invoice: ' . ($rowArray['deadline_invoice'] ?? 'null'));

                $transactionType = $rowArray['transaction_type'];

                // Reformat date columns before validation
                if (isset($rowArray['date'])) {
                    try {
                        $rowArray['date'] = $this->formatDate($rowArray['date']);
                    } catch (Exception $e) {
                        Log::error('Error parsing date at row ' . ($idx + 1) . ': ' . $rowArray['date']);
                        throw $e;
                    }
                }
                if (isset($rowArray['deadline_invoice'])) {
                    try {
                        $rowArray['deadline_invoice'] = $this->formatDate($rowArray['deadline_invoice']);
                    } catch (Exception $e) {
                        Log::error('Error parsing deadline_invoice at row ' . ($idx + 1) . ': ' . $rowArray['deadline_invoice']);
                        throw $e;
                    }
                }

                switch ($transactionType) {
                    case 'transfer':
                        $this->importTransfer($rowArray, $idx);
                        break;
                    case 'send':
                        $this->importSend($rowArray, $idx);
                        break;
                    case 'receive':
                        $this->importReceive($rowArray, $idx);
                        break;
                    default:
                        throw new Exception("Invalid transaction type '{$transactionType}' at line " . ($idx + 1));
                }
            }

            DB::commit();
            Session::flash('success', 'Data imported successfully');
        } catch (Exception $th) {
            Log::info('Error: ' . $th->getMessage());
            Session::flash('import_status', [
                'code' => 500,
                'msg' => 'Internal server error: ' . $th->getMessage(),
            ]);
            DB::rollBack();
        }
    }

    private function formatDate($date)
    {
        // Check if the date is in the Excel formula format
        if (preg_match('/^=DATE\((\d+),(\d+),(\d+)\)$/', $date, $matches)) {
            // Extract the year, month, and day from the formula
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];
            // Create a Carbon date and format it as d/m/Y
            return Carbon::create($year, $month, $day)->format('d/m/Y');
        }

        // Assuming the date comes in as Y-m-d format from Excel
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    private function importTransfer($row, $idx)
    {
        $validator = Validator::make($row, [
            'transaction_type' => 'required',
            'transfer_account_id' => 'required', // Menggunakan ID akun
            'deposit_account_id' => 'required',  // Menggunakan ID akun
            'no_transaction' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
        }

        // Mencari ID berdasarkan nama untuk transfer_account
        $transferAccount = AccountNumber::where('name', $row['transfer_account_id'])->first();
        if (!$transferAccount) {
            throw new Exception("Transfer account name not found at line " . ($idx + 1));
        }

        // Mencari ID berdasarkan name untuk deposit_account
        $depositAccount = AccountNumber::where('name', $row['deposit_account_id'])->first();
        if (!$depositAccount) {
            throw new Exception("Deposit account name not found at line " . ($idx + 1));
        }

        Transaction_transfer::create([
            'transfer_account_id' => $transferAccount->id, // Menggunakan ID dari nama akun
            'deposit_account_id' => $depositAccount->id,   // Menggunakan ID dari nama akun
            'no_transaction' => $row['no_transaction'],
            'amount' => $row['amount'],
            'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
            'description' => $row['description'],
        ]);
    }


    private function importSend($row, $idx)
    {
        $validator = Validator::make($row, [
            'transaction_type' => 'required',
            'transfer_account_id' => 'required',
            'deposit_account_id' => 'required',
            'no_transaction' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
            'transaction_send_supplier_id' => 'nullable',
            'deadline_invoice' => 'nullable|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
        }

        // Mencari ID berdasarkan nama untuk transfer_account
        $transferAccount = AccountNumber::where('name', $row['transfer_account_id'])->first();
        if (!$transferAccount) {
            throw new Exception("Transfer account name not found at line " . ($idx + 1));
        }

        // Mencari ID berdasarkan name untuk deposit_account
        $depositAccount = AccountNumber::where('name', $row['deposit_account_id'])->first();
        if (!$depositAccount) {
            throw new Exception("Deposit account name not found at line " . ($idx + 1));
        }

        // Mencari ID berdasarkan nama untuk supplier
        $transaction_send_supplier_id = TransactionSendSupplier::where('supplier_name', $row['transaction_send_supplier_id'])->first();
        if (!$transaction_send_supplier_id) {
            throw new Exception("Transaction Send Supplier name not found at line " . ($idx + 1));
        }

        Transaction_send::create([
           'transfer_account_id' => $transferAccount->id, // Menggunakan ID dari nama akun
            'deposit_account_id' => $depositAccount->id,   // Menggunakan ID dari nama akun
            'transaction_send_supplier_id' => $transaction_send_supplier_id->id,
            'no_transaction' => $row['no_transaction'],
            'amount' => $row['amount'],
            'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
            'deadline_invoice' => isset($row['deadline_invoice']) ? Carbon::createFromFormat('d/m/Y', $row['deadline_invoice']) : null,
            'description' => $row['description'],
        ]);
    }

    private function importReceive($row, $idx)
    {
        $validator = Validator::make($row, [
            'transaction_type' => 'required',
            'transfer_account_id' => 'required',
            'deposit_account_id' => 'required',
            'no_transaction' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
            'description' => 'required',
            'student_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
        }

        // Mencari ID berdasarkan nama untuk transfer_account
        $transferAccount = AccountNumber::where('name', $row['transfer_account_id'])->first();
        if (!$transferAccount) {
            throw new Exception("Transfer account name not found at line " . ($idx + 1));
        }

        // Mencari ID berdasarkan name untuk deposit_account
        $depositAccount = AccountNumber::where('name', $row['deposit_account_id'])->first();
        if (!$depositAccount) {
            throw new Exception("Deposit account name not found at line " . ($idx + 1));
        }

         // Mencari ID berdasarkan nama untuk student
         $student_id = Student::where('name', $row['student_id'])->first();
         if (!$student_id) {
             throw new Exception("Transaction Send Supplier name not found at line " . ($idx + 1));
         }

        Transaction_receive::create([
            'transfer_account_id' => $transferAccount->id, // Menggunakan ID dari nama akun
            'deposit_account_id' => $depositAccount->id,   // Menggunakan ID dari nama akun
            'no_transaction' => $row['no_transaction'],
            'student_id' => $student_id->id,
            'amount' => $row['amount'],
            'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
            'description' => $row['description'],
        ]);
    }












    // public function collection(Collection $collection)
    // {
    //     try {
    //         DB::beginTransaction();

    //         foreach ($collection as $idx => $row) {
    //             // Ensure $row is an associative array
    //             $rowArray = $row->toArray();


    //             Log::info('Processing row: ' . json_encode($rowArray));

    //             if (!isset($rowArray['transaction_type'])) {
    //                 throw new Exception("Missing 'transaction_type' column at line " . ($idx + 1));
    //             }

    //             // Logging the raw date values for debugging
    //             Log::info('Raw date values - date: ' . ($rowArray['date'] ?? 'null') . ', deadline_invoice: ' . ($rowArray['deadline_invoice'] ?? 'null'));

    //             $transactionType = $rowArray['transaction_type'];

    //             // Reformat date columns before validation
    //             if (isset($rowArray['date'])) {
    //                 try {
    //                     $rowArray['date'] = $this->formatDate($rowArray['date']);
    //                 } catch (Exception $e) {
    //                     Log::error('Error parsing date at row ' . ($idx + 1) . ': ' . $rowArray['date']);
    //                     throw $e;
    //                 }
    //             }
    //             if (isset($rowArray['deadline_invoice'])) {
    //                 try {
    //                     $rowArray['deadline_invoice'] = $this->formatDate($rowArray['deadline_invoice']);
    //                 } catch (Exception $e) {
    //                     Log::error('Error parsing deadline_invoice at row ' . ($idx + 1) . ': ' . $rowArray['deadline_invoice']);
    //                     throw $e;
    //                 }
    //             }

    //             switch ($transactionType) {
    //                 case 'transfer':
    //                     $this->importTransfer($rowArray, $idx);
    //                     break;
    //                 case 'send':
    //                     $this->importSend($rowArray, $idx);
    //                     break;
    //                 case 'receive':
    //                     $this->importReceive($rowArray, $idx);
    //                     break;
    //                 default:
    //                     throw new Exception("Invalid transaction type '{$transactionType}' at line " . ($idx + 1));
    //             }
    //         }

    //         DB::commit();
    //         Session::flash('success', 'Data imported successfully');
    //     } catch (Exception $th) {
    //         Log::info('Error: ' . $th->getMessage());
    //         Session::flash('import_status', [
    //             'code' => 500,
    //             'msg' => 'Internal server error: ' . $th->getMessage(),
    //         ]);
    //         DB::rollBack();
    //     }
    // }

    // private function formatDate($date)
    // {
    //     // Check if the date is in the Excel formula format
    //     if (preg_match('/^=DATE\((\d+),(\d+),(\d+)\)$/', $date, $matches)) {
    //         // Extract the year, month, and day from the formula
    //         $year = $matches[1];
    //         $month = $matches[2];
    //         $day = $matches[3];
    //         // Create a Carbon date and format it as d/m/Y
    //         return Carbon::create($year, $month, $day)->format('d/m/Y');
    //     }

    //     // Assuming the date comes in as Y-m-d format from Excel
    //     return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    // }

    // private function importTransfer($row, $idx)
    // {
    //     $validator = Validator::make($row, [
    //         'transaction_type' => 'required',
    //         'transfer_account_name' => 'required',
    //         'deposit_account_name' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
    //     }

    //     // Get transfer account ID
    //     $transferAccount = Accountnumber::where('name', $row['transfer_account_name'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account not found for account name '{$row['transfer_account_name']}' at line " . ($idx + 1));
    //     }

    //     // Get deposit account ID
    //     $depositAccount = Accountnumber::where('name', $row['deposit_account_name'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account not found for account name '{$row['deposit_account_name']}' at line " . ($idx + 1));
    //     }

    //     Transaction_transfer::create([
    //         'transfer_account_id' => $transferAccount->id,
    //         'deposit_account_id' => $depositAccount->id,
    //         'no_transaction' => $row['no_transaction'],
    //         'amount' => $row['amount'],
    //         'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
    //         'description' => $row['description'],
    //     ]);
    // }


    // private function importSend($row, $idx)
    // {
    //     $validator = Validator::make($row, [
    //         'transaction_type' => 'required',
    //         'transfer_account_name' => 'required',
    //         'deposit_account_name' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //         'transaction_send_supplier_name' => 'nullable',
    //         'deadline_invoice' => 'nullable|date_format:d/m/Y',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
    //     }

    //     // Get transfer account ID
    //     $transferAccount = Accountnumber::where('name', $row['transfer_account_name'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account not found for account name '{$row['transfer_account_name']}' at line " . ($idx + 1));
    //     }

    //     // Get deposit account ID
    //     $depositAccount = Accountnumber::where('name', $row['deposit_account_name'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account not found for account name '{$row['deposit_account_name']}' at line " . ($idx + 1));
    //     }

    //     $transactionSendSupplier = null;
    //     if (isset($row['transaction_send_supplier_name'])) {
    //         // Get transaction send supplier ID
    //         $transactionSendSupplier = TransactionSendSupplier::where('supplier_name', $row['transaction_send_supplier_name'])->first();
    //         if (!$transactionSendSupplier) {
    //             throw new Exception("Supplier not found for supplier name '{$row['transaction_send_supplier_name']}' at line " . ($idx + 1));
    //         }
    //     }

    //     Transaction_send::create([
    //         'transfer_account_id' => $transferAccount->id,
    //         'deposit_account_id' => $depositAccount->id,
    //         'transaction_send_supplier_id' => $transactionSendSupplier ? $transactionSendSupplier->id : null,
    //         'no_transaction' => $row['no_transaction'],
    //         'amount' => $row['amount'],
    //         'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
    //         'deadline_invoice' => isset($row['deadline_invoice']) ? Carbon::createFromFormat('d/m/Y', $row['deadline_invoice']) : null,
    //         'description' => $row['description'],
    //     ]);
    // }



    // private function importReceive($row, $idx)
    // {
    //     $validator = Validator::make($row, [
    //         'transaction_type' => 'required',
    //         'transfer_account_name' => 'required',
    //         'deposit_account_name' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //         'student_name' => 'nullable',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . ($idx + 1) . ": " . $validator->errors()->first());
    //     }

    //     // Get transfer account ID
    //     $transferAccount = Accountnumber::where('name', $row['transfer_account_name'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account not found for account name '{$row['transfer_account_name']}' at line " . ($idx + 1));
    //     }

    //     // Get deposit account ID
    //     $depositAccount = Accountnumber::where('name', $row['deposit_account_name'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account not found for account name '{$row['deposit_account_name']}' at line " . ($idx + 1));
    //     }

    //     $student = null;
    //     if (isset($row['student_name'])) {
    //         // Get student ID
    //         $student = Student::where('name', $row['student_name'])->first();
    //         if (!$student) {
    //             throw new Exception("Student not found for student name '{$row['student_name']}' at line " . ($idx + 1));
    //         }
    //     }

    //     Transaction_receive::create([
    //         'transfer_account_id' => $transferAccount->id,
    //         'deposit_account_id' => $depositAccount->id,
    //         'no_transaction' => $row['no_transaction'],
    //         'student_id' => $student ? $student->id : null,
    //         'amount' => $row['amount'],
    //         'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
    //         'description' => $row['description'],
    //     ]);
    // }
}
