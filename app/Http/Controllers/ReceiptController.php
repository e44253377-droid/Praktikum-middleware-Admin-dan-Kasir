<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ReceiptController extends Controller
{
    // 1. SESUAI BKPM - Printer Asli POS-58
    public function printReceipt()
    {
        try {
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);
            $printer->text("Hello, ini struk test\n");
            $printer->cut();
            $printer->close();
            return response()->json(['message' => 'Struk berhasil dicetak']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // 2. SIMULASI + 5) CUSTOM FORMATTING - Kayak teman kamu
    public function printStruk()
    {
        try {
            $filePath = storage_path('app/struk.txt');
            $connector = new FilePrintConnector($filePath);
            $printer = new Printer($connector);

            // Align CENTER + Bold - Nama Toko
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true); // BOLD ON
            $printer->text("TOKO GOOD\n");
            $printer->setEmphasis(false); // BOLD OFF
            $printer->text("Jl. Contoh No. 1, Waingapu\n");
            $printer->text("------------------------------\n");

            // Align LEFT - Isi struk
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Kasir : Admin\n");
            $printer->text("Tanggal : " . date('d-m-Y H:i') . "\n");
            $printer->text("------------------------------\n");
            $printer->text("Item 1          Rp 10.000\n");
            $printer->text("Item 2          Rp 15.000\n");
            $printer->text("------------------------------\n");

            // Align RIGHT + Underline - Total
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setUnderline(true); // UNDERLINE ON
            $printer->text("Total Rp 25.000\n");
            $printer->setUnderline(false); // UNDERLINE OFF

            // QR Code dan Barcode - CENTER
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\nScan untuk promo:\n");
            $printer->qrCode("https://tokogood.com/promo", Printer::QR_ECLEVEL_L, 5);
            $printer->text("\n");
            $printer->barcode("123456789012", Printer::BARCODE_CODE39);
            $printer->text("123456789012\n");

            // Terima kasih + Cut
            $printer->text("\nTerima Kasih!\n");
            $printer->cut();
            $printer->close();

            return response()->json(['message' => 'Struk berhasil dicetak di storage/app/struk.txt']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}