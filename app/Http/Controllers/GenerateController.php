<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use app\Models\ProductTransaction;

class GenerateController extends Controller
{
    public function invoice($id)
{
    $trx = ProductTransaction::with('produk')->findOrFail($id);

    $pdf = Pdf::loadView('pdf.invoice', compact('trx'));

    return $pdf->download('invoice-'.$trx->id.'.pdf');
}
}
