
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .register-container {
            max-width: 700px;
        }
    </style>
</head>

<body>
    <div class="container register-container mt-5">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">สร้างบัญชีผู้ใช้งานใหม่</h2>
                <p class="text-center text-muted mb-4">กรุณากรอกข้อมูลให้ครบถ้วนเพื่อลงทะเบียน และรอการอนุมัติจากผู้ดูแลระบบอำเภอของท่าน</p>

                <!-- แสดงข้อความ Error (ถ้ามี) -->
                <?php if (session()->get('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->get('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('register') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?= old('fullname') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="position" name="position" value="<?= old('position') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pass_confirm" class="form-label">ยืนยันรหัสผ่าน</label>
                            <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" required>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted">กรุณาเลือกพื้นที่ที่ท่านสังกัด</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="changwatcode" class="form-label">จังหวัด</label>
                            <select class="form-select" id="changwatcode" name="changwatcode" required>
                                <option value="">-- เลือกจังหวัด --</option>
                                <?php foreach ($changwats as $changwat): ?>
                                    <option value="<?= $changwat['changwatcode'] ?>" <?= old('changwatcode') == $changwat['changwatcode'] ? 'selected' : '' ?>><?= $changwat['changwatname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ampurcodefull" class="form-label">อำเภอ</label>
                            <select class="form-select" id="ampurcodefull" name="ampurcodefull" required disabled>
                                <option value="">-- กรุณาเลือกจังหวัดก่อน --</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">ลงทะเบียน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#changwatcode').on('change', function() {
                let provinceCode = $(this).val();
                let amphurSelect = $('#ampurcodefull');

                amphurSelect.prop('disabled', true).html('<option value="">-- กำลังโหลด --</option>');

                if (provinceCode) {
                    $.ajax({
                        url: "<?= site_url('ajax/get-amphures') ?>", // ใช้ Route ใหม่
                        method: 'POST',
                        data: {
                            province_code: provinceCode
                        },
                        dataType: 'json',
                        success: function(data) {
                            amphurSelect.prop('disabled', false).html('<option value="">-- เลือกอำเภอ --</option>');
                            $.each(data, function(key, value) {
                                amphurSelect.append(`<option value="${value.ampurcodefull}">${value.ampurname}</option>`);
                            });
                        }
                    });
                } else {
                    amphurSelect.html('<option value="">-- กรุณาเลือกจังหวัดก่อน --</option>');
                }
            });
        });
    </script>
</body>

</html>