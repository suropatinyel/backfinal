<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Alat;

class AlatController extends Controller
{
    public function index () {
		try {
        $alat = Cache::remember('alat', 60*60*24, function () {
            return Alat::getAlat();
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get alat data.',
            'data' => $alat
      ];

        return response()->json($response, 200)
        ->header('Cache-Control', 'public, max-age=300');
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
	
public function show (int $alat_id) {
    try {
        $cacheKey = 'alat_id'.$alat_id;
        $alat = Cache::remember($cacheKey, 60*60*24, function () use ($alat_id) {
            return Alat::getAlatById($alat_id);
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get alat data.',
            'data' => $alat
        ];

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
                'alat_kategori_id' => 'required|numeric',
                'alat_nama' => 'required|string|max:150',
                'alat_deskripsi' => 'required|string|max:250',
                'alat_hargaperhari' => 'required|numeric',
                'alat_stok' => 'required|numeric'
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

            $alat = Alat::createAlat($validator->validated());
            Cache::put('alat', Alat::getAlat(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully create product data',
                'data' => $alat,
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

	public function update (Request $request, int $alat_id) {
		try {
            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'required|numeric',
                'alat_nama' => 'required|string|max:150',
                'alat_deskripsi' => 'required|string|max:250',
                'alat_hargaperhari' => 'required|numeric',
                'alat_stok' => 'required|numeric'
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

            $alat = Alat::updateAlat($alat_id, $validator->validated());
            
            Cache::forget('alat_id' .$alat_id); 
            Cache::forget('alat');
            
            Cache::put('alat_id'.$alat_id,$alat, 60*60*24);
            Cache::put('alat',Alat::getAlat(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $alat
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
	
	public function destroy (int $alat_id) {
		try {
            $alat = Alat::deleteAlat($alat_id);
            $alat->delete();
            $response = array(
                'success' => true,
                'message' => 'Successfully delete alat data',
                'data' => $alat,
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
