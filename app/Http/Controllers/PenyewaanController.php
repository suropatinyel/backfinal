<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Penyewaan;

class PenyewaanController extends Controller
{
    public function index () {
		try {
            $penyewaan = Penyewaan::getPenyewaan();
            $response = array(
                'succes' => true,
                'message' => 'Data semua Penyewaan berhasil ditampilkan',
                'data' => $penyewaan
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
	
public function show (int $penyewaan_id) {
    try {
        $penyewaan = Penyewaan::getPenyewaanById($penyewaan_id);
        $response = array(
            'succes' => true,
            'message' => 'Data Penyewaan berdasarkan ID berhasil ditampilkan',
            'data' => $penyewaan
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
                'penyewaan_pelanggan_id' => 'required|numeric',
                'penyewaan_tglsewa' => 'required|date',
                'penyewaan_tglkembali' => 'required|date',
                'penyewaan_sttspembayaran' => 'required|in:LUNAS,BELUM DIBAYAR,DP',
                'penyewaan_sttskembali' => 'required|in:SUDAH KEMBALI,BELUM KEMBALI',
                'penyewaan_totalharga' => 'required|numeric',
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

            $penyewaan = Penyewaan::createPenyewaan($validator->validated());
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menambahkan data Penyewaan',
                'data' => $penyewaan
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

	public function update (Request $request, int $penyewaan_id) {
		try {
            $validator = Validator::make($request->all(), [
                'penyewaan_pelanggan_id' => 'required|numeric',
                'penyewaan_tglsewa' => 'required|date',
                'penyewaan_tglkembali' => 'required|date',
                'penyewaan_sttspembayaran' => 'required|in:LUNAS,BELUM DIBAYAR,DP',
                'penyewaan_sttskembali' => 'required|in:SUDAH KEMBALI,BELUM KEMBALI',
                'penyewaan_totalharga' => 'required|numeric',
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

            $penyewaan = Penyewaan::updatePenyewaan($penyewaan_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $penyewaan
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
	
	public function destroy (int $penyewaan_id) {
		try {
            $penyewaan = Penyewaan::deletePenyewaan($penyewaan_id);
            $penyewaan->delete();
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menhapus data Penyewaan',
                'data' => $penyewaan,
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
