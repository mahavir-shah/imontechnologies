<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class TermAndCondition extends Model{
    
    use HasFactory;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = ['customer_type', 'content'];

    protected $table = 'term_and_condition';
    
}