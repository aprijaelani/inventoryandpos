<?php

namespace App\Http\Controllers;

use PDF;
use barcode;
use App\Barang;
use Illuminate\Http\Request;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class PDFController extends Controller
{
	public function barcode($id)
    {
        $barang = Barang::where('id', $id)->first();

        $barcode = new BarcodeGenerator();
        $barcode->setText($barang->kode_barcode);
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(1);
        $barcode->setThickness(12);
        $barcode->setFontSize(8);
        $code = $barcode->generate();

        return $this->pdf('data:image/png;base64,'.$code, $id);
    } 

    public function pdf($barcode, $id)
    {
    	// return $this->barcode($id);
    	$barangs = Barang::where('id', $id)->value('nama_barang');
        $pdf = PDF::loadView('master.barang.pdf', compact('barcode'));
        // echo $barangs;exit();
        return $pdf -> stream('Barcode_'.$barangs.'.pdf');
    }

     
}
