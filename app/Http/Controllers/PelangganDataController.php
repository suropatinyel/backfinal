<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PelangganData;

class PelangganDataController extends Controller
{
    public function index () {
		try {
            $pelangganData = PelangganData::getPelangganData();
            $response = array(
                'succes' => true,
                'message' => 'Data semua pelanggan berhasil ditampilkan',
                'data' => $pelangganData
            );

            return response()->json($response, 200);

    } catch (Exception $error) {
        $response = array(
            'success' => false,
            'message' => 'Sorry, there error in internal server',
            'data' => null,
            'errors' => $error->getMessage()
        );

        return response()->json($response, 500);
    }
}
	
public function show (int $pelangganData_id) {
    try {
        $pelangganData = PelangganData::getPelangganDataById($pelangganData_id);
        $response = array(
            'succes' => true,
            'message' => 'Data pelanggan berdasarkan ID berhasil ditampilkan',
            'data' => $pelangganData
        );

        return response()->json($response, 200);

    } catch (Exception $error) {
        $response = [
            'success' => false,
            'message' => 'Sorry, there error in internal server',
            'data' => null,
            'errors' => $error->getMessage()
        ];

        return response()->json($response, 500);
    }
}


    public function store (Request $request) {
		try {
            $validator = Validator::make($request->all(), [
                'pelanggan_data_pelanggan_id' => 'required|numeric',
                'pelanggan_data_jenis' => 'required|in:KTP,SIM',
                'pelanggan_data_file' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            if ($validator->fails()) {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to create data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            if ($request->hasFile('pelanggan_data_file')) {
                $file = $request->file('pelanggan_data_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/pelanggan', $fileName, 'public'); // Simpan di storage/app/public/uploads/pelanggan
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File gambar tidak ditemukan.',
                ], 400);
            }
    
            // Simpan data ke database
            $pelangganData = PelangganData::create([
                'pelanggan_data_pelanggan_id' => $request->pelanggan_data_pelanggan_id,
                'pelanggan_data_jenis' => $request->pelanggan_data_jenis,
                'pelanggan_data_file' => $filePath // Simpan path file ke database
            ]);

            $pelangganData = PelangganData::find($pelangganData->id);

            // $pelangganData = PelangganData::createPelangganData($validator->validated());
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menambahkan data pelanggan',
                'data' => $pelangganData
            );

            return response()->json($response, 201);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => 'Sorry, there error in internal server',
                'data' => null,
                'errors' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
	}

	public function updatePut (Request $request, int $pelangganData_id) {
		try {
            $validator = Validator::make($request->all(), [
                'pelanggan_data_pelanggan_id' => 'required|numeric',
                'pelanggan_data_jenis' => 'required|in:KTP,SIM',
                'pelanggan_data_file' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            if ($validator->fails()) {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to create data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            $pelangganData = PelangganData::updatePelangganData($pelangganData_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $pelangganData
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => 'Sorry, there error in internal server',
                'data' => null,
                'errors' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
	}

    
    public function updatePatch (Request $request, int $pelangganData_id) {
		try {

            // Cari resource berdasarkan ID
        $pelangganData = PelangganData::find($pelangganData_id);
        if (!$pelangganData) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan Data not found',
                'data' => null
            ], 404);
        }

        // Ambil hanya atribut yang dikirimkan
        $data = $request->only([
            'pelanggan_data_pelanggan_id',
            'pelanggan_data_jenis',
            'pelanggan_data_file'
    ]);

            $validator = Validator::make($request->all(), [
                'pelanggan_data_pelanggan_id' => 'sometimes|numeric',
                'pelanggan_data_jenis' => 'sometimes|in:KTP,SIM',
                'pelanggan_data_file' => 'sometimes|image|mimes:jpg,png,jpeg'
            ]);

            if ($validator->fails()) {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to create data product. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            $pelangganData = PelangganData::updatePelangganData($pelangganData_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $pelangganData
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => 'Sorry, there error in internal server',
                'data' => null,
                'errors' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }

	
	public function destroy (int $pelangganData_id) {
		try {
            $pelangganData = PelangganData::deletePelangganData($pelangganData_id);
            $pelangganData->delete();
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menhapus data pelanggan',
                'data' => $pelangganData,
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => 'Sorry, there error in internal server',
                'data' => null,
                'errors' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
	}
}
