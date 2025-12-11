<?php

namespace App\Services;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WarehouseService
// Class ini sebagai manajer operasi pada service layer yang mengurus logika bisnis
{
    private WarehouseRepository $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    // Meminta seluruh data gudang
    public function getAll(array $fields)
    {
        // meneruskan kepada warehouseRepository
        return $this->warehouseRepository->getAll($fields);
    }

    // Mencari data tertentu dengan mengambil data id gudang 
    public function getById(int $id, array $fields)
    {
       // meneruskan kepada warehouseRepository, operator ?? apabila lupa menentukan kolom
        return $this->warehouseRepository->getById($id, $fields ?? ['*']);
    }

    // Input data warehouse
    public function create(array $data)
    {
        //memastikan apakah ada file foto baru yang diupload
        if(isset($data['photo']) && $data['photo'] instanceof UploadedFile){
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        // meneruskan kepada warehouseRepository
        return $this->warehouseRepository->create($data);
    }

    //update data warehouse
    public function update(int $id, array $data)
    {
        //mengambil daya dari warehouseRepository by id
        $fields = ['*'];
        $warehouse = $this->warehouseRepository->getById($id, $fields);

        //memastikan apakah ada jika ada file foto yg lama maka akan terhapus
        if(isset($data['photo']) && $data['photo'] instanceof UploadedFile){
            if(!empty($warehouse->photo)){
                $this->deletePhoto($warehouse->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        // meneruskan kepada warehouseRepository
        return $this->warehouseRepository->update($id, $data);
    }

    //hapus data warehouse
    public function delete(int $id)
    {
        //mengambil daya dari warehouseRepository by id
        $fields = ['*'];
        $warehouse = $this->warehouseRepository->getById($id, $fields);

        //memastikan apakah ada foto yg lama maka akan terhapus
        if(!empty($warehouse->photo)){
            $this->deletePhoto($warehouse->photo);
        }

        // meneruskan kepada warehouseRepository
        $this->warehouseRepository->delete($id);
    }

    // function khusus untuk upload foto sesuai dengan folder yang ditentukan
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('warehouses', 'public');
    }

    // function khusus untuk delete foto menyeseuaikan dengan base path
    private function deletePhoto(string $photoPath)
    {
        $relativePath = 'warehouses/' . basename($photoPath);
        if(Storage::disk('public')->exists($relativePath)){
            Storage::disk('public')->delete($relativePath);
        }
    }

    // function detail untuk menambahkan product sesuai warehouse
    public function attachProduct(int $warehouseId, int $productId, int $stock)
    {
        // mengambil warehouse id dari warehouseRepository
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);

        //update product tanpa menghapus produck lain dan mencatatan jumlah stock
        $warehouse->products()->syncWithoutDetaching([
            $productId => ['stock' => $stock]
        ]);
    }

    // function detail untuk mengapus hubungan produk tertentu dengan warehouse
    public function detachProduct(int $warehouseId, int $productId)
    {
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);

        $warehouse->products()->detach($productId);
    }

    // function edit stok 
    public function updateProductStock(int $warehouseId, int $productId, int $stock)
    {
        // ambil data id warehouse
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);

        // jalankan eloquent untuk update stock berdasarkan product id
        $warehouse->products()->updateExistingPivot($productId, ['stock' => $stock]);

        return $warehouse->products()->where('product_id', $productId)->first();
    }
}