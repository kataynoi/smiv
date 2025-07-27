<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel; // อย่าลืม use Model ของเรา

class ProductController extends BaseController
{
    public function index()
    {
        // สร้าง instance ของ ProductModel
        $productModel = new ProductModel();

        // เตรียมข้อมูลสำหรับส่งไปที่ View
        // findAll() จะดึงข้อมูลทั้งหมดจากตาราง products
        $data = [
            'products' => $productModel->findAll()
        ];

        // ส่งข้อมูลไปแสดงผลที่ View ชื่อ 'product_list_view.php'
        return view('product_list_view', $data);
    }
}
