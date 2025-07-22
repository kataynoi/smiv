
<?php
// --------------------------------------------------------------------
// File: app/Views/patient/index.php (สร้างไฟล์ใหม่)
// --------------------------------------------------------------------
// หน้ารายชื่อผู้ป่วยทั้งหมด
// --------------------------------------------------------------------
?>
<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
รายชื่อผู้ป่วยทั้งหมด
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">จัดการข้อมูลผู้ป่วย</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">แดชบอร์ด</a></li>
        <li class="breadcrumb-item active">รายชื่อผู้ป่วยทั้งหมด</li>
    </ol>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            รายชื่อผู้ป่วย
            <a href="<?= site_url('patients/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> เพิ่มผู้ป่วยใหม่
            </a>
        </div>
        <div class="card-body">
            <table id="patientsTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>เลขบัตรประชาชน</th>
                        <th>ชื่อ-สกุล</th>
                        <th>ระดับความเสี่ยง</th>
                        <th>ประเภทการนำเข้า</th>
                        <th>วันที่ลงทะเบียน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?= esc($patient['id_card']) ?></td>
                                <td><?= esc($patient['firstname']) ?> <?= esc($patient['lastname']) ?></td>
                                <td>
                                    <span class="badge" style="background-color: <?= esc($patient['color_hex'] ?? '#6c757d') ?>;">
                                        <?= esc($patient['risk_level_name']) ?>
                                    </span>
                                </td>
                                <td><?= esc($patient['entry_type_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($patient['created_at'])) ?></td>
                                <td>
                                    <a href="<?= site_url('patients/show/' . $patient['id']) ?>" class="btn btn-info btn-sm" title="ดูรายละเอียด"><i class="fas fa-eye"></i></a>
                                    <a href="<?= site_url('patients/edit/' . $patient['id']) ?>" class="btn btn-warning btn-sm" title="แก้ไข"><i class="fas fa-edit"></i></a>
                                    <a href="<?= site_url('patients/delete/' . $patient['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบข้อมูลผู้ป่วยรายนี้ใช่หรือไม่?')" title="ลบ"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#patientsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>