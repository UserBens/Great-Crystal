<?php

namespace Database\Seeders;

use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NumberInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        $year = date('Y');
        $increment = 0;
        $data = Bill::orderBy('created_at', 'asc')->get(['id', 'created_at']);
        foreach($data as $invoice){
            $year_invoice = date('Y', strtotime($invoice->created_at));
            $month_invoice = date('m', strtotime($invoice->created_at));

            if($year == $year_invoice){
                $increment++;
                $number_invoice = $year.'/'.$month_invoice.'/'.str_pad($increment,4,"0",STR_PAD_LEFT);
            } else {
                $increment = 1;
                $year = $year_invoice;
                $number_invoice = $year.'/'.$month_invoice.'/'.str_pad($increment,4,"0",STR_PAD_LEFT);
                info($year);
            }
            Bill::where('id', $invoice->id)->update([
                'number_invoice'=>$number_invoice,
            ]);
        }
    }
}
