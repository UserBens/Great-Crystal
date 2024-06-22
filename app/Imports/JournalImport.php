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


    // GAK KENEK
    // public function collection(Collection $rows)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Iterate through rows after the header row
    //         foreach ($rows as $idx => $row) {
    //             $rowArray = $row->toArray();

    //             Log::info('Processing row: ' . json_encode($rowArray));

    //             // Validate required columns
    //             if (!isset($rowArray['transaction_type']) || empty($rowArray['transaction_type'])) {
    //                 throw new Exception("Missing 'transaction_type' column at line " . ($idx + 2));
    //             }

    //             // Handle date formatting
    //             if (isset($rowArray['date'])) {
    //                 try {
    //                     $rowArray['date'] = $this->formatDate($rowArray['date']);
    //                 } catch (Exception $e) {
    //                     Log::error('Error parsing date at row ' . ($idx + 2) . ': ' . $rowArray['date']);
    //                     throw $e;
    //                 }
    //             }

    //             // Call appropriate import function based on transaction type
    //             switch ($rowArray['transaction_type']) {
    //                 case 'transfer':
    //                     $this->importTransfer($rowArray, $idx + 2); // +2 because Excel rows are 1-based and we skipped heading row
    //                     break;
    //                 case 'send':
    //                     $this->importSend($rowArray, $idx + 2);
    //                     break;
    //                 case 'receive':
    //                     $this->importReceive($rowArray, $idx + 2);
    //                     break;
    //                 default:
    //                     throw new Exception("Invalid transaction type '{$rowArray['transaction_type']}' at line " . ($idx + 2));
    //             }
    //         }

    //         DB::commit();
    //         Session::flash('success', 'Data imported successfully');
    //     } catch (Exception $th) {
    //         Log::error('Error: ' . $th->getMessage());
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

    //     // Assuming the date comes in as d/m/Y format from Excel
    //     return $date;
    // }

    // private function importTransfer($row, $idx)
    // {
    //     $validator = Validator::make($row, [
    //         'transaction_type' => 'required',
    //         'transfer_account' => 'required',
    //         'deposit_account' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . $idx . ": " . $validator->errors()->first());
    //     }

    //     // Find transfer_account and deposit_account IDs
    //     $transferAccount = AccountNumber::where('name', $row['transfer_account'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account name not found at line " . $idx);
    //     }

    //     $depositAccount = AccountNumber::where('name', $row['deposit_account'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account name not found at line " . $idx);
    //     }

    //     // Create transaction record
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
    //         'transfer_account_id' => 'required',
    //         'deposit_account_id' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //         'transaction_send_supplier_id' => 'nullable',
    //         'deadline_invoice' => 'nullable|date_format:d/m/Y',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . $idx . ": " . $validator->errors()->first());
    //     }

    //     // Find IDs for transfer_account, deposit_account, and optional transaction_send_supplier
    //     $transferAccount = AccountNumber::where('name', $row['transfer_account_id'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account name not found at line " . $idx);
    //     }

    //     $depositAccount = AccountNumber::where('name', $row['deposit_account_id'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account name not found at line " . $idx);
    //     }

    //     $transaction_send_supplier_id = null;
    //     if (isset($row['transaction_send_supplier_id'])) {
    //         $transaction_send_supplier_id = TransactionSendSupplier::where('supplier_name', $row['transaction_send_supplier_id'])->first();
    //         if (!$transaction_send_supplier_id) {
    //             throw new Exception("Transaction Send Supplier name not found at line " . $idx);
    //         }
    //     }

    //     // Create transaction record
    //     Transaction_send::create([
    //         'transfer_account_id' => $transferAccount->id,
    //         'deposit_account_id' => $depositAccount->id,
    //         'transaction_send_supplier_id' => $transaction_send_supplier_id ? $transaction_send_supplier_id->id : null,
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
    //         'transfer_account_id' => 'required',
    //         'deposit_account_id' => 'required',
    //         'no_transaction' => 'required',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'required|date_format:d/m/Y',
    //         'description' => 'required',
    //         'student_id' => 'nullable',
    //     ]);

    //     if ($validator->fails()) {
    //         throw new Exception("Validation errors at line " . $idx . ": " . $validator->errors()->first());
    //     }

    //     // Find IDs for transfer_account, deposit_account, and optional student
    //     $transferAccount = AccountNumber::where('name', $row['transfer_account_id'])->first();
    //     if (!$transferAccount) {
    //         throw new Exception("Transfer account name not found at line " . $idx);
    //     }

    //     $depositAccount = AccountNumber::where('name', $row['deposit_account_id'])->first();
    //     if (!$depositAccount) {
    //         throw new Exception("Deposit account name not found at line " . $idx);
    //     }

    //     $student_id = null;
    //     if (isset($row['student_id'])) {
    //         $student_id = Student::where('name', $row['student_id'])->first();
    //         if (!$student_id) {
    //             throw new Exception("Student name not found at line " . $idx);
    //         }
    //     }

    //     // Create transaction record
    //     Transaction_receive::create([
    //         'transfer_account_id' => $transferAccount->id,
    //         'deposit_account_id' => $depositAccount->id,
    //         'student_id' => $student_id ? $student_id->id : null,
    //         'no_transaction' => $row['no_transaction'],
    //         'amount' => $row['amount'],
    //         'date' => Carbon::createFromFormat('d/m/Y', $row['date']),
    //         'description' => $row['description'],
    //     ]);
    // }
}
