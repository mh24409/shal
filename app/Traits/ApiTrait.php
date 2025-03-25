<?php
namespace App\Traits;
trait ApiTrait {
    public function apiResponse($message = null,$data = null,$errors = null,$code = 200)
    {
        $response = [
            'message'=>$message,
            'code'=>$code
        ];

        if($data != null){
            $response['data'] = $data;
        }

        if($errors != null){
            $response['errors'] = $errors;
        }

        return response()->json($response);

    }

}
