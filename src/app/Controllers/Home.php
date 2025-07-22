<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('auth/login.php');
    }

    // เพิ่มฟังก์ชันนี้เข้าไป
    public function dbtest()
    {
        // เรียกใช้บริการฐานข้อมูล
        $db = \Config\Database::connect();

        try {
            // พยายามเชื่อมต่อ
            $db->initialize();
            echo "<h1>เชื่อมต่อฐานข้อมูลสำเร็จ!</h1>";
            echo "<p>คุณพร้อมที่จะเริ่มพัฒนาโปรเจกต์แล้ว</p>";
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // หากเกิดข้อผิดพลาด
            echo "<h1>เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล</h1>";
            echo "<p>กรุณาตรวจสอบการตั้งค่าในไฟล์ .env</p>";
            echo "<p><strong>ข้อความจากระบบ:</strong> " . $e->getMessage() . "</p>";
        }
    }
}