<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Kiểm tra nếu có file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Lưu hình ảnh vào thư mục public/images và lấy tên ảnh
            $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension(); // Tạo tên ngẫu nhiên cho ảnh
            $image->move(public_path('images'), $imageName); // Di chuyển ảnh vào thư mục images trong public

            // Trả về URL của ảnh vừa tải lên
            return response()->json([
                'message' => 'Image uploaded successfully',
                'image_url' => asset('images/' . $imageName), // Trả về URL của ảnh
            ]);
        }

        return response()->json(['error' => 'No image provided'], 400);
    }
}
