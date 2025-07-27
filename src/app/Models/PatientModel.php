<?php
namespace App\Models;
use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_card', 'screening_date', 'firstname', 'lastname', 'birthdate',
        'changwatcode', 'ampurcodefull', 'tamboncodefull', 'villagecode',
        'house_id', 'lat', 'long', 'address_text', 'phone_number',
        'main_diagnosis_icd10', 'risk_level_id', 'entry_type_id', 'registrar_id'
    ];
    protected $useTimestamps = true;
}