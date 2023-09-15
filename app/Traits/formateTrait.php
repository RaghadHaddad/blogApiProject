<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait formateTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function ApiFormate($data=null,$message=null,$status=null) 
    {
        $array = ['data' => $data,'message' => $message,'status' => $status];
    }

}