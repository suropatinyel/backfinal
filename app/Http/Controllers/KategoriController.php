<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index() {
        try {
            // Ambil data kategori tanpa cache
            $kategori = Kategori::getKategori();

            $response = [
                'success' => true,
                'message' => 'Successfully get kategori data.',
                'data' => $kategori
            ];

            return response()->json($response, 200);
        } catch (\Exception $error) {
            $response = [
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'data' => null,
                'errors' => $error->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function show(int $kategori_id) {
        try {
            // Ambil data kategori berdasarkan ID tanpa cache
            $kategori = Kategori::getKategoriById($kategori_id);

            $response = [
                'success' => true,
                'message' => 'Successfully get kategori data.',
                'data' => $kategori
            ];

            return response()->json($response, 200);
        } catch (\Exception $error) {
            $response = [
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'data' => null,
                'errors' => $error->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function store(Request $request) {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                ], 400);
            }

            // Simpan data kategori
            $kategori = Kategori::createKategori($validator->validated());

            $response = [
                'success' => true,
                'message' => 'Successfully create kategori data',
                'data' => [
                    'id' => $kategori->id,
                    'kategori_nama' => $kategori->kategori_nama,
                    'created_at' => $kategori->created_at,
                    'updated_at' => $kategori->updated_at,
                ]
            ];

            return response()->json($response, 201);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function updatePut(Request $request, int $kategori_id) {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                ], 400);
            }

            // Update data kategori
            $kategori = Kategori::updateKategori($kategori_id, $validator->validated());

            $response = [
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $kategori
            ];

            return response()->json($response, 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function destroy(int $kategori_id) {
        try {
            // Hapus data kategori
            $kategori = Kategori::deleteKategori($kategori_id);
            if ($kategori) {
                $kategori->delete();

                $response = [
                    'success' => true,
                    'message' => 'Successfully delete kategori data',
                    'data' => $kategori,
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Kategori not found',
                    'data' => null,
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }
}