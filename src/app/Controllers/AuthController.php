<?php
// --------------------------------------------------------------------
// File: app/Controllers/AuthController.php
// --------------------------------------------------------------------
// Controller หลักสำหรับจัดการระบบยืนยันตัวตน (Authentication)
// --------------------------------------------------------------------
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    /**
     * แสดงหน้าฟอร์มสำหรับ Login
     */
    public function index()
    {
        // ถ้าผู้ใช้ Login อยู่แล้ว ให้ redirect ไปหน้า dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        helper(['form']);
        return view('auth/login');
    }

    /**
     * ประมวลผลข้อมูลที่ส่งมาจากฟอร์ม Login
     */
    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $ses_data = [
                'user_id'       => $user['id'],
                'fullname'      => $user['fullname'],
                'username'      => $user['username'],
                'position'      => $user['position'],
                'role'          => $user['role'],
                'isLoggedIn'    => TRUE
            ];
            $session->set($ses_data);
            return redirect()->to('dashboard');
        } else {
            $session->setFlashdata('msg', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            return redirect()->to('login');
        }
    }

    /**
     * [ฟังก์ชันชั่วคราว] สำหรับรีเซ็ตรหัสผ่านของผู้ใช้
     * เพื่อสร้าง Hash ที่ถูกต้องสำหรับสภาพแวดล้อมปัจจุบัน
     */
    public function resetPassword()
    {
        $userModel = new UserModel();
        
        // ค้นหาผู้ใช้ 'mana' จาก ID (จากข้อมูลดีบักคือ id=3)
        $userId = 3; 
        
        // ข้อมูลใหม่ที่จะอัปเดต (รหัสผ่านใหม่คือ '1234')
        $data = [
            'password' => '1234'
        ];

        // ใช้ Model เพื่อบันทึก ซึ่งจะไปเรียกใช้ beforeUpdate hook (hashPassword) โดยอัตโนมัติ
        if ($userModel->update($userId, $data)) {
            echo "<h1>รีเซ็ตรหัสผ่านสำหรับผู้ใช้ 'mana' สำเร็จ!</h1>";
            echo "<p>รหัสผ่านใหม่คือ '1234'</p>";
            echo "<p>ตอนนี้คุณสามารถกลับไปที่หน้า Login และเข้าระบบได้แล้ว</p>";
            echo "<p><a href='" . site_url('login') . "'>กลับไปหน้า Login</a></p>";
        } else {
            echo "<h1>เกิดข้อผิดพลาดในการรีเซ็ตรหัสผ่าน</h1>";
        }
    }

    /**
     * ทำการ Logout ออกจากระบบ
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
?>