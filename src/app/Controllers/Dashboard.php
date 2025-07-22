<?php
// --------------------------------------------------------------------
// File: app/Controllers/Dashboard.php
// --------------------------------------------------------------------
// Controller ตัวอย่างสำหรับเรียกใช้หน้า Dashboard
// --------------------------------------------------------------------
namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // ส่งข้อมูลไปให้ View (ถ้ามี)
        $data = [
            'page_title' => 'แดชบอร์ดหลัก'
        ];
        return view('dashboard', $data);
    }
}