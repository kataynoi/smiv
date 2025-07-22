<?php
namespace App\Models;

use CodeIgniter\Model;
// --------------------------------------------------------------------
// File: app/Models/PatientModel.php (สร้างไฟล์ใหม่)
// --------------------------------------------------------------------
// Model สำหรับจัดการข้อมูลตาราง `patients`
// --------------------------------------------------------------------
class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_card', 'screening_date', 'firstname', 'lastname', 'birthdate', 
        'address_text', 'phone_number', 'main_diagnosis_icd10', 
        'risk_level_id', 'entry_type_id', 'registrar_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * ดึงข้อมูลผู้ป่วยทั้งหมดพร้อม JOIN ตารางที่เกี่ยวข้อง
     */
    public function getPatientsWithDetails()
    {
        return $this->select('patients.*, risk_levels.name as risk_level_name, risk_levels.color_hex, entry_types.name as entry_type_name')
            ->join('risk_levels', 'risk_levels.id = patients.risk_level_id', 'left')
            ->join('entry_types', 'entry_types.id = patients.entry_type_id', 'left')
            ->findAll();
    }

    /**
     * ดึงข้อมูลผู้ป่วย 1 คนพร้อม JOIN ตารางที่เกี่ยวข้อง
     */
    public function getPatientDetails($id)
    {
        return $this->select('patients.*, risk_levels.name as risk_level_name, entry_types.name as entry_type_name, users.fullname as registrar_name')
            ->join('risk_levels', 'risk_levels.id = patients.risk_level_id', 'left')
            ->join('entry_types', 'entry_types.id = patients.entry_type_id', 'left')
            ->join('users', 'users.id = patients.registrar_id', 'left')
            ->where('patients.id', $id)
            ->first();
    }
}