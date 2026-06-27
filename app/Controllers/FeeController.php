<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\Fee;
use App\Models\Lookup;

final class FeeController
{
    public function index(): void
    {
        Auth::requirePermission('finance.view');
        View::render('fees/index', [
            'title' => 'Fees',
            'invoices' => Fee::invoices(),
            'payments' => Fee::payments(),
            'students' => Lookup::students(),
            'terms' => Lookup::terms(),
            'nextInvoiceNo' => Fee::nextInvoiceNo(),
            'nextReceiptNo' => Fee::nextReceiptNo(),
        ]);
    }

    public function invoice(): void
    {
        Auth::requirePermission('finance.manage');
        Csrf::verify();
        $data = Request::only(['student_id', 'term_id', 'invoice_no', 'description', 'amount', 'due_date']);
        $id = Fee::createInvoice($data);
        AuditLog::record(Auth::id(), 'create', 'fee_invoices', $id, ['invoice_no' => $data['invoice_no']]);
        $_SESSION['flash_success'] = 'Invoice created successfully.';
        redirect('/fees');
    }

    public function payment(): void
    {
        Auth::requirePermission('finance.manage');
        Csrf::verify();
        $data = Request::only(['invoice_id', 'receipt_no', 'amount_paid', 'payment_method', 'payment_reference']);
        $id = Fee::createPayment($data, Auth::id());
        AuditLog::record(Auth::id(), 'create', 'fee_payments', $id, ['receipt_no' => $data['receipt_no']]);
        $_SESSION['flash_success'] = 'Payment recorded successfully.';
        redirect('/fees');
    }
}
