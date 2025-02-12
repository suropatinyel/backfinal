<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index () {
		try {
        $kategori = Cache::remember('kategori', 60*60*24, function () {
            return Kategori::getKategori();
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get kategori data.',
            'data' => $kategori
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
	
public function show (int $kategori_id) {
    try {
        $cacheKey = 'kategori_id'.$kategori_id;
        $kategori = Cache::remember($cacheKey, 60*60*24, function () use ($kategori_id) {
            return Kategori::getKategoriById($kategori_id);
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get kategori data.',
            'data' => $kategori
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
                'kategori_nama' => 'required|string|max:100',
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

            $kategori = Kategori::createKategori($validator->validated());
            Cache::put('kategori', Kategori::getKategori(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully create kategori data',
                'data' => [
                    'id' => $kategori->id,
                'kategori_nama' => $kategori->kategori_nama,
                'created_at' => $kategori->created_at,
                'updated_at' => $kategori->updated_at,
                ]
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

	public function update (Request $request, int $kategori_id) {
		try {
            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|string|max:100',
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

            $kategori = Kategori::updateKategori($kategori_id, $validator->validated());
            
            Cache::forget('kategori_id' .$kategori_id); 
            Cache::forget('kategori');
            
            Cache::put('kategori_id'.$kategori_id,$kategori, 60*60*24);
            Cache::put('kategori',Kategori::getKategori(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $kategori
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
	
	public function destroy (int $kategori_id) {
		try {
            $kategori = Kategori::deleteKategori($kategori_id);
            $kategori->delete();
            $response = array(
                'success' => true,
                'message' => 'Successfully delete kategori data',
                'data' => $kategori,
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
