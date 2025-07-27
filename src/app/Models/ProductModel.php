<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // กำหนดฟิลด์ที่อนุญาตให้มีการเพิ่ม/แก้ไขข้อมูลผ่าน Model ได้
    protected $allowedFields    = ['name', 'price', 'stock'];

    // เปิดใช้งาน Timestamps (created_at, updated_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
