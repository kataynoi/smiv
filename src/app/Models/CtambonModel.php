<?php
namespace App\Models;
use CodeIgniter\Model;

class CtambonModel extends Model
{
    protected $table = 'ctambon';
    protected $primaryKey = 'tamboncodefull';
    protected $returnType = 'array';
}