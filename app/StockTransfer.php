<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $table = 'stock_transfer';
    protected $fillable = ['admin_user_id', 'filename', 'import_type', 'warehouse_id'];

    public function admin () {
        return $this->belongsTo(AdminUser::class , 'admin_user_id' , 'id');
    }

    public function warehouse() {
        return $this->belongsTo(Warehouses::class, 'warehouse_id', 'id');
    }

    public function filePath($filename) {
        return url('/admin/uploaded/stocks' . $filename);
    }

}
