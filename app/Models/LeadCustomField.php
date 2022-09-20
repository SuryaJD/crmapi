<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCustomField extends Model
{
    use HasFactory;

    protected $connection = "vtiger";

    protected $table = 'vtiger_leadscf';
}
