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
        if (!$this->validInvoice($data)) {
            $_SESSION['flash_error'] = 'Complete all invoice fields and enter an amount greater than zero.';
            redirect('/fees');
        }
        try {
            $id = Fee::createInvoice($data);
        } catch (\Throwable $exception) {
            $_SESSION['flash_error'] = 'Invoice could not be created. The invoice number may already exist.';
            redirect('/fees');
        }
        AuditLog::record(Auth::id(), 'create', 'fee_invoices', $id, ['invoice_no' => $data['invoice_no']]);
        $_SESSION['flash_success'] = 'Invoice created successfully.';
        redirect('/fees');
    }

    public function payment(): void
    {
        Auth::requirePermission('finance.manage');
        Csrf::verify();
        $data = Request::only(['invoice_id', 'receipt_no', 'amount_paid', 'payment_method', 'payment_reference']);
        if (!$this->validPayment($data)) {
            $_SESSION['flash_error'] = 'Enter a receipt number and payment amount greater than zero.';
            redirect('/fees');
        }
        try {
            $id = Fee::createPayment($data, Auth::id());
        } catch (\RuntimeException $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
            redirect('/fees');
        } catch (\Throwable $exception) {
            $_SESSION['flash_error'] = 'Payment could not be recorded. The receipt number may already exist.';
            redirect('/fees');
        }
        AuditLog::record(Auth::id(), 'create', 'fee_payments', $id, ['receipt_no' => $data['receipt_no']]);
        $_SESSION['flash_success'] = 'Payment recorded successfully.';
        redirect('/fees');
    }

    private function validInvoice(array $data): bool
    {
        return (int)$data['student_id'] > 0
            && $data['invoice_no'] !== ''
            && $data['description'] !== ''
            && (float)$data['amount'] > 0
            && $data['due_date'] !== '';
    }

    private function validPayment(array $data): bool
    {
        return (int)$data['invoice_id'] > 0
            && $data['receipt_no'] !== ''
            && (float)$data['amount_paid'] > 0
            && in_array($data['payment_method'], ['cash', 'bank', 'mpesa', 'paypal', 'stripe'], true);
    }
}
