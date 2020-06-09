<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie\Http\Controllers;

use Dompdf\Options;
use Illuminate\Http\Request;
use Laravel\Cashier\Order\Invoice;
use Laravel\Spark\Http\Controllers\Settings\Billing\InvoiceController as Base;
use Laravel\Spark\Spark;

class UserInvoiceController extends Base
{
    /**
     * A path DomPdf can temporarily write to (and read from).
     *
     * @var string
     */
    protected const DOMPDF_TEMP_PATH = '/tmp';

    /**
     * Download the invoice with the given ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        $invoice = $request->user()->localInvoices()
            ->where('id', $id)->firstOrFail();

        /** @var \Illuminate\Http\Response $response */
        $response = $request->user()->downloadInvoice(
            $invoice->provider_id,
            ['id' => $invoice->id] + Spark::invoiceDataFor($request->user()),
            Invoice::DEFAULT_VIEW,
            $this->getDomPdfOptions()
        );

        $response->headers->add(['X-Vapor-Base64-Encode' => 'True']);

        return $response;
    }

    /**
     * @return \Dompdf\Options
     */
    protected function getDomPdfOptions()
    {
        return (new Options)
            ->setTempDir(static::DOMPDF_TEMP_PATH)
            ->setFontDir(static::DOMPDF_TEMP_PATH)
            ->setFontCache(static::DOMPDF_TEMP_PATH);
    }
}
