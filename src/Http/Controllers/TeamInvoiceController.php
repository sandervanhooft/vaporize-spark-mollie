<?php
declare(strict_types=1);

namespace SanderVanHooft\VaporizeSparkMollie\Http\Controllers;

use Dompdf\Options;
use Illuminate\Http\Request;
use Laravel\Cashier\Order\Invoice;
use Laravel\Spark\Http\Controllers\Settings\Teams\Billing\InvoiceController as Base;
use Laravel\Spark\Spark;
use Laravel\Spark\Team;

class TeamInvoiceController extends Base
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
     * @param  \Laravel\Spark\Team  $team
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, Team $team, $id)
    {
        abort_unless($request->user()->ownsTeam($team), 403);

        $invoice = $team->localInvoices()
            ->where('id', $id)->firstOrFail();

        /** @var \Illuminate\Http\Response $response */
        $response = $team->downloadInvoice(
            $invoice->provider_id,
            ['id' => $invoice->id] + Spark::invoiceDataFor($team),
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
