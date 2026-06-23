<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SessionController as AdminSessionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\FeesTypeController;
use App\Http\Controllers\FeesMasterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentListController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\FeesManagerController;
use App\Http\Controllers\ReceiptSettingController;
use App\Http\Controllers\FeeCollectionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StreamManageController;
use App\Http\Controllers\StreamAllocationController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\TeacherSubjectAllocationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherSalaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\FinanceLedgerController;
use App\Http\Controllers\MarksController;
use App\Http\Controllers\MarksReportController;
use App\Http\Controllers\OtherStaffController;
use App\Http\Controllers\StaffSalaryController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ParentAuthController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\ParentFeesController;
use App\Http\Controllers\ParentExamController;
use App\Http\Controllers\ParentResultController;


Route::get('/login', function () {
    return redirect()->route('login');
});

 Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/query/store', [HomeController::class, 'storeQuery'])->name('home.query.store');

Route::prefix('license')->group(function () {
    Route::get('/', [LicenseController::class, 'index'])->name('license.index');
    Route::post('/activate', [LicenseController::class, 'activate'])->name('license.activate');
    Route::post('/renew', [LicenseController::class, 'renew'])->name('license.renew');
    Route::post('/update-secret', [LicenseController::class, 'updateSecret'])->name('license.update-secret');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes (for all logged in users)
Route::middleware('auth','prevent-back','check.license')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/change-session', [SessionController::class, 'changeSession'])->name('change.session');
    Route::get('/get-session-info', [SessionController::class, 'getSessionInfo'])->name('get.session.info');
     Route::get('/marks/marks-entry', [MarksController::class, 'index'])->name('marks.marks.entry');
    Route::post('/marks/marks-store', [MarksController::class, 'store'])->name('marks.marks.store');
    Route::post('/marks/marks-approve', [MarksController::class, 'approve'])->name('marks.marks.approve');
    Route::get('/marks/marks-export', [MarksController::class, 'exportCSV'])->name('marks.marks.export');
    // Academics Routes
    Route::get('/academics', [ClassController::class, 'index'])->name('academics.index');
    Route::post('/classes/store', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/edit/{id}', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/update/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/delete/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/classes/search', [ClassController::class, 'search'])->name('classes.search');
    Route::get('/teacher/attendance', [TeacherAttendanceController::class, 'index'])->name('teacher.attendance');
    Route::post('/teacher/attendance/store', [TeacherAttendanceController::class, 'store'])->name('teacher.attendance.store');
    Route::get('/teacher/attendance/report', [TeacherAttendanceController::class, 'report'])->name('teacher.attendance.report');
Route::get('/teacher/attendance/export', [TeacherAttendanceController::class, 'exportCSV'])->name('teacher.attendance.export');


    Route::get('/student/register', [StudentController::class, 'index'])->name('student.register');
    Route::post('/student/store', [StudentController::class, 'store'])->name('student.store');
    Route::get('/api/class/{id}', [StudentController::class, 'getClass']);
    Route::get('/api/streams/by-class/{classId}', [StudentController::class, 'getStreamsByClass']);
    Route::get('/api/student/{studentId}/subjects', [StudentController::class, 'getStudentSubjects']);
    Route::get('/student/list', [StudentListController::class, 'index'])->name('student.list');
    Route::get('/student/profile/{id}', [StudentListController::class, 'show'])->name('student.profile');
    Route::get('/student/edit/{id}', [StudentListController::class, 'edit'])->name('student.edit');
    Route::put('/student/update/{id}', [StudentListController::class, 'update'])->name('student.update');
    Route::delete('/student/delete/{id}', [StudentListController::class, 'destroy'])->name('student.delete');
    Route::get('/student/qr-data/{id}', [StudentListController::class, 'qrData'])->name('student.qr.data');
    Route::get('/fees-collection', [FeeCollectionController::class, 'index'])->name('fees.collection');
    Route::post('/fees-collect', [FeeCollectionController::class, 'collectFee'])->name('fees.collect');
    Route::get('/fees-student-list', [FeeCollectionController::class, 'studentList'])->name('fees.student.list');
    Route::get('/fees-collection', [FeeCollectionController::class, 'index'])->name('fees.collection');
    Route::post('/fees-collect', [FeeCollectionController::class, 'collectFee'])->name('fees.collect');
    Route::get('/admin/fee-payment/edit/{id}', [FeeCollectionController::class, 'editPayment'])->name('fee.payment.edit');
    Route::put('/admin/fee-payment/update/{id}', [FeeCollectionController::class, 'updatePayment'])->name('fee.payment.update');
    Route::delete('/admin/fee-payment/delete/{id}', [FeeCollectionController::class, 'deletePayment'])->name('fee.payment.delete');
    Route::get('/admin/fee-payment/print/{id}', [FeeCollectionController::class, 'printReceipt'])->name('fee.payment.print');
    Route::get('/admin/fee-statement/print/{studentId}', [FeeCollectionController::class, 'printStatement'])->name('fee.statement.print');
     Route::get('/fee-payment-logs', [FeeCollectionController::class, 'logs'])->name('fee.payment.logs');
    Route::get('/fee-payment-log-details/{id}', [FeeCollectionController::class, 'getLogDetails'])->name('fee.payment.log.details');


     Route::get('/fees-types', [FeesTypeController::class, 'index'])->name('fees.index');
    Route::post('/admin/fees-types/store', [FeesTypeController::class, 'store'])->name('fees.store');
    Route::get('/admin/fees-types/edit/{id}', [FeesTypeController::class, 'edit'])->name('fees.edit');
    Route::put('/admin/fees-types/update/{id}', [FeesTypeController::class, 'update'])->name('fees.update');
    Route::delete('/admin/fees-types/delete/{id}', [FeesTypeController::class, 'destroy'])->name('fees.destroy');
    Route::get('/admin/fees-types/search', [FeesTypeController::class, 'search'])->name('fees.search');
    Route::get('/admin/fees-types/view/{id}', [FeesTypeController::class, 'view'])->name('fees.view');
    Route::get('/admin/fees-types/by-session', [FeesTypeController::class, 'getBySession'])->name('fees.by.session');
    Route::post('/admin/fees-types/copy-from-previous', [FeesTypeController::class, 'copyFromPrevious'])->name('fees.copy.previous');

     Route::get('/fees-master', [FeesMasterController::class, 'index'])->name('fees.master');
    Route::post('/admin/fees-master/store', [FeesMasterController::class, 'store'])->name('fees.master.store');
    Route::get('/admin/fees-master/edit/{id}', [FeesMasterController::class, 'edit'])->name('fees.master.edit');
    Route::put('/admin/fees-master/update/{id}', [FeesMasterController::class, 'update'])->name('fees.master.update');
    Route::delete('/admin/fees-master/delete/{id}', [FeesMasterController::class, 'destroy'])->name('fees.master.destroy');
    Route::get('/admin/fees-master/search', [FeesMasterController::class, 'search'])->name('fees.master.search');
    Route::get('/admin/fees-master/view/{id}', [FeesMasterController::class, 'view'])->name('fees.master.view');
    Route::get('/admin/fees-master/by-session', [FeesMasterController::class, 'getBySession'])->name('fees.master.by.session');
    Route::get('/fees-assigned', [FeesManagerController::class, 'getAssignedFees'])->name('fees.assigned');
    Route::post('/fees-remove', [FeesManagerController::class, 'removeFees'])->name('fees.remove');
     Route::post('/admin/fees-master/copy-from-previous', [FeesMasterController::class, 'copyFromPrevious'])->name('fees.master.copy.previous');
        Route::get('/fees-manager', [FeesManagerController::class, 'index'])->name('fees.manager');
    Route::post('/fees-assign', [FeesManagerController::class, 'assignFees'])->name('fees.assign');
    Route::get('/fees-master-details/{id}', [FeesManagerController::class, 'getFeesMasterDetails'])->name('fees.master.details');

    Route::get('/reports/daily-collection', [ReportsController::class, 'dailyCollection'])->name('reports.daily');
    Route::get('/reports/get-date-transactions', [ReportsController::class, 'getDateTransactions'])->name('reports.date.transactions');
    Route::get('/reports/export-csv', [ReportsController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/transactions', [ReportsController::class, 'transactionDetails'])->name('reports.transactions');
    Route::get('/reports/transactions/export-csv', [ReportsController::class, 'exportTransactionsCsv'])->name('reports.transactions.export');
    Route::get('/reports/due', [ReportsController::class, 'dueReport'])->name('reports.due');
    Route::get('/reports/due/export-csv', [ReportsController::class, 'exportDueCsv'])->name('reports.due.export');
    Route::get('/reports/student', [ReportsController::class, 'studentReport'])->name('reports.student');
    Route::get('/reports/student/export-csv', [ReportsController::class, 'exportStudentCsv'])->name('reports.student.export');
    Route::get('/reports/teacher', [ReportsController::class, 'teacherReport'])->name('reports.teacher');
    Route::get('/reports/teacher/export', [ReportsController::class, 'teacherExportCSV'])->name('reports.teacher.export');

     Route::get('/teacher/list', [TeacherController::class, 'list'])->name('teacher.list');
    Route::get('/teacher/register', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/teacher/store', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/teacher/{id}', [TeacherController::class, 'show'])->name('teacher.show');
    Route::get('/teacher/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/teacher/update/{id}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/teacher/delete/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

     Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/admin/subjects/store', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/admin/subjects/edit/{id}', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/admin/subjects/update/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/admin/subjects/delete/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::get('/admin/subjects/search', [SubjectController::class, 'search'])->name('subjects.search');

    Route::get('/class-subject', [ClassSubjectController::class, 'index'])->name('class.subject');
    Route::post('/admin/class-subject/assign', [ClassSubjectController::class, 'assign'])->name('class.subject.assign');
    Route::get('/admin/class-subject/get-subjects/{classId}', [ClassSubjectController::class, 'getSubjects'])->name('class.subject.get');
          Route::get('/manage-streams', [StreamManageController::class, 'index'])->name('streams.manage');
    Route::post('/admin/streams/store', [StreamManageController::class, 'store'])->name('streams.store');
    Route::get('/admin/streams/edit/{id}', [StreamManageController::class, 'edit'])->name('streams.edit');
    Route::put('/admin/streams/update/{id}', [StreamManageController::class, 'update'])->name('streams.update');
    Route::delete('/admin/streams/delete/{id}', [StreamManageController::class, 'destroy'])->name('streams.destroy');
    Route::get('/admin/streams/search', [StreamManageController::class, 'search'])->name('streams.search');
     Route::get('/stream-allocation', [StreamAllocationController::class, 'index'])->name('stream.allocation');
    Route::post('/stream-allocation/assign', [StreamAllocationController::class, 'assignStreams'])->name('stream.assign');
    Route::post('/stream-allocation/remove', [StreamAllocationController::class, 'removeStream'])->name('stream.remove');
    Route::post('/stream-allocation/change', [StreamAllocationController::class, 'changeStream'])->name('stream.change');
    Route::get('/stream-allocation/export', [StreamAllocationController::class, 'exportCsv'])->name('stream.allocation.export');
    Route::get('/time-table', [TimeTableController::class, 'index'])->name('time.table');
    Route::post('/time-table/save', [TimeTableController::class, 'save'])->name('time.table.save');
    Route::get('/time-table/get/{classId}/{day}', [TimeTableController::class, 'getTimeTable'])->name('time.table.get');
    Route::post('/time-table/copy', [TimeTableController::class, 'copyFromPrevious'])->name('time.table.copy');
      Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion.index');
    Route::post('/promotion/promote', [PromotionController::class, 'promote'])->name('promotion.promote');
    Route::get('/promotion/history', [PromotionController::class, 'history'])->name('promotion.history');
    Route::post('/promotion/revert', [PromotionController::class, 'revert'])->name('promotion.revert');
      Route::get('/teacher-subject-allocation', [TeacherSubjectAllocationController::class, 'index'])->name('teacher.subject.allocation');
    Route::post('/admin/teacher-subject-allocation/store', [TeacherSubjectAllocationController::class, 'store'])->name('teacher.subject.allocation.store');
    Route::put('/admin/teacher-subject-allocation/update/{id}', [TeacherSubjectAllocationController::class, 'update'])->name('teacher.subject.allocation.update');
    Route::delete('/admin/teacher-subject-allocation/delete/{id}', [TeacherSubjectAllocationController::class, 'destroy'])->name('teacher.subject.allocation.delete');
    Route::post('/admin/teacher-subject-allocation/copy', [TeacherSubjectAllocationController::class, 'copyFromPrevious'])->name('teacher.subject.allocation.copy');
    Route::get('/admin/teacher-subject-allocation/get/{id}', [TeacherSubjectAllocationController::class, 'getAllocation'])->name('teacher.subject.allocation.get');
    Route::get('/exam-schedule', [ExamScheduleController::class, 'index'])->name('exam.schedule');
    Route::post('/exam-schedule/store', [ExamScheduleController::class, 'store'])->name('exam.schedule.store');
    Route::delete('/exam-schedule/{id}', [ExamScheduleController::class, 'destroy'])->name('exam.schedule.destroy');
    Route::get('/exam-schedule/export', [ExamScheduleController::class, 'exportCSV'])->name('exam.schedule.export');
    Route::get('/exam-schedule/get-streams', [ExamScheduleController::class, 'getStreams'])->name('exam.schedule.streams');
    Route::get('/exam-schedule/get-subjects', [ExamScheduleController::class, 'getSubjects'])->name('exam.schedule.subjects');
    // ✅ YEH ROUTE ADD KAREIN (Missing tha)
    Route::get('/exam-schedule/get-streams', [ExamScheduleController::class, 'getStreams'])->name('exam.schedule.streams');
    Route::get('/exam-schedule/get-subjects', [ExamScheduleController::class, 'getSubjects'])->name('exam.schedule.subjects');
       Route::get('/ledger-creation', [FinanceLedgerController::class, 'index'])->name('finance.ledger.creation');
          Route::get('management/vehicle/', [VehicleController::class, 'index'])->name('management.vehicle.index');
    Route::post('management/vehicle/store', [VehicleController::class, 'store'])->name('management.vehicle.store');
    Route::get('management/vehicle/{id}', [VehicleController::class, 'show'])->name('management.vehicle.show');
    Route::put('management/vehicle/{id}', [VehicleController::class, 'update'])->name('management.vehicle.update');
    Route::delete('/managementvehicle/{id}', [VehicleController::class, 'destroy'])->name('management.vehicle.destroy');
    Route::get('management/vehicle/export', [VehicleController::class, 'exportCSV'])->name('management.vehicle.export');
    // Group Routes
    Route::get('/groups', [FinanceLedgerController::class, 'getGroups'])->name('finance.groups.get');
    Route::post('/groups/store', [FinanceLedgerController::class, 'storeGroup'])->name('finance.groups.store');
    Route::put('/groups/{id}', [FinanceLedgerController::class, 'updateGroup'])->name('finance.groups.update');
    Route::delete('/groups/{id}', [FinanceLedgerController::class, 'deleteGroup'])->name('finance.groups.delete');
    
    // Ledger Routes
    Route::post('/ledgers/store', [FinanceLedgerController::class, 'storeLedger'])->name('finance.ledgers.store');
    Route::get('/ledgers/{id}/edit', [FinanceLedgerController::class, 'edit'])->name('finance.ledgers.edit');
    Route::put('/ledgers/{id}', [FinanceLedgerController::class, 'updateLedger'])->name('finance.ledgers.update');
    Route::delete('/ledgers/{id}', [FinanceLedgerController::class, 'deleteLedger'])->name('finance.ledgers.delete');
    Route::get('/ledgers', [FinanceLedgerController::class, 'getLedgers'])->name('finance.ledgers.get');
    Route::get('/marks-report', [MarksReportController::class, 'index'])->name('marks.report');
    Route::get('/marks-report/export', [MarksReportController::class, 'exportCSV'])->name('marks.report.export');

    Route::get('/management', [OtherStaffController::class, 'index'])->name('management.index');
    Route::get('/management/create', [OtherStaffController::class, 'create'])->name('management.create');
    Route::post('/management/store', [OtherStaffController::class, 'store'])->name('management.store');
    Route::get('/management/{id}', [OtherStaffController::class, 'show'])->name('management.show');
    Route::get('/management/{id}/edit', [OtherStaffController::class, 'edit'])->name('management.edit');
    Route::put('/management/{id}', [OtherStaffController::class, 'update'])->name('management.update');
    Route::delete('/management/{id}', [OtherStaffController::class, 'destroy'])->name('management.destroy');

    Route::get('finance/staff-salary', [StaffSalaryController::class, 'index'])->name('finance.staff-salary.index');
    Route::get('finance/staff-salary/create', [StaffSalaryController::class, 'create'])->name('finance.staff-salary.create');
    Route::post('finance/staff-salary/store', [StaffSalaryController::class, 'store'])->name('finance.staff-salary.store');
    Route::get('finance/staff-salary/{id}/edit', [StaffSalaryController::class, 'edit'])->name('finance.staff-salary.edit');
    Route::put('finance/staff-salary/{id}', [StaffSalaryController::class, 'update'])->name('finance.staff-salary.update');
    Route::delete('finance/staff-salary/{id}', [StaffSalaryController::class, 'destroy'])->name('finance.staff-salary.destroy');
    Route::get('finance/staff-salary/get-staff-data', [StaffSalaryController::class, 'getStaffData'])->name('finance.staff-salary.get-staff-data');
    Route::get('finance/staff-salary/export', [StaffSalaryController::class, 'exportCSV'])->name('finance.staff-salary.export');




});











// Admin only routes
Route::middleware(['auth', 'admin','prevent-back','check.license'])->group(function () {
    Route::get('/control-panel', function () {
        return view('control-panel.index');
    })->name('control-panel');
    
    Route::get('/control-panel/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    
    // Admin Session Management
    Route::get('/control-panel/sessions', [AdminSessionController::class, 'index'])->name('sessions.index');
    Route::post('/admin/sessions/store', [AdminSessionController::class, 'store'])->name('sessions.store');
    Route::get('/admin/sessions/edit/{id}', [AdminSessionController::class, 'edit'])->name('sessions.edit');
    Route::put('/admin/sessions/update/{id}', [AdminSessionController::class, 'update'])->name('sessions.update');
    Route::delete('/admin/sessions/delete/{id}', [AdminSessionController::class, 'destroy'])->name('sessions.destroy');
    Route::get('/admin/sessions/search', [AdminSessionController::class, 'search'])->name('sessions.search');

    Route::get('/control-panel/settings', [SchoolSettingController::class, 'index'])->name('settings.index');
    Route::post('/admin/settings/store', [SchoolSettingController::class, 'store'])->name('settings.store');
    Route::put('/admin/settings/update/{id}', [SchoolSettingController::class, 'update'])->name('settings.update');
    Route::get('/admin/settings/show', [SchoolSettingController::class, 'show'])->name('settings.show');

    Route::get('/control-panel/receipt-setting', [ReceiptSettingController::class, 'index'])->name('receipt.setting');
    Route::post('/admin/receipt-setting/store', [ReceiptSettingController::class, 'store'])->name('receipt.setting.store');
     Route::get('/control-panel/notice', [NoticeController::class, 'index'])->name('control-panel.notice');
    Route::post('/control-panel/notice/store', [NoticeController::class, 'store'])->name('control-panel.notice.store');
    Route::get('/control-panel/notice/{id}', [NoticeController::class, 'show'])->name('control-panel.notice.show');
    Route::put('/control-panel/notice/{id}', [NoticeController::class, 'update'])->name('control-panel.notice.update');
    Route::delete('/control-panel/notice/{id}', [NoticeController::class, 'destroy'])->name('control-panel.notice.destroy');
    Route::get('/control-panel/notice/export', [NoticeController::class, 'exportCSV'])->name('control-panel.notice.export');
    Route::put('/admin/receipt-setting/update/{id}', [ReceiptSettingController::class, 'update'])->name('receipt.setting.update');
    Route::post('/admin/receipt-setting/preview', [ReceiptSettingController::class, 'preview'])->name('receipt.setting.preview');
    Route::get('/deleted-payments', [FeeCollectionController::class, 'deletedPayments'])->name('deleted.payments');
    Route::post('/admin/fee-payment/restore/{id}', [FeeCollectionController::class, 'restorePayment'])->name('fee.payment.restore');

     Route::get('/control-panel/edit-logs', [FeeCollectionController::class, 'editLogs'])->name('edit.logs');
    Route::get('/control-panel/delete-logs', [FeeCollectionController::class, 'deleteLogs'])->name('delete.logs');
    Route::get('/admin/edit-log-details/{id}', [FeeCollectionController::class, 'editLogDetails'])->name('edit.log.details');
    Route::get('/admin/delete-log-details/{id}', [FeeCollectionController::class, 'deleteLogDetails'])->name('delete.log.details');
    Route::post('/admin/restore-payment/{id}', [FeeCollectionController::class, 'restorePayment'])->name('restore.payment');
      Route::get('/control-panel/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::get('/backup/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [BackupController::class, 'deleteBackup'])->name('backup.delete');
    
    Route::get('finance/salary', [TeacherSalaryController::class, 'index'])->name('finance.salary.index');
    Route::get('finance/salary/create', [TeacherSalaryController::class, 'create'])->name('finance.salary.create');
    Route::post('finance/salary/store', [TeacherSalaryController::class, 'store'])->name('finance.salary.store');
    Route::get('finance/salary/{id}/edit', [TeacherSalaryController::class, 'edit'])->name('finance.salary.edit');
    Route::put('finance/salary/{id}', [TeacherSalaryController::class, 'update'])->name('finance.salary.update');
    Route::delete('finance/salary/{id}', [TeacherSalaryController::class, 'destroy'])->name('finance.salary.destroy');
    Route::get('finance/salary/get-teacher-data', [TeacherSalaryController::class, 'getTeacherData'])->name('finance.salary.get-teacher-data');
    Route::get('/salary/export', [TeacherSalaryController::class, 'exportCSV'])->name('finance.salary.export');
    Route::get('/control-panel/license-history', [LicenseController::class, 'history'])
        ->name('control-panel.license-history');
         Route::get('/gallery', [GalleryController::class, 'index'])->name('control-panel.gallery');
    Route::post('/control-panel/gallery/store', [GalleryController::class, 'store'])->name('control-panel.gallery.store');
    Route::get('/control-panel/gallery/{id}', [GalleryController::class, 'show'])->name('control-panel.gallery.show');
    Route::post('/control-panel/gallery/{id}', [GalleryController::class, 'update'])->name('control-panel.gallery.update');
    Route::delete('/control-panel/gallery/{id}', [GalleryController::class, 'destroy'])->name('control-panel.gallery.destroy');
    Route::get('/control-panel/gallery/export', [GalleryController::class, 'exportCSV'])->name('control-panel.gallery.export');

});

Route::prefix('control-panel')->group(function () {
    Route::get('/query', [QueryController::class, 'index'])->name('control-panel.query');
    Route::get('/query/{id}', [QueryController::class, 'show'])->name('control-panel.query.show');
    Route::put('/query/{id}', [QueryController::class, 'update'])->name('control-panel.query.update');
    Route::delete('/query/{id}', [QueryController::class, 'destroy'])->name('control-panel.query.destroy');
    Route::get('/query/export', [QueryController::class, 'exportCSV'])->name('control-panel.query.export');
});





Route::get('/parent/login', [ParentAuthController::class, 'showLoginForm'])->name('parent.login');
Route::post('/parent/login', [ParentAuthController::class, 'login'])->name('parent.login.post');
Route::post('/parent/logout', [ParentAuthController::class, 'logout'])->name('parent.logout');

Route::middleware(['parent' , 'prevent-back'])->group(function () {
    Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
     Route::get('/parent/fees', [ParentFeesController::class, 'index'])->name('parent.fees');
    Route::get('/parent/exam', [ParentExamController::class, 'index'])->name('parent.exam');
    Route::get('/parent/result', [ParentResultController::class, 'index'])->name('parent.result');
    Route::get('/parent/profile/{id}', [ParentFeesController::class, 'show'])->name('parent.profile');
});