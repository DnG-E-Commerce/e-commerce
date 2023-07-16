<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];

    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class, 'send_to');
    }

    public function delete()
    {
        // Cek apakah ada relasi produk yang terhubung dengan kategori ini
        if ($this->invoice()->exists()) {
            throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
        }

        // Hapus data master jika tidak ada relasi
        return parent::delete();
    }
}
