<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function delete()
    {
        // Cek apakah ada relasi produk yang terhubung dengan kategori ini
        if ($this->products()->exists()) {
            throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
        }

        // Hapus data master jika tidak ada relasi
        return parent::delete();
    }
}
