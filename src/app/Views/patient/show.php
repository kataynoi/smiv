<?php
// --------------------------------------------------------------------
// File: app/Views/patient/show.php (สร้างไฟล์ใหม่)
// --------------------------------------------------------------------
// หน้าแสดงรายละเอียดผู้ป่วย 1 คน
// --------------------------------------------------------------------
?>
<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
รายละเอียดผู้ป่วย
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">รายละเอียดผู้ป่วย</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">แดชบอร์ด</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('patients') ?>">รายชื่อผู้ป่วย</a></li>
        <li class="breadcrumb-item active">รายละเอียด</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>
            ข้อมูลของ: <?= esc($patient['firstname']) ?> <?= esc($patient['lastname']) ?>
            <a href="<?= site_url('patients/edit/' . $patient['id']) ?>" class="btn btn-warning btn-sm float-end">
                <i class="fas fa-edit"></i> แก้ไขข้อมูล
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>เลขบัตรประชาชน:</strong> <?= esc($patient['id_card']) ?></p>
                    <p><strong>ชื่อ-สกุล:</strong> <?= esc($patient['firstname']) ?> <?= esc($patient['lastname']) ?></p>
                    <p><strong>วันเกิด:</strong> <?= !empty($patient['birthdate']) ? date('d F Y', strtotime($patient['birthdate'])) : 'N/A' ?></p>
                    <p><strong>เบอร์โทรศัพท์:</strong> <?= esc($patient['phone_number'] ?? 'N/A') ?></p>
                    <p><strong>ที่อยู่:</strong> <?= esc($patient['address_text'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>วันที่คัดกรอง:</strong> <?= date('d F Y', strtotime($patient['screening_date'])) ?></p>
                    <p><strong>ประเภทการนำเข้า:</strong> <?= esc($patient['entry_type_name']) ?></p>
                    <p><strong>ระดับความเสี่ยงปัจจุบัน:</strong> <?= esc($patient['risk_level_name']) ?></p>
                    <p><strong>ผู้ลงทะเบียน:</strong> <?= esc($patient['registrar_name']) ?></p>
                    <p><strong>วันที่สร้างข้อมูล:</strong> <?= date('d F Y, H:i', strtotime($patient['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            ประวัติการติดตาม
        </div>
        <div class="card-body">
            <!-- ส่วนนี้สำหรับแสดงรายการติดตามในอนาคต -->
            <p>ยังไม่มีข้อมูลการติดตาม</p>
        </div>
    </div>

</div>
<?= $this->endSection() ?>