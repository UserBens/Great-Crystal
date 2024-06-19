<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\InvoiceSupplier;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Transaction_send;
use App\Models\Transaction_transfer;
use App\Models\Transaction_receive;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // public function index()
    // {
    //    try {
    //       //code...
    //       session()->flash('page',  $page = (object)[
    //          'page' => 'dashboard',
    //          'child' => 'dashboard',
    //       ]);

    //       $newStudent = Student::where('is_active', true)->orderBy('created_at', 'desc')->get()->count('id');

    //       $newTeacher = Teacher::where('is_active', true)->get()->count('id');

    //       $newBill = Bill::where('created_at', '>',  Carbon::now()->subDays(30)->setTimezone('Asia/Jakarta')->toDateTimeString())->get()->count('id');

    //       $billPastDue = Bill::where('paidOf', false)
    //       ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'))->get()->count('id');

    //       $newBillData = Bill::with('student')->orderBy('id', 'desc')->take(6)->get();
    //       $pastDueData =  Bill::with('student')
    //       ->where('paidOf', false)
    //       ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('y-m-d'))
    //       ->take(6)
    //       ->get();

    //       $transactionSend = Transaction_send::with(['TransactionSendSupplier'])->orderBy('id', 'desc')->get();

    //       $teacherData = Teacher::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();

    //       $studentData = Student::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();


    //       $data = (object)[
    //          'student' => (int)$newStudent,
    //          'teacher' => (int)$newTeacher,
    //          'bill' => (int)$newBill,
    //          'pastDue' => $billPastDue,
    //          'dataBill' => $newBillData,
    //          'dataPastDue' => $pastDueData,
    //          'dataTeacher' => $teacherData,
    //          'dataStudent' => $studentData,
    //          'transactionSend' => $transactionSend,
    //       ];

    //       return view('components.dashboard')->with('data', $data);
    //    } catch (Exception $err) {

    //       return dd($err);
    //    }
    // }

    // public function index()
    // {
    //    try {
    //       session()->flash('page',  $page = (object)[
    //          'page' => 'dashboard',
    //          'child' => 'dashboard',
    //       ]);

    //       $newStudent = Student::where('is_active', true)->count();
    //       $newTeacher = Teacher::where('is_active', true)->count();
    //       $newBill = Bill::where('created_at', '>',  Carbon::now()->subDays(30)->setTimezone('Asia/Jakarta'))->count();
    //       $billPastDue = Bill::where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->count();
    //       $newBillData = Bill::with('student')->orderBy('id', 'desc')->take(6)->get();
    //       $pastDueData = Bill::with('student')
    //          ->where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->take(6)
    //          ->get();

    //       $teacherData = Teacher::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();
    //       $studentData = Student::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();

    //       // $transactionSend = Transaction_send::all()->count();
    //       $transactionSendSupplier = Transaction_send::with(['transactionSendSupplier'])->orderBy('id', 'desc')->get();
    //       // Mengambil jumlah data transaksi send
    //       $transactionSend = $transactionSendSupplier->count();
    //       // Mengambil jumlah data transaksi receive
    //       $transactionReceive = Transaction_receive::count();
    //       // Mengambil jumlah data transaksi transfer
    //       $transactionTransfer = Transaction_transfer::count();

    //       // Data for charts
    //       // Pie chart data for transaction types
    //       $transactionTypes = [
    //          'send' => Transaction_send::count(),
    //          'receive' => Transaction_receive::count(),
    //          'transfer' => Transaction_transfer::count()
    //       ];
    //       $pie = [];
    //       foreach ($transactionTypes as $type => $count) {
    //          $pie[] = [
    //             'name' => ucfirst($type),
    //             'y' => $count
    //          ];
    //       }

    //       // Line chart data for monthly transactions
    //       $rows = Transaction_send::selectRaw('MAX(date) AS date, COUNT(*) AS total')
    //          ->groupByRaw('YEAR(date), MONTH(date)')
    //          ->get();
    //       $line = [];
    //       foreach ($rows as $row) {
    //          $line['categories'][] = date('M-Y', strtotime($row->date));
    //          $line['data'][] = $row->total * 1;
    //       }

    //       // Column chart data for transaction sums per year
    //       $rows = Transaction_send::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       $column = [];
    //       foreach ($rows as $row) {
    //          $column['categories'][] = $row->year;
    //          $column['series']['Send']['name'] = 'Send';
    //          $column['series']['Send']['data'][] = $row->total * 1;
    //       }

    //       // Adding receive and transfer data to column chart
    //       $rows = Transaction_receive::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Receive']['name'] = 'Receive';
    //          $column['series']['Receive']['data'][] = $row->total * 1;
    //       }

    //       $rows = Transaction_transfer::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Transfer']['name'] = 'Transfer';
    //          $column['series']['Transfer']['data'][] = $row->total * 1;
    //       }

    //       // Organizing column chart data
    //       $column['categories'] = array_values(array_unique($column['categories']));
    //       foreach ($column['series'] as &$series) {
    //          $series['data'] = array_values($series['data']);
    //       }
    //       $column['series'] = array_values($column['series']);

    //       $data = (object)[
    //          'student' => (int)$newStudent,
    //          'teacher' => (int)$newTeacher,
    //          'bill' => (int)$newBill,
    //          'pastDue' => $billPastDue,
    //          'dataBill' => $newBillData,
    //          'dataPastDue' => $pastDueData,
    //          'dataTeacher' => $teacherData,
    //          'dataStudent' => $studentData,
    //          'transactionSend' => $transactionSend,
    //          'transactionSendSupplier' => $transactionSendSupplier,
    //          'transactionReceive' => $transactionReceive,
    //          'transactionTransfer' => $transactionTransfer,
    //          'pie' => $pie,
    //          'line' => $line,
    //          'column' => $column
    //       ];

    //       return view('components.dashboard')->with('data', $data);
    //    } catch (Exception $err) {
    //       return dd($err);
    //    }
    // }

    // public function index()
    // {
    //    try {
    //       session()->flash('page',  $page = (object)[
    //          'page' => 'dashboard',
    //          'child' => 'dashboard',
    //       ]);

    //       // Menghitung jumlah siswa, guru, tagihan baru, dan tagihan jatuh tempo
    //       $newStudent = Student::where('is_active', true)->count();
    //       $newTeacher = Teacher::where('is_active', true)->count();
    //       $newBill = Bill::where('created_at', '>',  Carbon::now()->subDays(30)->setTimezone('Asia/Jakarta'))->count();
    //       $billPastDue = Bill::where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->count();

    //       // Mengambil data terbaru untuk beberapa entitas
    //       $newBillData = Bill::with('student')->orderBy('id', 'desc')->take(6)->get();
    //       $pastDueData = Bill::with('student')
    //          ->where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->take(6)
    //          ->get();

    //       $teacherData = Teacher::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();
    //       $studentData = Student::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();

    //       // Mengambil jumlah data transaksi
    //       $transactionSendSupplier = Transaction_send::with(['transactionSendSupplier'])->orderBy('id', 'desc')->get();
    //       $transactionSend = $transactionSendSupplier->count();
    //       $transactionReceive = Transaction_receive::count();
    //       $transactionTransfer = Transaction_transfer::count();

    //       // Data untuk diagram
    //       // Data pie chart untuk tipe transaksi
    //       $transactionTypes = [
    //          'send' => Transaction_send::count(),
    //          'receive' => Transaction_receive::count(),
    //          // 'transfer' => Transaction_transfer::count()
    //       ];
    //       $pie = [];
    //       foreach ($transactionTypes as $type => $count) {
    //          $pie[] = [
    //             'name' => ucfirst($type),
    //             'y' => $count
    //          ];
    //       }

    //       // Data line chart untuk transaksi bulanan
    //       $rows = Transaction_send::selectRaw('MAX(date) AS date, COUNT(*) AS total')
    //          ->groupByRaw('YEAR(date), MONTH(date)')
    //          ->get();
    //       $line = [];
    //       foreach ($rows as $row) {
    //          $line['categories'][] = date('M-Y', strtotime($row->date));
    //          $line['data'][] = $row->total * 1;
    //       }

    //       // Data column chart untuk jumlah transaksi per tahun
    //       $rows = Transaction_send::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       $column = [];
    //       foreach ($rows as $row) {
    //          $column['categories'][] = $row->year;
    //          $column['series']['Send']['name'] = 'Send';
    //          $column['series']['Send']['data'][] = $row->total * 1;
    //       }

    //       // Menambahkan data receive dan transfer ke column chart
    //       $rows = Transaction_receive::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Receive']['name'] = 'Receive';
    //          $column['series']['Receive']['data'][] = $row->total * 1;
    //       }

    //       $rows = Transaction_transfer::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Transfer']['name'] = 'Transfer';
    //          $column['series']['Transfer']['data'][] = $row->total * 1;
    //       }

    //       // Mengorganisir data column chart
    //       $column['categories'] = array_values(array_unique($column['categories']));
    //       foreach ($column['series'] as &$series) {
    //          $series['data'] = array_values($series['data']);
    //       }
    //       $column['series'] = array_values($column['series']);

    //       $currentYear = date('Y'); // Ambil tahun saat ini, atau bisa diganti dengan tahun yang diinginkan
    //       $incomeRows = Bill::selectRaw('MONTH(paid_date) AS month, SUM(amount) AS total')
    //          ->where('paidOf', true)
    //          ->whereYear('paid_date', $currentYear) // Filter untuk tahun tertentu
    //          ->groupByRaw('MONTH(paid_date)')
    //          ->get();

    //       // Inisialisasi array untuk pendapatan bulanan
    //       $income = [
    //          'categories' => [],
    //          'data' => array_fill(0, 12, 0) // Inisialisasi 12 bulan dengan nilai 0
    //       ];

    //       // Isi data ke dalam array
    //       foreach ($incomeRows as $row) {
    //          $monthIndex = $row->month - 1; // Konversi bulan ke indeks array (0-11)
    //          $income['categories'][$monthIndex] = date('F', mktime(0, 0, 0, $row->month, 1));
    //          $income['data'][$monthIndex] = (float) $row->total; // Pastikan data dalam format float
    //       }

    //       // Isi kategori untuk bulan yang kosong
    //       for ($i = 0; $i < 12; $i++) {
    //          if (!isset($income['categories'][$i])) {
    //             $income['categories'][$i] = date('F', mktime(0, 0, 0, $i + 1, 1));
    //          }
    //       }

    //       $data = (object)[
    //          'student' => (int)$newStudent,
    //          'teacher' => (int)$newTeacher,
    //          'bill' => (int)$newBill,
    //          'pastDue' => $billPastDue,
    //          'dataBill' => $newBillData,
    //          'dataPastDue' => $pastDueData,
    //          'dataTeacher' => $teacherData,
    //          'dataStudent' => $studentData,
    //          'transactionSend' => $transactionSend,
    //          'transactionSendSupplier' => $transactionSendSupplier,
    //          'transactionReceive' => $transactionReceive,
    //          'transactionTransfer' => $transactionTransfer,
    //          'pie' => $pie,
    //          'line' => $line,
    //          'column' => $column,
    //          'income' => $income
    //       ];

    //       return view('components.dashboard')->with('data', $data);
    //    } catch (Exception $err) {
    //       return dd($err);
    //    }
    // }

    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'dashboard',
                'child' => 'dashboard',
            ]);

            // Menghitung jumlah siswa, guru, tagihan baru, dan tagihan jatuh tempo
            $newStudent = Student::where('is_active', true)->count();
            $newTeacher = Teacher::where('is_active', true)->count();
            $newBill = Bill::where('created_at', '>',  Carbon::now()->subDays(30)->setTimezone('Asia/Jakarta'))->count();
            $billPastDue = Bill::where('paidOf', false)
                ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
                ->count();

            // Mengambil data terbaru untuk beberapa entitas
            $newBillData = Bill::with('student')->orderBy('id', 'desc')->take(6)->get();
            $pastDueData = Bill::with('student')
                ->where('paidOf', false)
                ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
                ->take(6)
                ->get();

            $teacherData = Teacher::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();
            $studentData = Student::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();

            $invoiceSuppliers = InvoiceSupplier::all();

            // Mengambil jumlah data transaksi
            $transactionSend = Transaction_send::count();
            // $transactionSend = $transactionSendSupplier->count();
            $transactionReceive = Transaction_receive::count();
            $transactionTransfer = Transaction_transfer::count();


            // Data untuk diagram
            // Data pie chart untuk tipe transaksi
            $transactionTypes = [
                'send' => Transaction_send::count(),
                'receive' => Transaction_receive::count(),
                // 'transfer' => Transaction_transfer::count()
            ];
            $pie = [];
            foreach ($transactionTypes as $type => $count) {
                $pie[] = [
                    'name' => ucfirst($type),
                    'y' => $count
                ];
            }

            // Data line chart untuk transaksi bulanan
            $rows = Transaction_send::selectRaw('MAX(date) AS date, COUNT(*) AS total')
                ->groupByRaw('YEAR(date), MONTH(date)')
                ->get();
            $line = [];
            foreach ($rows as $row) {
                $line['categories'][] = date('M-Y', strtotime($row->date));
                $line['data'][] = $row->total * 1;
            }

            // Data column chart untuk jumlah transaksi per tahun
            $rows = Transaction_send::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
                ->groupByRaw('YEAR(date)')
                ->get();
            $column = [];
            foreach ($rows as $row) {
                $column['categories'][] = $row->year;
                $column['series']['Send']['name'] = 'Send';
                $column['series']['Send']['data'][] = $row->total * 1;
            }

            // Menambahkan data receive dan transfer ke column chart
            $rows = Transaction_receive::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
                ->groupByRaw('YEAR(date)')
                ->get();
            foreach ($rows as $row) {
                if (!in_array($row->year, $column['categories'])) {
                    $column['categories'][] = $row->year;
                }
                $column['series']['Receive']['name'] = 'Receive';
                $column['series']['Receive']['data'][] = $row->total * 1;
            }

            $rows = Transaction_transfer::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
                ->groupByRaw('YEAR(date)')
                ->get();
            foreach ($rows as $row) {
                if (!in_array($row->year, $column['categories'])) {
                    $column['categories'][] = $row->year;
                }
                $column['series']['Transfer']['name'] = 'Transfer';
                $column['series']['Transfer']['data'][] = $row->total * 1;
            }

            // Mengorganisir data column chart
            $column['categories'] = array_values(array_unique($column['categories']));
            foreach ($column['series'] as &$series) {
                $series['data'] = array_values($series['data']);
            }
            $column['series'] = array_values($column['series']);

            $currentYear = date('Y'); // Ambil tahun saat ini, atau bisa diganti dengan tahun yang diinginkan
            $incomeRows = Bill::selectRaw('MONTH(paid_date) AS month, SUM(amount) AS total')
                ->where('paidOf', true)
                ->whereYear('paid_date', $currentYear) // Filter untuk tahun tertentu
                ->groupByRaw('MONTH(paid_date)')
                ->get();

            // Inisialisasi array untuk pendapatan bulanan
            $income = [
                'categories' => [],
                'data' => array_fill(0, 12, 0) // Inisialisasi 12 bulan dengan nilai 0
            ];

            // Isi data ke dalam array
            foreach ($incomeRows as $row) {
                $monthIndex = $row->month - 1; // Konversi bulan ke indeks array (0-11)
                $income['categories'][$monthIndex] = date('F', mktime(0, 0, 0, $row->month, 1));
                $income['data'][$monthIndex] = (float) $row->total; // Pastikan data dalam format float
            }

            // Isi kategori untuk bulan yang kosong
            for ($i = 0; $i < 12; $i++) {
                if (!isset($income['categories'][$i])) {
                    $income['categories'][$i] = date('F', mktime(0, 0, 0, $i + 1, 1));
                }
            }

            $data = (object)[
                'student' => (int)$newStudent,
                'teacher' => (int)$newTeacher,
                'bill' => (int)$newBill,
                'pastDue' => $billPastDue,
                'dataBill' => $newBillData,
                'dataPastDue' => $pastDueData,
                'dataTeacher' => $teacherData,
                'dataStudent' => $studentData,
                'transactionSend' => $transactionSend,
                'invoiceSuppliers' => $invoiceSuppliers,
                'transactionReceive' => $transactionReceive,
                'transactionTransfer' => $transactionTransfer,
                'pie' => $pie,
                'line' => $line,
                'column' => $column,
                'income' => $income // Include income data
            ];

            return view('components.dashboard')->with('data', $data);
        } catch (Exception $err) {
            return dd($err);
        }
    }


    // public function index()
    // {
    //    try {
    //       session()->flash('page',  $page = (object)[
    //          'page' => 'dashboard',
    //          'child' => 'dashboard',
    //       ]);

    //       $newStudent = Student::where('is_active', true)->count();
    //       $newTeacher = Teacher::where('is_active', true)->count();
    //       $newBill = Bill::where('created_at', '>',  Carbon::now()->subDays(30)->setTimezone('Asia/Jakarta'))->count();
    //       $billPastDue = Bill::where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->count();
    //       $newBillData = Bill::with('student')->orderBy('id', 'desc')->take(6)->get();
    //       $pastDueData = Bill::with('student')
    //          ->where('paidOf', false)
    //          ->where('deadline_invoice', '<',  Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'))
    //          ->take(6)
    //          ->get();
    //       $transactionSend = Transaction_send::with(['transactionSendSupplier'])->orderBy('id', 'desc')->get();
    //       $teacherData = Teacher::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();
    //       $studentData = Student::where('is_active', true)->orderBy('id', 'desc')->take(6)->get();

    //       // Inisialisasi array kosong
    //       $line = [
    //          'categories' => [],
    //          'data' => []
    //       ];
    //       $column = [
    //          'categories' => [],
    //          'series' => []
    //       ];

    //       // Data for charts
    //       // Pie chart data for transaction types
    //       $transactionTypes = [
    //          'send' => Transaction_send::count(),
    //          'receive' => Transaction_receive::count(),
    //          'transfer' => Transaction_transfer::count()
    //       ];
    //       $pie = [];
    //       foreach ($transactionTypes as $type => $count) {
    //          $pie[] = [
    //             'name' => ucfirst($type),
    //             'y' => $count
    //          ];
    //       }

    //       // Line chart data for monthly transactions
    //       $rows = Transaction_send::selectRaw('MAX(date) AS date, COUNT(*) AS total')
    //          ->groupByRaw('YEAR(date), MONTH(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          $line['categories'][] = date('M-Y', strtotime($row->date));
    //          $line['data'][] = $row->total * 1;
    //       }

    //       // Column chart data for transaction sums per year
    //       $rows = Transaction_send::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          $column['categories'][] = $row->year;
    //          $column['series']['Send']['name'] = 'Send';
    //          $column['series']['Send']['data'][] = $row->total * 1;
    //       }

    //       // Adding receive and transfer data to column chart
    //       $rows = Transaction_receive::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Receive']['name'] = 'Receive';
    //          $column['series']['Receive']['data'][] = $row->total * 1;
    //       }

    //       $rows = Transaction_transfer::selectRaw('YEAR(date) AS year, SUM(amount) AS total')
    //          ->groupByRaw('YEAR(date)')
    //          ->get();
    //       foreach ($rows as $row) {
    //          if (!in_array($row->year, $column['categories'])) {
    //             $column['categories'][] = $row->year;
    //          }
    //          $column['series']['Transfer']['name'] = 'Transfer';
    //          $column['series']['Transfer']['data'][] = $row->total * 1;
    //       }

    //       // Organizing column chart data
    //       $column['categories'] = array_values(array_unique($column['categories']));
    //       foreach ($column['series'] as &$series) {
    //          $series['data'] = array_values($series['data']);
    //       }
    //       $column['series'] = array_values($column['series']);

    //       $data = (object)[
    //          'student' => (int)$newStudent,
    //          'teacher' => (int)$newTeacher,
    //          'bill' => (int)$newBill,
    //          'pastDue' => $billPastDue,
    //          'dataBill' => $newBillData,
    //          'dataPastDue' => $pastDueData,
    //          'dataTeacher' => $teacherData,
    //          'dataStudent' => $studentData,
    //          'transactionSend' => $transactionSend,
    //          'pie' => $pie,
    //          'line' => $line,
    //          'column' => $column
    //       ];

    //       return view('components.dashboard')->with('data', $data);
    //    } catch (Exception $err) {
    //       return dd($err);
    //    }
    // }
}
