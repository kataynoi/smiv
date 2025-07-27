<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;

class AdminController extends BaseController
{
    /**
     * แสดงหน้าหลักสำหรับอนุมัติผู้ใช้
     */
    public function userApproval()
    {
        // --- ตรวจสอบสิทธิ์ ---
        // อนุญาตให้เฉพาะ 'Adminอำเภอ' (Role ID = 3) เข้าถึงหน้านี้
        if (!in_array(3, $this->currentUserRoles)) {
            return redirect()->to('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $roleModel = new RoleModel();
        $excludedRoles = [1, 2];
        $data = [
            // ดึง Role ทั้งหมดไปแสดงใน Modal (ยกเว้น SuperAdmin)
            'roles' => $roleModel->whereNotIn('id', $excludedRoles)->findAll()
        ];

        return view('admin/approval_view', $data);
    }

    /**
     * ดึงข้อมูลผู้ใช้ที่รออนุมัติสำหรับ DataTables (ผ่าน AJAX)
     */
    public function fetchPendingUsers()
    {
        // ตรวจสอบสิทธิ์อีกครั้งเพื่อความปลอดภัย
        if (!in_array(3, $this->currentUserRoles)) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $userModel = new UserModel();

        // ดึง ampurcodefull ของ Admin ที่ Login อยู่ จาก BaseController
        $adminAmphurCode = $this->currentUser['ampurcodefull'];

        // ค้นหาผู้ใช้ที่ status = 0 และมี ampurcodefull ตรงกับ Admin
        $pendingUsers = $userModel->where('status', 0)
                                  ->where('ampurcodefull', $adminAmphurCode)
                                  ->findAll();
        
        $data['data'] = $pendingUsers;
        return $this->response->setJSON($data);
    }

    /**
     * ประมวลผลการอนุมัติผู้ใช้และกำหนดสิทธิ์ (ผ่าน AJAX)
     */
    public function processApproval()
    {
        // ตรวจสอบสิทธิ์
        if (!in_array(3, $this->currentUserRoles)) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $userModel = new UserModel();
        $userRoleModel = new UserRoleModel();

        $userId = $this->request->getPost('user_id');
        $roles = $this->request->getPost('roles'); // นี่คือ Array ของ role_id

        // --- เริ่ม Transaction ---
        $this->db->transStart();

        // 1. อัปเดตสถานะผู้ใช้
        $userModel->update($userId, [
            'status' => 1, // 1 = ใช้งานได้
            'approved_by' => $this->currentUser['id'] // ID ของ Admin ที่อนุมัติ
        ]);

        // 2. ลบ Role เก่า (ถ้ามี) และกำหนด Role ใหม่
        $userRoleModel->where('user_id', $userId)->delete();
        if (!empty($roles)) {
            foreach ($roles as $roleId) {
                $userRoleModel->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
            }
        }
        
        // --- จบ Transaction ---
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'อนุมัติผู้ใช้งานสำเร็จ']);
    }

    /**
     * ประมวลผลการปฏิเสธผู้ใช้ (ผ่าน AJAX)
     */
    public function rejectUser($id)
    {
        // ตรวจสอบสิทธิ์
        if (!in_array(3, $this->currentUserRoles)) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $userModel = new UserModel();
        $userModel->update($id, ['status' => 3]); // 3 = ถูกปฏิเสธ

        return $this->response->setJSON(['status' => 'success', 'message' => 'ปฏิเสธการสมัครเรียบร้อยแล้ว']);
    }
}
