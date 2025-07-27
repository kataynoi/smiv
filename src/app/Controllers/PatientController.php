<?php

namespace App\Controllers;

// เพิ่ม use statements สำหรับ Models ใหม่
use App\Models\PatientModel;
use App\Models\CchangwatModel;
use App\Models\CampurModel;
use App\Models\CtambonModel;
use App\Models\CvillageModel;
use App\Models\EntryTypeModel;
use App\Models\RiskLevelModel;

class PatientController extends BaseController
{
    // แสดงหน้าหลักพร้อมข้อมูลสำหรับ Dropdowns
    public function index()
    {
        $cchangwatModel = new CchangwatModel();
        $entryTypeModel = new EntryTypeModel();
        $riskLevelModel = new RiskLevelModel();
        // ดึงค่า roles จาก session ออกมา
        // 1. กำหนดสิทธิ์ที่สามารถเข้าถึงหน้านี้ได้ (เป็น Array)
        $requiredRoles = [4, 5]; // Role 'PatientsList' และ 'Edit'
        // 2. ดึงค่า roles ของผู้ใช้จาก session ออกมา
        $userRoles = session()->get('roles') ?? []; // จะได้ค่าเป็น Array เช่น [2, 4] หรือ []
        // 3. หาค่าที่ซ้ำกันระหว่าง 2 Array
        $matchingRoles = array_intersect($requiredRoles, $userRoles);
        // 4. ตรวจสอบ: ถ้าไม่มีค่าที่ซ้ำกันเลย (Array ว่าง) แสดงว่าไม่มีสิทธิ์
        if (empty($matchingRoles)) {
            // ถ้าไม่มีสิทธิ์ ให้แสดงข้อความหรือ Redirect ไปหน้าอื่น
            return redirect()->to('/dashboard')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        $data = [
            'changwats'   => $cchangwatModel->orderBy('changwatname', 'ASC')->findAll(),
            'entry_types' => $entryTypeModel->findAll(),
            'risk_levels' => $riskLevelModel->findAll()
        ];

        return view('patient_crud_view', $data);
    }

    // ดึงข้อมูลผู้ป่วยสำหรับ DataTables (ทำการ JOIN)
    public function fetchPatients()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('patients');
        $builder->select('patients.*, risk_levels.name as risk_level_name, risk_levels.color_hex');
        $builder->join('risk_levels', 'risk_levels.id = patients.risk_level_id', 'left');
        $query = $builder->get();

        $data['data'] = $query->getResultArray();
        return $this->response->setJSON($data);
    }

    // เพิ่มข้อมูลผู้ป่วยใหม่
    public function store()
    {
        $requiredRoles = [4]; 
        $userRoles = session()->get('roles') ?? []; // จะได้ค่าเป็น Array เช่น [2, 4] หรือ []
        $matchingRoles = array_intersect($requiredRoles, $userRoles);
        if (empty($matchingRoles)) {
            return redirect()->to('/patients')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        $patientModel = new PatientModel();
        $data = $this->request->getPost();
        if ($patientModel->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
    }

    // ดึงข้อมูลผู้ป่วย 1 คนเพื่อแก้ไข
    public function fetchSinglePatient($id)
    {
        $patientModel = new PatientModel();
        $data = $patientModel->find($id);
        return $this->response->setJSON($data);
    }

    // อัปเดตข้อมูลผู้ป่วย
    public function update()
    {
        $patientModel = new PatientModel();
        $id = $this->request->getPost('id');
        $data = $this->request->getPost();
        if ($patientModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'แก้ไขข้อมูลสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
    }

    // ลบข้อมูลผู้ป่วย
    public function delete($id)
    {
        $patientModel = new PatientModel();
        if ($patientModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
    }

    // อัปเดตเฉพาะระดับความเสี่ยง
    public function updateRiskLevel()
    {
        $patientModel = new PatientModel();
        $id = $this->request->getPost('patient_id');
        $data = [
            'risk_level_id' => $this->request->getPost('risk_level_id')
        ];

        if ($patientModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'เปลี่ยนระดับความเสี่ยงสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
    }

    // --- Ajax Functions for Dropdowns ---
    public function getAmphures()
    {
        $provinceCode = $this->request->getVar('province_code');
        $campurModel = new CampurModel();
        $amphures = $campurModel->where('changwatcode', $provinceCode)->orderBy('ampurname', 'ASC')->findAll();
        return $this->response->setJSON($amphures);
    }

    public function getTambons()
    {
        $amphureCode = $this->request->getVar('amphure_code');
        $ctambonModel = new CtambonModel();
        $tambons = $ctambonModel->where('ampurcode', $amphureCode)->orderBy('tambonname', 'ASC')->findAll();
        return $this->response->setJSON($tambons);
    }

    public function getVillages()
    {
        $tambonCode = $this->request->getVar('tambon_code');
        $cvillageModel = new CvillageModel();
        $villages = $cvillageModel->where('tamboncode', $tambonCode)->orderBy('villagename', 'ASC')->findAll();
        return $this->response->setJSON($villages);
    }
}
