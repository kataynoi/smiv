<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['fullname', 'username', 'password', 'role'];

    // เปิดใช้งาน Timestamps เพื่อให้ created_at, updated_at ทำงานอัตโนมัติ
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // (แนะนำ) เพิ่มฟังก์ชันสำหรับ Hash รหัสผ่านก่อนบันทึก
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
}

?>