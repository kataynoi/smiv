<?php
namespace App\Models;
use CodeIgniter\Model;

class RiskLevelModel extends Model
{
    protected $table = 'risk_levels';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
}
