<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index () {
		try {
            $pelanggan = Pelanggan::getPelanggan();
            $response = array(
                'succes' => true,
                'message' => 'Data semua pelanggan berhasil ditampilkan',
                'data' => $pelanggan
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
	
public function show (int $pelanggan_id) {
    try {
        $pelanggan = Pelanggan::getPelangganById($pelanggan_id);
        $response = array(
            'succes' => true,
            'message' => 'Data pelanggan berdasarkan ID berhasil ditampilkan',
            'data' => $pelanggan
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
                'pelanggan_nama' => 'required|string|max:150',
                'pelanggan_alamat' => 'required|string|max:200',
                'pelanggan_notelp' => 'required|digits_between:10,15',
                'pelanggan_email' => 'required|string|max:100',
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

            $pelanggan = Pelanggan::createPelanggan($validator->validated());
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menambahkan data pelanggan',
                'data' => $pelanggan
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

	public function updatePut (Request $request, int $pelanggan_id) {
		try {
            $validator = Validator::make($request->all(), [
                'pelanggan_nama' => 'required|string|max:150',
                'pelanggan_alamat' => 'required|string|max:200',
                'pelanggan_notelp' => 'required|digits_between:10,15',
                'pelanggan_email' => 'required|string|max:100',
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

            $pelanggan = Pelanggan::updatePelanggan($pelanggan_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $pelanggan
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

    public function updatePatch (Request $request, int $pelanggan_id) {
		try {

            // Cari resource berdasarkan ID
        $pelanggan = Pelanggan::find($pelanggan_id);
        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan not found',
                'data' => null
            ], 404);
        }

        // Ambil hanya atribut yang dikirimkan
        $data = $request->only([
            'pelanggan_nama',
            'pelanggan_alamat',
            'pelanggan_notelp',
            'pelanggan_email'
]);

            $validator = Validator::make($request->all(), [
                'pelanggan_nama' => 'sometimes|string|max:150',
                'pelanggan_alamat' => 'sometimes|string|max:200',
                'pelanggan_notelp' => 'sometimes|digits_between:10,15',
                'pelanggan_email' => 'sometimes|string|max:100',
            ]);

            if ($validator->fails()) {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to create data pelanggan. Data not completed, please check your data.',
                    'data' => null,
                    'errors' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            $pelanggan = Pelanggan::updatePelanggan($pelanggan_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $pelanggan
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

	
	public function destroy (int $pelanggan_id) {
		try {
            $pelanggan = Pelanggan::deletePelanggan($pelanggan_id);
            $pelanggan->delete();
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menhapus data pelanggan',
                'data' => $pelanggan,
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
