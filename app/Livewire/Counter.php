<?php

namespace App\Livewire;

use Livewire\Component;
use WireUi\Traits\Actions;

class Counter extends Component
{
   use Actions;

   public $count = 1;

   public function increment(): void
   {
      $this->count++;
      $this->notification()->success(
         $title = 'Profile saved',
         $description = 'Your profile was successfully saved'
      );
   }

   public function decrement()
   {
      $this->count--;
   }

   public function render()
   {
      return view('livewire.counter');
   }
}