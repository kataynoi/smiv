<?php
// --------------------------------------------------------------------
// File: app/Views/patient/edit.php (อัปเดตไฟล์เดิม)
// --------------------------------------------------------------------
?>
<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>แก้ไขข้อมูลผู้ป่วย<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">แก้ไขข้อมูลผู้ป่วย</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">แดชบอร์ด</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('patients') ?>">รายชื่อผู้ป่วย</a></li>
        <li class="breadcrumb-item active">แก้ไขข้อมูล</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit me-1"></i>แก้ไขข้อมูล: <?= esc($patient['firstname']) ?> <?= esc($patient['lastname']) ?></div>
        <div class="card-body">
            
            <?php if(session()->get('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                    <?php foreach (session()->get('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="<?= site_url('patients/update/' . $patient['id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <h5 class="mt-4">ส่วนที่ 1: ข้อมูลทั่วไป</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4"><label for="id_card" class="form-label">เลขบัตรประชาชน <span class="text-danger">*</span></label><input type="text" class="form-control" id="id_card" name="id_card" value="<?= old('id_card', $patient['id_card']) ?>" required></div>
                    <div class="col-md-4"><label for="firstname" class="form-label">ชื่อ <span class="text-danger">*</span></label><input type="text" class="form-control" id="firstname" name="firstname" value="<?= old('firstname', $patient['firstname']) ?>" required></div>
                    <div class="col-md-4"><label for="lastname" class="form-label">นามสกุล <span class="text-danger">*</span></label><input type="text" class="form-control" id="lastname" name="lastname" value="<?= old('lastname', $patient['lastname']) ?>" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><label for="birthdate" class="form-label">วันเกิด</label><input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= old('birthdate', $patient['birthdate']) ?>"></div>
                    <div class="col-md-4"><label for="phone_number" class="form-label">เบอร์โทรศัพท์</label><input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= old('phone_number', $patient['phone_number']) ?>"></div>
                </div>

                <h5 class="mt-4">ส่วนที่ 2: ที่อยู่ปัจจุบัน</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4"><label for="changwatcode" class="form-label">จังหวัด <span class="text-danger">*</span></label><select class="form-select" id="changwatcode" name="changwatcode" required><option value="">-- เลือกจังหวัด --</option><?php foreach($provinces as $province): ?><option value="<?= $province['changwatcode'] ?>" <?= ($patient['changwatcode'] == $province['changwatcode']) ? 'selected' : '' ?>><?= $province['changwatname'] ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-4"><label for="ampurcodefull" class="form-label">อำเภอ</label><select class="form-select" id="ampurcodefull" name="ampurcodefull"><option value="">-- เลือกอำเภอ --</option><?php foreach($amphurs as $amphur): ?><option value="<?= $amphur['ampurcodefull'] ?>" <?= ($patient['ampurcodefull'] == $amphur['ampurcodefull']) ? 'selected' : '' ?>><?= $amphur['ampurname'] ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-4"><label for="tamboncodefull" class="form-label">ตำบล</label><select class="form-select" id="tamboncodefull" name="tamboncodefull"><option value="">-- เลือกตำบล --</option><?php foreach($tambons as $tambon): ?><option value="<?= $tambon['tamboncodefull'] ?>" <?= ($patient['tamboncodefull'] == $tambon['tamboncodefull']) ? 'selected' : '' ?>><?= $tambon['tambonname'] ?></option><?php endforeach; ?></select></div>
                </div>
                <div class="mb-3"><label for="address_text" class="form-label">บ้านเลขที่/หมู่บ้าน</label><textarea class="form-control" id="address_text" name="address_text" rows="2"><?= old('address_text', $patient['address_text']) ?></textarea></div>

                <h5 class="mt-4">ส่วนที่ 3: ข้อมูลการนำเข้าสู่ระบบ</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4"><label for="screening_date" class="form-label">วันที่คัดกรอง <span class="text-danger">*</span></label><input type="date" class="form-control" id="screening_date" name="screening_date" value="<?= old('screening_date', $patient['screening_date']) ?>" required></div>
                    <div class="col-md-4"><label for="entry_type_id" class="form-label">ประเภทการนำเข้าบำบัด <span class="text-danger">*</span></label><select class="form-select" id="entry_type_id" name="entry_type_id" required><option value="">-- กรุณาเลือก --</option><?php foreach($entry_types as $type): ?><option value="<?= $type['id'] ?>" <?= old('entry_type_id', $patient['entry_type_id']) == $type['id'] ? 'selected' : '' ?>><?= $type['name'] ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-4"><label for="risk_level_id" class="form-label">ระดับความเสี่ยง</label><select class="form-select" id="risk_level_id" name="risk_level_id" required><?php foreach($risk_levels as $level): ?><option value="<?= $level['id'] ?>" <?= old('risk_level_id', $patient['risk_level_id']) == $level['id'] ? 'selected' : '' ?>><?= $level['name'] ?></option><?php endforeach; ?></select></div>
                </div>
                
                <div class="mt-4"><button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button><a href="<?= site_url('patients') ?>" class="btn btn-secondary">ยกเลิก</a></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    // Logic for dependent dropdowns (same as create.php)
    $('#changwatcode').change(function() {
        var provinceId = $(this).val();
        if (provinceId) {
            $.ajax({
                url: "<?= site_url('patients/get-amphurs') ?>",
                method: "POST",
                data: { province_id: provinceId, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: "json",
                success: function(data) {
                    $('#ampurcodefull').empty().append('<option value="">-- เลือกอำเภอ --</option>');
                    $('#tamboncodefull').empty().append('<option value="">-- เลือกตำบล --</option>');
                    $.each(data, function(key, value) {
                        $('#ampurcodefull').append('<option value="' + value.ampurcodefull + '">' + value.ampurname + '</option>');
                    });
                }
            });
        } else {
            $('#ampurcodefull').empty().append('<option value="">-- เลือกอำเภอ --</option>');
            $('#tamboncodefull').empty().append('<option value="">-- เลือกตำบล --</option>');
        }
    });
    $('#ampurcodefull').change(function() {
        var amphurId = $(this).val();
        if (amphurId) {
            $.ajax({
                url: "<?= site_url('patients/get-tambons') ?>",
                method: "POST",
                data: { amphur_id: amphurId, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: "json",
                success: function(data) {
                    $('#tamboncodefull').empty().append('<option value="">-- เลือกตำบล --</option>');
                    $.each(data, function(key, value) {
                        $('#tamboncodefull').append('<option value="' + value.tamboncodefull + '">' + value.tambonname + '</option>');
                    });
                }
            });
        } else {
            $('#tamboncodefull').empty().append('<option value="">-- เลือกตำบล --</option>');
        }
    });
});
</script>
<?= $this->endSection() ?>