<?php

namespace App\Enums;

enum StudentStatus : string
{
    case INQUIRED = 'Inquired';
    case ENROLLED = 'Enrolled';
    case INCOMPLETE = 'Incomplete';
    case COMPLETE = 'Complete';
    case REFUNDED = 'Refunded';
   case ARCHIVED = 'Archived';
   case HOLD_FOR_PAYMENT = 'Hold for Payment';
   case HOLD_FOR_PAPERWORK = 'Hold for Paperwork';

   public function label(): string
   {
       return match ($this) {
         static::INQUIRED => 'Inquired',
         static::ENROLLED => 'Enrolled',
         static::INCOMPLETE => 'Incomplete',
         static::COMPLETE => 'Complete',
         static::REFUNDED => 'Refunded',
         static::ARCHIVED => 'Archived',
         static::HOLD_FOR_PAYMENT => 'Hold for Payment',
         static::HOLD_FOR_PAPERWORK => 'Hold for Paperwork',
       };
   }

   public function color(): string
   {
       return match ($this) {
         static::INQUIRED => 'yellow',
         static::ENROLLED => 'green',
         static::INCOMPLETE => 'orange',
         static::COMPLETE => 'blue',
         static::REFUNDED => 'red',
         static::ARCHIVED => 'gray',
         static::HOLD_FOR_PAYMENT => 'purple',
         static::HOLD_FOR_PAPERWORK => 'pink',
       };
   }
}
