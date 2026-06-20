<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new pointsModel();
    }

    public function geojson_points()
    {
        return response()->json(
            $this->points->geojson_points(),
            200,
            [],
            JSON_NUMERIC_CHECK
        );
    }

    public function geojson_point($id)
    {
        $point = $this->points->geojson_point($id);

        if (!$point) {
            return response()->json(['error' => 'Point not found'], 404);
        }

        return response()->json($point, 200, [], JSON_PRETTY_PRINT);
    }
}
