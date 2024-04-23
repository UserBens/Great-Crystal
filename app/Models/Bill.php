<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

date_default_timezone_set('Asia/Jakarta');

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'student_id',
      'type',
      'subject',
      'amount',
      'dp',
      'paidOf',
      'discount',
      'deadline_invoice',
      'date_change_bill',
      'installment',
      'amount_installment',
      'created_by',
      'created_at',	
      'updated_at',
      'number_invoice',	
    ]; 


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Perform actions before creating
            date_default_timezone_set('Asia/Jakarta');
            $year = date('Y');
            $month = date('m');
            $number = Bill::where('number_invoice', "LIKE", '%'.$year.'%')->count();

            $model->number_invoice = $year."/".$month."/".str_pad($number+1, 4, '0', STR_PAD_LEFT);

        });
    }

    
   public function student()
   {
      return $this->belongsTo(Student::class, 'student_id');
   }


   public function bill_collection()
   {
      return $this->hasMany(BillCollection::class, 'bill_id');
   }

   public function bill_installments()
   {
      return $this->belongsToMany(Bill::class, 'installment_pakets', 'main_id', 'child_id');
   }

   public function bill_status()
   {
      return $this->hasMany(statusInvoiceMail::class, 'bill_id');
   }
}