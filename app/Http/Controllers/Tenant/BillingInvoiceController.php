<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Services\InvoicePdfService;
use Symfony\Component\HttpFoundation\Response;

class BillingInvoiceController extends Controller
{
    public function download($tenant,BillingInvoice $invoice, InvoicePdfService $pdfService): Response
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);
        abort_unless($invoice->tenant_id === tenant()->id, 404);

        return response($pdfService->render($invoice), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$invoice->invoice_number.'.pdf"',
        ]);
    }
}
