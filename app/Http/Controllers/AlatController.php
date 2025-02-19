<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Alat;

class AlatController extends Controller
{
    public function index () {
        try {
            $alat = Alat::getAlat();

            $response = [
                'success' => true,
                'message' => 'Successfully get alat data.',
                'data' => $alat
            ];

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = [
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function show (int $alat_id) {
        try {
            $alat = Alat::getAlatById($alat_id);

            $response = [
                'success' => true,
                'message' => 'Successfully get alat data.',
                'data' => $alat
            ];

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = [
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function store (Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'required|numeric',
                'alat_nama' => 'required|string|max:150',
                'alat_deskripsi' => 'required|string|max:250',
                'alat_hargaperhari' => 'required|numeric',
                'alat_stok' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                ], 400);
            }

            $alat = Alat::createAlat($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully create product data',
                'data' => $alat,
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function updatePut (Request $request, int $alat_id) {
        try {
            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'required|numeric',
                'alat_nama' => 'required|string|max:150',
                'alat_deskripsi' => 'required|string|max:250',
                'alat_hargaperhari' => 'required|numeric',
                'alat_stok' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                ], 400);
            }

            $alat = Alat::updateAlat($alat_id, $validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $alat
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function updatePatch (Request $request, int $alat_id) {
        try {
            $alat = Alat::find($alat_id);
            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat not found',
                    'data' => null
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'sometimes|numeric',
                'alat_nama' => 'sometimes|string|max:150',
                'alat_deskripsi' => 'sometimes|string|max:250',
                'alat_hargaperhari' => 'sometimes|numeric',
                'alat_stok' => 'sometimes|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                ], 400);
            }

            $alat->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $alat
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }

    public function destroy (int $alat_id) {
        try {
            $alat = Alat::find($alat_id);
            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat not found',
                    'data' => null
                ], 404);
            }

            $alat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully delete alat data',
                'data' => $alat,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there is an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage()
            ], 500);
        }
    }
}
