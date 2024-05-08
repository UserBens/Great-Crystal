<?php

use App\Http\Controllers\Admin\{
   AccountingController,
   AdminController,
   BillController,
   BookController,
   DashboardController,
   GradeController,
   PaymentBookController,
   PaymentGradeController,
   PaymentStudentController,
   RegisterController,
   StudentController,
   TeacherController,
   TransactionController,
   // FinancialController,
};
use App\Http\Controllers\Excel\Report;
use App\Http\Controllers\Excel\Import;
use App\Http\Controllers\ExpenditureController;
// use App\Http\Controllers\FinancialController as ControllersFinancialController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Notification\NotificationBillCreated;
use App\Http\Controllers\Notification\NotificationPastDue;
use App\Http\Controllers\Notification\NotificationPaymentSuccess;
use App\Http\Controllers\Notification\StatusMailSend;
use App\Http\Controllers\SuperAdmin\{
   SuperAdminController,
   StudentController as SuperStudentController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Jobs\SendEmailJob;
use App\Jobs\SendMailReminder;
use App\Livewire\Counter;
use App\Mail\SendEmailTest;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Notifications\Notification;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'actionLogin'])->name('actionLogin');
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/counter', Counter::class);

Route::get('send-mail', [NotificationBillCreated::class, 'book']);

Route::middleware(['auth.login'])->prefix('/admin')->group(function () {

   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::prefix('/user')->group(function () {

      Route::get('/change-password', [AdminController::class, 'changeMyPassword']);
      Route::put('/change-password', [AdminController::class, 'actionChangeMyPassword']);
   });

   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/bills')->group(function () {
      Route::get('/', [BillController::class, 'index']);
      Route::get('/create', [BillController::class, 'chooseStudent']);
      Route::get('/create-bills/{id}', [BillController::class, 'pageCreateBill']);
      Route::get('/detail-payment/{id}', [BillController::class, 'detailPayment']);
      Route::get('/create-payment/{id}', [BillController::class, 'pagePayment']);
      Route::get('/change-paket/{student_id}/{bill_id}', [BillController::class, 'pageChangePaket']);
      Route::get('/intallment-paket/{bill_id}', [BillController::class, 'pagePaketInstallment']);
      Route::get('/paid/pdf/{bill_id}', [BillController::class, 'pagePdf']);
      Route::get('/installment-pdf/{bill_id}', [BillController::class, 'reportInstallmentPdf']);
      Route::get('/edit-installment-paket/{bill_id}', [BillController::class, 'pageEditInstallment']);
      Route::get('/status', [StatusMailSend::class, 'index']);
      Route::get('/status/{status_id}', [StatusMailSend::class, 'view']);
      Route::post('/post-bill/{id}', [BillController::class, 'actionCreateBill'])->name('create.bill');
      Route::post('/post-intallment-paket/{bill_id}', [BillController::class, 'actionPaketInstallment'])->name('create.installment');
      Route::put('/change-paket/{bill_id}/{student_id}', [BillController::class, 'actionChangePaket'])->name('action.edit.paket');
      Route::patch('/status/{id}', [StatusMailSend::class, 'send']);
      Route::patch('/update-paid/{bill_id}/{student_id}', [BillController::class, 'paidOfBook'])->name('action.book.payment');
      Route::patch('/update-paid/{id}', [BillController::class, 'paidOf']);
   });



   Route::prefix('/reports')->group(function () {
      Route::get('/', [Report::class, 'index']);
      Route::post('/exports', [Report::class, 'export']);
   });
});

Route::middleware(['admin'])->prefix('/admin')->group(function () {

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegister');
      Route::get('/edit-installment-capital/{bill_id}', [RegisterController::class, 'pageEditInstallment']);
      Route::put('/edit-installment-capital/{bill_id}', [RegisterController::class, 'actionEditInstallment'])->name('action.edit.installment');
      Route::get('/imports', [Import::class, 'index']);
      Route::post('/imports', [Import::class, 'upload'])->name('import.register');
      Route::get('/templates/students', [Import::class, 'downloadTemplate']);
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
   });


   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });

   Route::prefix('/teachers')->group(function () {

      Route::get('/', [TeacherController::class, 'index']);
      Route::post('/', [TeacherController::class, 'actionPost'])->name('actionRegisterTeacher');
      Route::put('/{id}', [TeacherController::class, 'actionEdit'])->name('actionUpdateTeacher');
      Route::get('/register', [TeacherController::class, 'pagePost']);
      Route::get('/{id}', [TeacherController::class, 'editPage']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/', [GradeController::class, 'index']);
      Route::get('/create', [GradeController::class, 'pageCreate']);
      Route::get('/{id}', [GradeController::class, 'detailGrade']);
      Route::get('/edit/{id}', [GradeController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [GradeController::class, 'pagePDF']);
      Route::post('/', [GradeController::class, 'actionPost'])->name('actionCreateGrade');
      Route::put('/{id}', [GradeController::class, 'actionPut'])->name('actionUpdateGrade');
   });


   Route::prefix('/books')->group(function () {
      Route::get('/', [BookController::class, 'index']);
      Route::get('/create', [BookController::class, 'pageCreate']);
      Route::get('/edit/{id}', [BookController::class, 'pageEdit']);
      Route::get('/detail/{id}', [BookController::class, 'detail']);
      Route::post('/post', [BookController::class, 'postCreate'])->name('action.create.book');
      Route::patch('/post/{id}', [BookController::class, 'actionUpdate'])->name('action.update.book');
      Route::delete('/{id}', [BookController::class, 'destroy']);
   });

   Route::prefix('/student')->group(function () {
      Route::get('/re-registration/{student_id}', [SuperStudentController::class, 'pageReRegis']);
      Route::patch('/{id}', [SuperStudentController::class, 'inactiveStudent']);
      Route::patch('/activate/{student_id}', [SuperStudentController::class, 'activateStudent']);
      Route::patch('/re-registration/{student_id}', [SuperStudentController::class, 'actionReRegis'])->name('action.re-regis');
   });
});

Route::middleware(['accounting'])->prefix('admin')->group(function () {

   Route::prefix('/spp-students')->group(function () {
      Route::get('/', [PaymentStudentController::class, 'index']);
      Route::get('/create/{id}', [PaymentStudentController::class, 'createPage']);
      Route::get('/detail/{id}', [PaymentStudentController::class, 'pageDetailSpp']);
      Route::get('/edit/{id}/', [PaymentStudentController::class, 'pageEditSpp']);
      Route::post('/create/{id}', [PaymentStudentController::class, 'actionCreatePayment'])->name('create.static.student');
      Route::put('/actionEdit/{id}/{id_student_payment}', [PaymentStudentController::class, 'actionEditStaticPayment'])->name('update.payment.student-static');
   });

   Route::prefix('/payment-grades')->group(function () {
      Route::get('/', [PaymentGradeController::class, 'index']);
      Route::get('/{id}', [PaymentGradeController::class, 'pageById']);
      Route::get('/{id}/choose-type', [PaymentGradeController::class, 'chooseSection']);
      Route::get('{id}/create/{type}', [PaymentGradeController::class, 'pageCreate']);
      Route::get('/{id}/edit', [PaymentGradeController::class, 'pageEdit']);
      Route::post('action-create/payment-grade/{id}/{type}', [PaymentGradeController::class, 'actionCreate'])->name('create.payment-grades');
      Route::put('/{id}/edit', [PaymentGradeController::class, 'actionEdit'])->name('edit.payment-grades');
      Route::delete('/{id}', [PaymentGradeController::class, 'deletePayment']);
   });

   Route::prefix('payment-books')->group(function () {
      Route::get('/', [PaymentBookController::class, 'index']);
      Route::get('/{id}', [PaymentBookController::class, 'studentBook']);
      Route::get('/{id}/add-books', [PaymentBookController::class, 'pageAddBook']);
      Route::post('/{id}/add-books-action', [PaymentBookController::class, 'actionAddBook'])->name('action.add.book');
   });

   Route::prefix('/income')->group(function () {
      Route::get('/', [FinancialController::class, 'indexIncome'])->name('income.index');
   });

   Route::prefix('/expenditure')->group(function () {
      Route::get('/', [FinancialController::class, 'indexExpenditure'])->name('expenditure.index');
      Route::get('/create', [FinancialController::class, 'createExpenditure'])->name('expenditure.create');
      Route::post('/store', [FinancialController::class, 'storeExpenditure'])->name('expenditure.store');
      Route::get('/{id}/edit', [FinancialController::class, 'editExpenditure'])->name('expenditure.edit');
      Route::put('/{id}', [FinancialController::class, 'updateExpenditure'])->name('expenditure.update');
      Route::delete('/{id}', [FinancialController::class, 'destroyExpenditure'])->name('expenditure.destroy');
   });

   Route::prefix('/cash')->group(function () {
      Route::get('/', [AccountingController::class, 'indexCash'])->name('cash.index');

      Route::get('/transaction-transfer', [AccountingController::class, 'createTransactionTransfer'])->name('transaction-transfer.create');
      Route::post('/transaction-transfer', [AccountingController::class, 'storeTransactionTransfer'])->name('transaction-transfer.store');
      Route::delete('/{id}', [AccountingController::class, 'deleteTransactionTransfer'])->name('transaction-transfer.destroy');

      // Route::get('/transaction-send', [AccountingController::class, 'createTransactionSend'])->name('transaction-send.create');
      // Route::post('/transaction-send', [AccountingController::class, 'storeTransactionSend'])->name('transaction-send.store');

      // Route::get('/transaction-receive', [AccountingController::class, 'createTransactionReceive'])->name('transaction-receive.create');
      // Route::post('/transaction-receive', [AccountingController::class, 'storeTransactionReceive'])->name('transaction-receive.store');
      
   });

   // Route::prefix('/bank')->group(function () {
   //    Route::get('/', [AccountingController::class, 'indexBank'])->name('bank.index');
   // });

   Route::prefix('/journal')->group(function () {
      Route::get('/', [AccountingController::class, 'indexJournal'])->name('journal.index');
   });

   Route::prefix('/account')->group(function () {
      Route::get('/', [AccountingController::class, 'indexAccount'])->name('account.index');
      Route::get('/create-account', [AccountingController::class, 'createAccount'])->name('create-account.create');
      Route::post('/create-account/store', [AccountingController::class, 'storeAccount'])->name('account.store');
      Route::get('/{id}/edit', [AccountingController::class, 'editAccount'])->name('account.edit');
      Route::put('/{id}', [AccountingController::class, 'updateAccount'])->name('account.update');
      Route::delete('/{id}', [AccountingController::class, 'destroyAccount'])->name('account.destroy');
   });
});

Route::middleware(['superadmin'])->prefix('admin')->group(function () {

   Route::prefix('/user')->group(function () {
      Route::get('/', [SuperAdminController::class, 'getUser']);
      Route::get('/register-user', [SuperAdminController::class, 'registerUser']);
      Route::get('/{id}', [SuperAdminController::class, 'getById']);
      Route::post('/register-action', [SuperAdminController::class, 'registerUserAction']);
      Route::put('/change-password/commit/{id}', [SuperAdminController::class, 'changePassword'])->name('user.editPassword');
      Route::delete('{id}', [SuperAdminController::class, 'deleteUser']);
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/promotions/{id}', [GradeController::class, 'pagePromotion']);
      Route::put('/promotions/post/action', [GradeController::class, 'actionPromotion'])->name('actionPromotion');
   });

   Route::prefix('/teachers')->group(function () {

      Route::put('/deactivated/{id}', [TeacherController::class, 'deactivated']);
      Route::put('/activated/{id}', [TeacherController::class, 'activated']);
   });
});
