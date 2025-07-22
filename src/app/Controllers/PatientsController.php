<?php
namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\RiskLevelModel;
use App\Models\EntryTypeModel;
// --------------------------------------------------------------------
// File: app/Controllers/PatientsController.php (สร้างไฟล์ใหม่)
// --------------------------------------------------------------------
// Controller สำหรับจัดการข้อมูลผู้ป่วยทั้งหมด (CRUD)
// --------------------------------------------------------------------
class PatientsController extends BaseController
{
    /**
     * แสดงหน้ารายชื่อผู้ป่วยทั้งหมด
     */
    public function index()
    {
        $patientModel = new PatientModel();
        
        // ใช้ฟังก์ชันที่สร้างขึ้นใน Model เพื่อดึงข้อมูลพร้อม JOIN
        $data['patients'] = $patientModel->getPatientsWithDetails();

        return view('patient/index', $data);
    }

    /**
     * แสดงหน้าฟอร์มสำหรับสร้างผู้ป่วยใหม่
     */
    public function create()
    {
        helper(['form']);
        
        // โหลดข้อมูล Master Data สำหรับ Dropdowns
        $riskLevelModel = new RiskLevelModel();
        $entryTypeModel = new EntryTypeModel();

        $data = [
            'risk_levels' => $riskLevelModel->findAll(),
            'entry_types' => $entryTypeModel->findAll()
        ];

        return view('patient/create', $data);
    }

    /**
     * จัดการการบันทึกข้อมูลผู้ป่วยใหม่
     */
    public function store()
    {
        helper(['form']);
        $session = session();

        // กำหนดกฎการตรวจสอบข้อมูล
        $rules = [
            'id_card'        => 'required|exact_length[13]|is_unique[patients.id_card]',
            'firstname'      => 'required|min_length[2]|max_length[100]',
            'lastname'       => 'required|min_length[2]|max_length[100]',
            'screening_date' => 'required|valid_date',
            'entry_type_id'  => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            // ถ้าข้อมูลไม่ถูกต้อง ให้กลับไปหน้าฟอร์มพร้อม Error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // ถ้าข้อมูลถูกต้อง, บันทึกลงฐานข้อมูล
        $patientModel = new PatientModel();
        $data = [
            'id_card'        => $this->request->getVar('id_card'),
            'firstname'      => $this->request->getVar('firstname'),
            'lastname'       => $this->request->getVar('lastname'),
            'screening_date' => $this->request->getVar('screening_date'),
            'birthdate'      => $this->request->getVar('birthdate'),
            'address_text'   => $this->request->getVar('address_text'),
            'phone_number'   => $this->request->getVar('phone_number'),
            'entry_type_id'  => $this->request->getVar('entry_type_id'),
            'risk_level_id'  => 1, // กำหนดค่าเริ่มต้นเป็น "ยังไม่ประเมิน"
            'registrar_id'   => session()->get('user_id'), // ID ของผู้ใช้ที่ Login อยู่
        ];

        if ($patientModel->save($data)) {
            $session->setFlashdata('success', 'บันทึกข้อมูลผู้ป่วยสำเร็จ');
            return redirect()->to('/patients');
        } else {
            $session->setFlashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            return redirect()->back()->withInput();
        }
    }

    /**
     * แสดงข้อมูลผู้ป่วย 1 คน
     */
    public function show($id = null)
    {
        $patientModel = new PatientModel();
        $data['patient'] = $patientModel->getPatientDetails($id);

        if (empty($data['patient'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้ป่วย ID: ' . $id);
        }

        return view('patient/show', $data);
    }

    /**
     * แสดงหน้าฟอร์มสำหรับแก้ไขข้อมูลผู้ป่วย
     */
    public function edit($id = null)
    {
        helper(['form']);
        $patientModel = new PatientModel();
        $data['patient'] = $patientModel->find($id);

        if (empty($data['patient'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ไม่พบข้อมูลผู้ป่วย ID: ' . $id);
        }

        // โหลดข้อมูล Master Data สำหรับ Dropdowns
        $riskLevelModel = new RiskLevelModel();
        $entryTypeModel = new EntryTypeModel();
        $data['risk_levels'] = $riskLevelModel->findAll();
        $data['entry_types'] = $entryTypeModel->findAll();

        return view('patient/edit', $data);
    }

    /**
     * จัดการการอัปเดตข้อมูลผู้ป่วย
     */
    public function update($id = null)
    {
        helper(['form']);
        $session = session();

        // กำหนดกฎการตรวจสอบข้อมูล (อนุญาตให้ id_card ซ้ำกับของตัวเองได้)
        $rules = [
            'id_card'        => "required|exact_length[13]|is_unique[patients.id_card,id,{$id}]",
            'firstname'      => 'required|min_length[2]|max_length[100]',
            'lastname'       => 'required|min_length[2]|max_length[100]',
            'screening_date' => 'required|valid_date',
            'entry_type_id'  => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $patientModel = new PatientModel();
        $data = [
            'id_card'        => $this->request->getVar('id_card'),
            'firstname'      => $this->request->getVar('firstname'),
            'lastname'       => $this->request->getVar('lastname'),
            'screening_date' => $this->request->getVar('screening_date'),
            'birthdate'      => $this->request->getVar('birthdate'),
            'address_text'   => $this->request->getVar('address_text'),
            'phone_number'   => $this->request->getVar('phone_number'),
            'entry_type_id'  => $this->request->getVar('entry_type_id'),
            'risk_level_id'  => $this->request->getVar('risk_level_id'),
        ];

        if ($patientModel->update($id, $data)) {
            $session->setFlashdata('success', 'อัปเดตข้อมูลผู้ป่วยสำเร็จ');
            return redirect()->to('/patients');
        } else {
            $session->setFlashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            return redirect()->back()->withInput();
        }
    }

    /**
     * ลบข้อมูลผู้ป่วย
     */
    public function delete($id = null)
    {
        $patientModel = new PatientModel();
        $session = session();

        if ($patientModel->delete($id)) {
            $session->setFlashdata('success', 'ลบข้อมูลผู้ป่วยสำเร็จ');
        } else {
            $session->setFlashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
        return redirect()->to('/patients');
    }
}