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
      'transfer_account_id', // tambahkan field ini jika belum ada
      'deposit_account_id',  // tambahkan field ini jika belum ada
      'new_deposit_account_id',
      'charge', // Add charge field
      'paid_date', // Add paid_date field
   ];


   protected static function boot()
   {
      parent::boot();

      static::creating(function ($model) {
         // Perform actions before creating
         date_default_timezone_set('Asia/Jakarta');
         $year = date('Y');
         $month = date('m');
         $number = Bill::where('number_invoice', "LIKE", '%' . $year . '%')->count();

         $model->number_invoice = $year . "/" . $month . "/" . str_pad($number + 1, 4, '0', STR_PAD_LEFT);

         // Set default transfer_account_id if not set
         if (is_null($model->transfer_account_id)) {
            $model->transfer_account_id = 110; // Default id for Piutang Monthly Fee
         }

         // Set default deposit_account_id if not set
         if (is_null($model->deposit_account_id)) {
            $model->deposit_account_id = 22; // Default id for Monthly Fee
         }

         static::updating(function ($model) {
            if ($model->isDirty('deposit_account_id')) {
               $oldDepositAccountId = $model->getOriginal('deposit_account_id');
               $newDepositAccountId = $model->deposit_account_id;

               BillDepositAccountChange::create([
                  'bill_id' => $model->id,
                  'old_deposit_account_id' => $oldDepositAccountId,
                  'new_deposit_account_id' => $newDepositAccountId,
                  'changed_at' => now(),
               ]);
            }
         });
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

   public function transferAccount()
   {
      return $this->belongsTo(AccountNumber::class, 'transfer_account_id');
   }

   public function depositAccount()
   {
      return $this->belongsTo(AccountNumber::class, 'deposit_account_id');
   }

   public function newAccount()
   {
      return $this->belongsTo(AccountNumber::class, 'new_deposit_account_id');
   }

   const TYPE_MATERIAL_FEE = 'Material Fee';

   public function isMaterialFee()
   {
      return $this->type === self::TYPE_MATERIAL_FEE;
   }

   public function material_fee_installment()
   {
      return $this->hasOne(MaterialFeeInstallment::class, 'bill_id');
   }

   // Add helper method to check if bill has charge
   public function hasCharge()
   {
      return $this->charge > 0;
   }

   // Add helper method to get total amount including charge
   public function getTotalAmountWithCharge()
   {
      return $this->amount + $this->charge;
   }
}
