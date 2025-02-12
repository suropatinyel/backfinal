<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\PenyewaanDetail;

class PenyewaanDetailController extends Controller
{
    public function index () {
		try {
            $penyewaanDetail = PenyewaanDetail::getPenyewaanDetail();
            $response = array(
                'succes' => true,
                'message' => 'Data semua Penyewaan berhasil ditampilkan',
                'data' => $penyewaanDetail
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
	
public function show (int $penyewaanDetail_id) {
    try {
        $penyewaanDetail = PenyewaanDetail::getPenyewaanDetailById($penyewaanDetail_id);
        $response = array(
            'succes' => true,
            'message' => 'Data Penyewaan berdasarkan ID berhasil ditampilkan',
            'data' => $penyewaanDetail
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
                'penyewaan_detail_penyewaan_id' => 'required|numeric',
                'penyewaan_detail_alat_id' => 'required|numeric',
                'penyewaan_detail_jumlah' => 'required|numeric',
                'penyewaan_detail_subharga' => 'required|numeric',
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

            $penyewaanDetail = PenyewaanDetail::createPenyewaanDetail($validator->validated());
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menambahkan data Penyewaan',
                'data' => $penyewaanDetail
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

	public function update (Request $request, int $penyewaanDetail_id) {
		try {
            $validator = Validator::make($request->all(), [
                'penyewaan_detail_penyewaan_id' => 'required|numeric',
                'penyewaan_detail_alat_id' => 'required|numeric',
                'penyewaan_detail_jumlah' => 'required|numeric',
                'penyewaan_detail_subharga' => 'required|numeric',
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

            $penyewaanDetail = PenyewaanDetail::updatePenyewaanDetail($penyewaanDetail_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $penyewaanDetail
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
	
	public function destroy (int $penyewaanDetail_id) {
		try {
            $penyewaanDetail = PenyewaanDetail::deletePenyewaanDetail($penyewaanDetail_id);
            $penyewaanDetail->delete();
            $response = array(
                'success' => true,
                'message' => 'BERHASIL!! Menhapus data Penyewaan',
                'data' => $penyewaanDetail,
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
