<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Penyewaan;
use App\Models\PenyewaanDetail;
use App\Models\Alat;

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

	public function updatePut (Request $request, int $penyewaan_id) {
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

            $penyewaan = Penyewaan::find($penyewaan_id);
        if (!$penyewaan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penyewaan tidak ditemukan.'
            ], 404);
        }

        // Jika status pengembalian diubah menjadi "SUDAH KEMBALI"
        if ($request->penyewaan_sttskembali === 'SUDAH KEMBALI') {
            // Ambil semua detail penyewaan yang terkait
            $penyewaanDetail = PenyewaanDetail::where('penyewaan_detail_penyewaan_id', $penyewaan_id)->get();

            foreach ($penyewaanDetail as $detail) {
                // Ambil data alat
                $alat = Alat::find($detail->penyewaan_detail_alat_id);
                if ($alat) {
                    // Tambahkan stok
                    $alat->alat_stok += $detail->penyewaan_detail_jumlah;
                    $alat->save();
                }
            }
        }

        // Update status pengembalian
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

	public function updatePatch (Request $request, int $penyewaan_id) {
		try {

            // Cari resource berdasarkan ID
        $penyewaan = Penyewaan::find($penyewaan_id);
        if (!$penyewaan) {
            return response()->json([
                'success' => false,
                'message' => 'Penyewaan not found',
                'data' => null
            ], 404);
        }

        // Ambil hanya atribut yang dikirimkan
        $data = $request->only([
            'penyewaan_pelanggan_id',
            'penyewaan_tglsewa',
            'penyewaan_tglkembali',
            'penyewaan_sttspembayaran',
            'penyewaan_sttskembali',
            'penyewaan_totalharga'
        ]);

            $validator = Validator::make($request->all(), [
                'penyewaan_pelanggan_id' => 'sometimes|numeric',
                'penyewaan_tglsewa' => 'sometimes|date',
                'penyewaan_tglkembali' => 'sometimes|date',
                'penyewaan_sttspembayaran' => 'sometimes|in:LUNAS,BELUM DIBAYAR,DP',
                'penyewaan_sttskembali' => 'sometimes|in:SUDAH KEMBALI,BELUM KEMBALI',
                'penyewaan_totalharga' => 'sometimes|numeric',
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


            $penyewaanDetail = PenyewaanDetail::find($penyewaan_id);
            if (!$penyewaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data penyewaan tidak ditemukan.'
                ], 404);
            }
    
            // Jika status pengembalian diubah menjadi "SUDAH KEMBALI"
            if ($request->penyewaan_sttskembali === 'SUDAH KEMBALI') {
                // Ambil semua detail penyewaan yang terkait
                $penyewaanDetail = PenyewaanDetail::where('penyewaan_detail_penyewaan_id', $penyewaan_id)->get();
    
                foreach ($penyewaanDetail as $detail) {
                    // Ambil data alat
                    $alat = Alat::find($detail->penyewaan_detail_alat_id);
                    if ($alat) {
                        // Tambahkan stok
                        $alat->alat_stok += $detail->penyewaan_detail_jumlah;
                        $alat->save();
                    }
                }
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
