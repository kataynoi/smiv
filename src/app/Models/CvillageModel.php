<?php
namespace App\Models;
use CodeIgniter\Model;

class CvillageModel extends Model
{
    protected $table = 'cvillage';
    protected $primaryKey = 'villagecodefull';
    protected $returnType = 'array';
}
