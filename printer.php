<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Exception;

class PrinterController extends Controller
{
    public function printBill()
    {

      //config and teste the printer in your machine.
        $pedido = [
            "itens" => ["coca", "Tonic","Gin Tonic", "Burger", "Pizza Margarita", "Mojito", "Sex on the Beach"],
            "quantidade" => [4, 2, 2, 3, 1, 2, 2],
            "valor"     => [24, 17, 68, 120, 70, 80, 70]
        ];

        try{
            $msg = "Restaurant casino Bar\n";

            $connector = new CupsPrintConnector("TM-T20X");
     
            $printer = new Printer($connector);
            $printer->setTextSize(1, 2);
            $printer -> text($msg);
            $printer->feed();
            $printer->feed();
            foreach ($pedido["itens"] as $key => $ped){
                $printer->textRaw("{$ped}.........".$pedido['quantidade'][$key].".........".$pedido['valor'][$key]."R$\n");
            }
            $printer->feed();
            $printer->textRaw("Obrigado Suellen !\n");
            $printer->textRaw("\nTotal: ".array_sum($pedido['valor'])."R$\n");
            $printer->feed();
            $printer->feed();
            $printer->setTextSize(1, 1);
            $printer->textRaw("Rua Luiz Franca, 2647\n");
            $printer->textRaw("Celular: (41)99 718-2410\n");
            $printer->feed();
            $printer->feed();
            $printer->textRaw("Software copyright\n");
            $printer->textRaw("Lotchi Serge Gogo\n");
            $printer -> cut();
            $printer -> close();
            echo "Caiu no try";
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }

    }
}
