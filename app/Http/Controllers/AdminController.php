<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\AdminModel;

class AdminController extends Controller
{
    public function index () {
		try {
        $admin = Cache::remember('admin', 60*60*24, function () {
            return AdminModel::getAdmin();
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get admin data.',
            'data' => $admin
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
	
public function show (int $admin_id) {
    try {
        $cacheKey = 'admin_id'.$admin_id;
        $admin = Cache::remember($cacheKey, 60*60*24, function () use ($admin_id) {
            return AdminModel::getAdminById($admin_id);
        });

        $response = [
            'success' => true,
            'message' => 'Successfully get admin data.',
            'data' => $admin
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
                'admin_username' => 'required|string|max:50',
                'admin_password' => 'required|max:250'
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

            $admin = AdminModel::createAdmin($validator->validated());
            Cache::put('admin', AdminModel::getAdmin(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully create product data',
                'data' => $admin,
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

	public function update (Request $request, int $admin_id) {
		try {
            $validator = Validator::make($request->all(), [
                'admin_username' => 'required|string|max:50',
                'admin_password' => 'required|string|max:250'
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

            $admin = AdminModel::updateAdmin($admin_id, $validator->validated());
            
            Cache::forget('admin_id' .$admin_id); 
            Cache::forget('admin');
            
            Cache::put('admin_id'.$admin_id,$admin, 60*60*24);
            Cache::put('admin',AdminModel::getAdmin(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $admin,
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
	
	public function destroy (int $admin_id) {
		try {
            $admin = AdminModel::deleteAdmin($admin_id);
            $admin->delete();
            $response = array(
                'success' => true,
                'message' => 'Successfully delete admin data',
                'data' => $admin,
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
