<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function meals(Request $request)
    {
        return Meal::on()->where("title", "LIKE", '%' . $request->keyword . '%')->get();
    }

    public function categories(Request $request): \Illuminate\Database\Eloquent\Collection|array
    {
        return Category::on()->where("title", "LIKE", '%' . $request->keyword . '%')->get();
    }

    public function users(Request $request): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::on()->where("name", "LIKE", '%' . $request->keyword . '%')->get();
    }


    public function Main(Request $request, string $type = null)
    {
        if(!$request->has("keyword")){
            $request->keyword = '';
        }
        if ($type == null) {
            $result = [];
            $result["meals"] = $this->meals($request);
            $result["categories"] = $this->categories($request);

            return response()->json($result);
        }
        $result = match ($type) {
            "meal" => $this->meals($request),
            "category" => $this->categories($request),
            "user" => $this->users($request),
            default => [],
        };
        return response()->json($result);
    }
}
