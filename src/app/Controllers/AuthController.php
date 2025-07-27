<?php
// --------------------------------------------------------------------
// File: app/Controllers/AuthController.php
// --------------------------------------------------------------------
// Controller หลักสำหรับจัดการระบบยืนยันตัวตน (Authentication)
// --------------------------------------------------------------------
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserRoleModel;
use CodeIgniter\Controller;
use App\Models\CchangwatModel;
use App\Models\CampurModel;

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
     * แสดงหน้าฟอร์มสมัครสมาชิก
     */
    public function register()
    {
        $cchangwatModel = new CchangwatModel();
        $data = [
            'changwats' => $cchangwatModel->orderBy('changwatname', 'ASC')->findAll(),
            'validation' => \Config\Services::validation() // ส่ง validation service ไปที่ view
        ];
        return view('auth/register_view', $data);
    }
    /**
     * ประมวลผลข้อมูลที่ส่งมาจากฟอร์ม Login
     */
    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();
        $UserRoleModel = new UserRoleModel();


        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $userModel->where('username', $username)->first();


        if ($user && password_verify($password, $user['password'])) {
            $roles = $UserRoleModel->getRolesForUser($user['id']);
            $ses_data = [
                'user_id'       => $user['id'],
                'fullname'      => $user['fullname'],
                'username'      => $user['username'],
                'position'      => $user['position'],
                'ampurcodefull' => $user['ampurcodefull'], // <-- เพิ่มบรรทัดนี้
                'roles'         => $roles, // <-- เก็บสิทธิ์เป็น Array
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
    public function attemptRegister()
    {
        // 1. ตั้งกฎการตรวจสอบข้อมูล
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[150]',
            'position' => 'required|max_length[100]',
            'username' => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[8]|max_length[255]',
            'pass_confirm' => 'required|matches[password]',
            'ampurcodefull' => 'required'
        ];

        // 2. ตรวจสอบข้อมูล
        if (!$this->validate($rules)) {
            // ถ้าข้อมูลไม่ถูกต้อง ให้กลับไปที่หน้าฟอร์มพร้อมกับข้อผิดพลาด
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. ถ้าข้อมูลถูกต้อง ให้เตรียมบันทึก
        $userModel = new UserModel();
        $data = [
            'fullname'      => $this->request->getPost('fullname'),
            'position'      => $this->request->getPost('position'),
            'username'      => $this->request->getPost('username'),
            'password'      => $this->request->getPost('password'),
            'ampurcodefull' => $this->request->getPost('ampurcodefull'),
            'status'        => 0, // 0 = รออนุมัติ
        ];

        // 4. บันทึกข้อมูล
        if ($userModel->save($data)) {
            // ถ้าบันทึกสำเร็จ ให้ไปที่หน้า login พร้อมข้อความแจ้งเตือน
            return redirect()->to('/login')->with('message', 'สมัครสมาชิกสำเร็จ! กรุณารอการอนุมัติจากผู้ดูแลระบบ');
        } else {
            // ถ้าเกิดข้อผิดพลาด
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    /**
     * ฟังก์ชันสำหรับ AJAX เพื่อดึงข้อมูลอำเภอ
     */
    public function getAmphures()
    {
        $provinceCode = $this->request->getVar('province_code');
        $campurModel = new CampurModel();
        $amphures = $campurModel->where('changwatcode', $provinceCode)->orderBy('ampurname', 'ASC')->findAll();
        return $this->response->setJSON($amphures);
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
