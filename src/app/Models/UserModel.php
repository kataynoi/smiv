<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'fullname',
        'username',
        'position',
        'password',
        'status',
        'ampurcodefull',
        'approved_by'
    ];

    // เปิดใช้งาน Timestamps เพื่อให้ created_at, updated_at ทำงานอัตโนมัติ
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // (แนะนำ) เพิ่มฟังก์ชันสำหรับ Hash รหัสผ่านก่อนบันทึก
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * ทำการ Hash รหัสผ่านโดยอัตโนมัติ
     */
    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
}

?>