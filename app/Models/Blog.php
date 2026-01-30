<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Blog extends Model
{
    // Fungsi untuk mengambil konten markdown berdasarkan slug
    public static function getMarkdownContent($slug)
    {
        // Tentukan path file markdown berdasarkan slug
        $filePath = resource_path("blogs/{$slug}.md");

        // Cek apakah file markdown ada
        if (File::exists($filePath)) {
            return File::get($filePath);
        }

        // Jika file tidak ditemukan, kembalikan null atau error handling lain
        return null;
    }
}
