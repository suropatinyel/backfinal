<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
// use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\PenyewaanDetail;
use App\Models\Alat;
use App\Models\Penyewaan;
use Carbon\Carbon;

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
                'penyewaan_detail_alat_id' => 'required|numeric|exists:alat,id',
                'penyewaan_detail_jumlah' => 'required|numeric|min:1',
                // 'penyewaan_detail_subharga' => 'required|numeric',
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
            
            
            $alat = Alat::find($request->penyewaan_detail_alat_id);
                
            if (!$alat) {
                $response = [
                    'success' => false,
                    'message' => 'Alat tidak ditemukan.',
                    'data' => null,
                    'errors' => null
                ];    
                return response()->json($response, 404);
            }    
            
            if ($alat->alat_stok < $request->penyewaan_detail_jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok alat tidak mencukupi.'
                ], 400);    
            }    
            
            $alat->alat_stok -= $request->penyewaan_detail_jumlah;
            $alat->save();
            
            $penyewaan = Penyewaan::find($request->penyewaan_detail_penyewaan_id);
            if (!$penyewaan) {
                $response = [
                    'success' => false,
                    'message' => 'Data penyewaan tidak ditemukan.',
                    'data' => null,
                    'errors' => null
                ];
                return response()->json($response, 404);
            }
            // Hitung total hari penyewaan
            $tanggalMulai = Carbon::parse($penyewaan->penyewaan_tglsewa); // Ambil tanggal mulai sewa
            $tanggalSelesai = Carbon::parse($penyewaan->penyewaan_tglkembali); // Ambil tanggal selesai sewa
        $totalHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1; // Hitung selisih hari (+1 untuk inklusif)

        // Hitung subtotal harga
        $hargaPerHari = $alat->alat_hargaperhari;
        $penyewaanDetailSubharga = round($hargaPerHari * $totalHari * $request->penyewaan_detail_jumlah);

        // Tambahkan penyewaan_detail_subharga ke data yang akan disimpan
        $data = $validator->validated();
        $data['penyewaan_detail_subharga'] = $penyewaanDetailSubharga;


            // Ambil data penyewaan untuk mendapatkan durasi sewa
        // Simpan data penyewaan detail
        $penyewaanDetail = PenyewaanDetail::createPenyewaanDetail($data);


            // $penyewaanDetail = PenyewaanDetail::createPenyewaanDetail($validator->validated());
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

	public function updatePut (Request $request, int $penyewaanDetail_id) {
		try {
            $validator = Validator::make($request->all(), [
                'penyewaan_detail_penyewaan_id' => 'required|numeric',
                'penyewaan_detail_alat_id' => 'required|numeric',
                'penyewaan_detail_jumlah' => 'required|numeric|exist:alat,id',
                // 'penyewaan_detail_subharga' => 'required|numeric',
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

    public function updatePatch (Request $request, int $penyewaanDetail_id) {
		try {

            // Cari resource berdasarkan ID
        $penyewaanDetail = PenyewaanDetail::find($penyewaanDetail_id);
        if (!$penyewaanDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Penyewaan Detail not found',
                'data' => null
            ], 404);
        }

        // Ambil hanya atribut yang dikirimkan
        $data = $request->only([
            'penyewaan_detail_penyewaan_id',
            'penyewaan_detail_alat_id',
            'penyewaan_detail_jumlah'
    ]);

            $validator = Validator::make($request->all(), [
                'penyewaan_detail_penyewaan_id' => 'sometimes|numeric',
                'penyewaan_detail_alat_id' => 'sometimes|numeric',
                'penyewaan_detail_jumlah' => 'sometimes|numeric|exist:alat,id',
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

            $penyewaanDetail = PenyewaanDetail::updatePenyewaanDetail($penyewaan_detail_id, $validator->validated());
                $response = array(
                'success' => true,
                'message' => 'Successfully update penyewaan detail data',
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
