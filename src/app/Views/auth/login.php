<?php
// FILE: app/Views/auth/login_view.php
// **************************************************
// นี่คือไฟล์ View สำหรับหน้า Login ที่อัปเดตแล้ว
// **************************************************
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>เข้าสู่ระบบ - SMIV CARE</title>
    <!-- ใช้ CDN สำหรับ Bootstrap 5 และ Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f0f2f5; /* ปรับสีพื้นหลังให้อ่อนลง */
        }
        .card {
            border: 0;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="text-center font-weight-light my-4">เข้าสู่ระบบ SMIV CARE</h3>
                                </div>
                                <div class="card-body p-4">

                                    <!-- แสดงข้อความแจ้งเตือนหลังสมัครสมาชิก (ถ้ามี) -->
                                    <?php if(session()->getFlashdata('message')):?>
                                        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
                                    <?php endif;?>
                                    
                                    <!-- แสดงข้อความแจ้งเตือน Error จากการ Login (ถ้ามี) -->
                                    <?php if(session()->getFlashdata('error')):?>
                                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                                    <?php endif;?>

                                    <!-- ฟอร์ม Login -->
                                    <form action="<?= site_url('login') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUsername" name="username" type="text" placeholder="ชื่อผู้ใช้" required />
                                            <label for="inputUsername">ชื่อผู้ใช้ (Username)</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="รหัสผ่าน" required />
                                            <label for="inputPassword">รหัสผ่าน (Password)</label>
                                        </div>
                                        <div class="d-grid mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <!-- เพิ่มลิงก์สำหรับสมัครสมาชิกที่นี่ -->
                                    <div class="small"><a href="<?= site_url('register') ?>">ยังไม่มีบัญชี? สมัครสมาชิกที่นี่</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
