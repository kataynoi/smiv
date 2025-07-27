<?= $this->extend('layout/default') ?>
<?= $this->section('content') ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการข้อมูลผู้ป่วย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>

    <div class=" mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>จัดการข้อมูลผู้ป่วย</h3>
            <?php
            $userRoles = session()->get('roles') ?? [];
            // ถ้าต้องการให้สิทธิ์ 'Edit' (ID 5) เพิ่มได้ด้วย ให้ใช้ if(in_array(4, $userRoles) || in_array(5, $userRoles))
            if (in_array(5, $userRoles)):
            ?>
                <button class="btn btn-primary" id="addPatientBtn">เพิ่มผู้ป่วยใหม่</button>
            <?php endif; ?>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="patientTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อ-สกุล</th>
                            <th>ระดับความเสี่ยง</th>
                            <th>เบอร์โทร</th>
                            <th>วันที่คัดกรอง</th>

                            <? if (in_array(5, $userRoles)): ?>
                                <th>Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Patient Modal (ฟอร์มหลัก) -->
    <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">ฟอร์มข้อมูลผู้ป่วย</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="patientForm">
                        <input type="hidden" name="id" id="patient_id">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="firstname" class="form-label">ชื่อ</label><input type="text" class="form-control" id="firstname" name="firstname" required></div>
                            <div class="col-md-4 mb-3"><label for="lastname" class="form-label">สกุล</label><input type="text" class="form-control" id="lastname" name="lastname" required></div>
                            <div class="col-md-4 mb-3"><label for="id_card" class="form-label">เลขบัตรประชาชน</label><input type="text" class="form-control" id="id_card" name="id_card" maxlength="13" required></div>

                            <div class="col-md-4 mb-3"><label for="phone_number" class="form-label">เบอร์โทร</label><input type="text" class="form-control" id="phone_number" name="phone_number"></div>
                            <div class="col-md-4 mb-3"><label for="birthdate" class="form-label">วันเกิด</label><input type="date" class="form-control" id="birthdate" name="birthdate"></div>
                            <div class="col-md-4 mb-3"><label for="screening_date" class="form-label">วันที่คัดกรอง</label><input type="date" class="form-control" id="screening_date" name="screening_date" required></div>

                            <div class="col-md-6 mb-3"><label for="entry_type_id" class="form-label">ประเภทการนำเข้า</label><select class="form-select" id="entry_type_id" name="entry_type_id" required>
                                    <option value="">-- เลือกประเภท --</option><?php foreach ($entry_types as $type): ?><option value="<?= $type['id'] ?>"><?= $type['name'] ?></option><?php endforeach; ?>
                                </select></div>
                            <div class="col-md-6 mb-3"><label for="risk_level_id" class="form-label">ระดับความเสี่ยง (เริ่มต้น)</label><select class="form-select" id="risk_level_id" name="risk_level_id" required><?php foreach ($risk_levels as $level): ?><option value="<?= $level['id'] ?>"><?= $level['name'] ?></option><?php endforeach; ?></select></div>

                            <hr>
                            <h5 class="mt-2">ที่อยู่</h5>
                            <div class="col-md-3 mb-3"><label for="changwatcode" class="form-label">จังหวัด</label><select class="form-select" id="changwatcode" name="changwatcode" required>
                                    <option value="">-- เลือกจังหวัด --</option><?php foreach ($changwats as $changwat): ?><option value="<?= $changwat['changwatcode'] ?>"><?= $changwat['changwatname'] ?></option><?php endforeach; ?>
                                </select></div>
                            <div class="col-md-3 mb-3"><label for="ampurcodefull" class="form-label">อำเภอ</label><select class="form-select" id="ampurcodefull" name="ampurcodefull" required disabled>
                                    <option value="">-- เลือกอำเภอ --</option>
                                </select></div>
                            <div class="col-md-3 mb-3"><label for="tamboncodefull" class="form-label">ตำบล</label><select class="form-select" id="tamboncodefull" name="tamboncodefull" required disabled>
                                    <option value="">-- เลือกตำบล --</option>
                                </select></div>
                            <div class="col-md-3 mb-3"><label for="villagecode" class="form-label">หมู่บ้าน</label><select class="form-select" id="villagecode" name="villagecode" required disabled>
                                    <option value="">-- เลือกหมู่บ้าน --</option>
                                </select></div>
                            <div class="col-12 mb-3"><label for="address_text" class="form-label">ที่อยู่เพิ่มเติม (บ้านเลขที่)</label><textarea class="form-control" id="address_text" name="address_text" rows="2"></textarea></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" form="patientForm">บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Level Modal (ฟอร์มเปลี่ยนระดับ) -->
    <div class="modal fade" id="riskLevelModal" tabindex="-1" aria-labelledby="riskLevelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="riskLevelModalLabel">เปลี่ยนระดับความเสี่ยง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="riskLevelForm">
                        <input type="hidden" name="patient_id" id="risk_patient_id">
                        <div class="mb-3">
                            <label for="new_risk_level_id" class="form-label">เลือกระดับความเสี่ยงใหม่</label>
                            <select class="form-select" id="new_risk_level_id" name="risk_level_id" required>
                                <?php foreach ($risk_levels as $level): ?>
                                    <option value="<?= $level['id'] ?>"><?= $level['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success" form="riskLevelForm">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // สร้างตัวแปร JavaScript ชื่อ USER_ROLES ให้มีค่าเท่ากับสิทธิ์ของผู้ใช้
        const USER_ROLES = <?= json_encode($userRoles); ?>;
        console.log(USER_ROLES);
    </script>
    <script>
        $(document).ready(function() {
            // --- Initializations ---
            var table = $('#patientTable').DataTable({
                ajax: "<?= site_url('patients/fetch') ?>",
                columns: [{
                        data: 'id'
                    },
                    {
                        render: (data, type, row) => `${row.firstname} ${row.lastname}`
                    },
                    {
                        data: null,
                        render: (data, type, row) => `<span class="badge" style="background-color:${row.color_hex}; color: #fff;">${row.risk_level_name}</span>`
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'screening_date'
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            // สร้างตัวแปรเปล่าสำหรับเก็บปุ่ม
                            let buttons = '';
                            // ตรวจสอบ: ถ้าผู้ใช้มี Role ID 5 (Edit) ใน Array
                            if (USER_ROLES.includes("5")) {
                                // เพิ่มปุ่ม "แก้ไข" และ "ลบ" เข้าไปในตัวแปร
                                buttons += `<button class="btn btn-warning btn-sm editBtn" data-id="${row.id}" title="แก้ไขข้อมูล"><i class="fas fa-edit"></i> แก้ไข</button> `;
                                buttons += `<button class="btn btn-danger btn-sm deleteBtn" data-id="${row.id}" title="ลบข้อมูล"><i class="fas fa-trash"></i> ลบ</button> `;
                                buttons += `<button class="btn btn-info btn-sm changeLevelBtn" data-id="${row.id}" data-level-id="${row.risk_level_id}" title="เปลี่ยนระดับความเสี่ยง"><i class="fas fa-level-up-alt"></i> เปลี่ยนระดับ</button>`;

                            }
                            // เพิ่มปุ่ม "เปลี่ยนระดับ" (ปุ่มนี้จะแสดงสำหรับทุกคนในตัวอย่างนี้)
                           
                            // ส่งค่า HTML ของปุ่มทั้งหมดที่สร้างเสร็จแล้วกลับไปแสดงผล
                            return buttons;
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/th.json'
                }
            });
            const patientModal = new bootstrap.Modal(document.getElementById('patientModal'));
            const riskLevelModal = new bootstrap.Modal(document.getElementById('riskLevelModal'));

            // --- Event Handlers ---
            $('#addPatientBtn').click(() => {
                $('#patientForm')[0].reset();
                $('#patient_id').val('');
                $('#patientModalLabel').text('เพิ่มข้อมูลผู้ป่วยใหม่');
                $('#ampurcodefull, #tamboncodefull, #villagecode').prop('disabled', true).html('<option value="">-- เลือก --</option>');
                patientModal.show();
            });

            $('#patientForm').submit(function(e) {
                e.preventDefault();
                let url = $('#patient_id').val() ? "<?= site_url('patients/update') ?>" : "<?= site_url('patients/store') ?>";
                $.post(url, $(this).serialize(), (response) => {
                    patientModal.hide();
                    Swal.fire({
                        icon: response.status,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload();
                }, 'json');
            });

            $('#patientTable tbody').on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $.get(`<?= site_url('patients/fetch-one/') ?>${$(this).data('id')}`, (data) => {
                    $('#patientModalLabel').text('แก้ไขข้อมูลผู้ป่วย');
                    $('#patient_id').val(data.id);
                    Object.keys(data).forEach(key => $(`#${key}`).val(data[key]));
                    $('#changwatcode').trigger('change', [data.ampurcodefull, data.tamboncodefull, data.villagecode]);
                    patientModal.show();
                }, 'json');
            });

            $('#patientTable tbody').on('click', '.deleteBtn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "ข้อมูลจะถูกลบอย่างถาวร!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post(`<?= site_url('patients/delete/') ?>${id}`, (response) => {
                            Swal.fire('ลบแล้ว!', response.message, 'success');
                            table.ajax.reload();
                        }, 'json');
                    }
                });
            });

            // --- Risk Level Change Logic ---
            $('#patientTable tbody').on('click', '.changeLevelBtn', function() {
                let patientId = $(this).data('id');
                let currentLevelId = $(this).data('level-id');
                $('#risk_patient_id').val(patientId);
                $('#new_risk_level_id').val(currentLevelId);
                riskLevelModal.show();
            });

            $('#riskLevelForm').submit(function(e) {
                e.preventDefault();
                $.post("<?= site_url('patients/update-risk-level') ?>", $(this).serialize(), (response) => {
                    riskLevelModal.hide();
                    Swal.fire({
                        icon: response.status,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload();
                }, 'json');
            });

            // --- Dependent Dropdowns Logic (4 Levels) ---
            $('#changwatcode').change(function(e, ampur_to_select, tambon_to_select, village_to_select) {
                let provinceCode = $(this).val();
                let amphurSelect = $('#ampurcodefull');
                amphurSelect.prop('disabled', true).html('<option>-- โหลด --</option>');
                $('#tamboncodefull, #villagecode').prop('disabled', true).html('<option>-- เลือก --</option>');
                if (provinceCode) {
                    $.post("<?= site_url('patients/get-amphures') ?>", {
                        province_code: provinceCode
                    }, (data) => {
                        amphurSelect.prop('disabled', false).html('<option value="">-- เลือกอำเภอ --</option>');
                        data.forEach(val => amphurSelect.append(`<option value="${val.ampurcodefull}">${val.ampurname}</option>`));
                        if (ampur_to_select) amphurSelect.val(ampur_to_select).trigger('change', [tambon_to_select, village_to_select]);
                    }, 'json');
                }
            });

            $('#ampurcodefull').change(function(e, tambon_to_select, village_to_select) {
                let amphureCode = $(this).val();
                let tambonSelect = $('#tamboncodefull');
                tambonSelect.prop('disabled', true).html('<option>-- โหลด --</option>');
                $('#villagecode').prop('disabled', true).html('<option>-- เลือก --</option>');
                if (amphureCode) {
                    $.post("<?= site_url('patients/get-tambons') ?>", {
                        amphure_code: amphureCode
                    }, (data) => {
                        tambonSelect.prop('disabled', false).html('<option value="">-- เลือกตำบล --</option>');
                        data.forEach(val => tambonSelect.append(`<option value="${val.tamboncodefull}">${val.tambonname}</option>`));
                        if (tambon_to_select) tambonSelect.val(tambon_to_select).trigger('change', [village_to_select]);
                    }, 'json');
                }
            });

            $('#tamboncodefull').change(function(e, village_to_select) {
                let tambonCode = $(this).val();
                let villageSelect = $('#villagecode');
                villageSelect.prop('disabled', true).html('<option>-- โหลด --</option>');
                if (tambonCode) {
                    $.post("<?= site_url('patients/get-villages') ?>", {
                        tambon_code: tambonCode
                    }, (data) => {
                        villageSelect.prop('disabled', false).html('<option value="">-- เลือกหมู่บ้าน --</option>');
                        data.forEach(val => villageSelect.append(`<option value="${val.villagecodefull}">${val.villagename}</option>`));
                        if (village_to_select) villageSelect.val(village_to_select);
                    }, 'json');
                }
            });
        });
    </script>
    <!-- Font Awesome for icons on buttons -->
</body>

</html>

<?= $this->endSection() ?>