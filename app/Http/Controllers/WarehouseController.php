<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Services\WarehouseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index()
    {
        // ambil data dari kolom berikut
        $fields = ['id','name','photo'];
        // delegasi permintaan ke warehouseService
        $warehouses = $this->warehouseService->getAll($fields ?: ['*']);
        // ubah menjadi respone json
        return response()->json(WarehouseResource::collection($warehouses));
    }

    public function show(int $id)
    {
        try {
            $fields = ['id','name','photo','phone']; //hardcoded fields
            // detail per id warehouse
            $warehouse = $this->warehouseService->getById($id, $fields);
            // ubah menjadi respone json
            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'warehouse not found'], 404);
        }
    }

    public function store(WarehouseRequest $request)
    {
        // input data baru berdasarkan request yang sudah divalidasi
        $warehouse = $this->warehouseService->create($request->validated());

        return response()->json(new WarehouseResource($warehouse), 201);
    }

    public function update(WarehouseRequest $request, int $id)
    {
        try {
            // update data sesuai id berdasarkan request yang sudah divalidasi
            $warehouse = $this->warehouseService->update($id, $request->validated());

            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'warehouse not found'], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            // hapus data sesuai id
            $this->warehouseService->delete($id);

            return response()->json(['message' => 'warehouse deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'warehouse not found'], 404);
        }
    }


}
