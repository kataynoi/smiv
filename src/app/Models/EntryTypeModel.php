<?php
namespace App\Models;
use CodeIgniter\Model;

class EntryTypeModel extends Model
{
    protected $table = 'entry_types';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
}