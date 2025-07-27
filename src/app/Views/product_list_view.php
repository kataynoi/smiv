

<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    
</head>
<body>

    <div class="container mt-6">
        <h1 class="mb-4 text-primary">รายการสินค้าในสต็อก</h1>
        
        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา (บาท)</th>
                    <th>จำนวนในสต็อก</th>
                    <th>วันที่เพิ่ม</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products) && is_array($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <!-- ใช้ esc() เพื่อป้องกัน XSS attacks -->
                            <td><?= esc($product['id']) ?></td>
                            <td><?= esc($product['name']) ?></td>
                            <td><?= number_format(esc($product['price']), 2) ?></td>
                            <td><?= esc($product['stock']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime(esc($product['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery (จำเป็นสำหรับ DataTables) -->
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables Core JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <!-- DataTables Bootstrap 5 JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

    <script>
        // เมื่อหน้าเว็บโหลดเสร็จสมบูรณ์
        $(document).ready(function() {
            // สั่งให้ตารางที่มี id="productTable" ทำงานเป็น DataTable
            $('#productTable').DataTable({
                // (ตัวเลือกเสริม) ตั้งค่าภาษาไทยสำหรับ DataTables
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/th.json'
                }
            });
        });
    </script>

</body>
</html>
<?= $this->endSection() ?>
