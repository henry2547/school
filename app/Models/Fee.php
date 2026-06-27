<?php

namespace App\Models;

use App\Core\Database;

final class Fee
{
    public static function invoices(): array
    {
        return Database::connection()->query(
            'SELECT fee_invoices.*, students.admission_no, CONCAT(students.first_name, " ", students.last_name) AS student_name,
                    terms.name AS term_name
             FROM fee_invoices
             JOIN students ON students.id = fee_invoices.student_id
             LEFT JOIN terms ON terms.id = fee_invoices.term_id
             ORDER BY fee_invoices.created_at DESC'
        )->fetchAll();
    }

    public static function payments(): array
    {
        return Database::connection()->query(
            'SELECT fee_payments.*, fee_invoices.invoice_no, CONCAT(students.first_name, " ", students.last_name) AS student_name
             FROM fee_payments
             JOIN fee_invoices ON fee_invoices.id = fee_payments.invoice_id
             JOIN students ON students.id = fee_invoices.student_id
             ORDER BY fee_payments.paid_at DESC
             LIMIT 50'
        )->fetchAll();
    }

    public static function createInvoice(array $data): int
    {
        Database::connection()->prepare(
            'INSERT INTO fee_invoices (student_id, term_id, invoice_no, description, amount, balance, due_date, status)
             VALUES (:student_id, :term_id, :invoice_no, :description, :amount, :amount, :due_date, "issued")'
        )->execute([
            'student_id' => $data['student_id'],
            'term_id' => $data['term_id'] ?: null,
            'invoice_no' => $data['invoice_no'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date'],
        ]);
        return (int)Database::connection()->lastInsertId();
    }

    public static function createPayment(array $data, ?int $userId): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        $pdo->prepare(
            'INSERT INTO fee_payments (invoice_id, receipt_no, amount_paid, payment_method, payment_reference, received_by)
             VALUES (:invoice_id, :receipt_no, :amount_paid, :payment_method, :payment_reference, :received_by)'
        )->execute([
            'invoice_id' => $data['invoice_id'],
            'receipt_no' => $data['receipt_no'],
            'amount_paid' => $data['amount_paid'],
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?: null,
            'received_by' => $userId,
        ]);
        $id = (int)$pdo->lastInsertId();
        $pdo->prepare(
            'UPDATE fee_invoices
             SET balance = GREATEST(balance - :amount_paid, 0),
                 status = CASE
                    WHEN GREATEST(balance - :amount_paid, 0) = 0 THEN "paid"
                    WHEN GREATEST(balance - :amount_paid, 0) < amount THEN "part_paid"
                    ELSE status
                 END
             WHERE id = :invoice_id'
        )->execute(['amount_paid' => $data['amount_paid'], 'invoice_id' => $data['invoice_id']]);
        $pdo->commit();
        return $id;
    }

    public static function nextInvoiceNo(): string
    {
        return 'INV-' . date('Y') . '-' . str_pad((string)((int)Database::connection()->query('SELECT COUNT(*) FROM fee_invoices')->fetchColumn() + 1), 4, '0', STR_PAD_LEFT);
    }

    public static function nextReceiptNo(): string
    {
        return 'RCT-' . date('Y') . '-' . str_pad((string)((int)Database::connection()->query('SELECT COUNT(*) FROM fee_payments')->fetchColumn() + 1), 4, '0', STR_PAD_LEFT);
    }
}
