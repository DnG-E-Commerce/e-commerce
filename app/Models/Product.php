<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function delete()
    {
        // Cek apakah ada relasi produk yang terhubung dengan kategori ini
        if ($this->order()->exists()) {
            throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
        }

        // Hapus data master jika tidak ada relasi
        return parent::delete();
    }
}
