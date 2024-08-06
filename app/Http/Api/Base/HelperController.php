<?php

namespace App\Http\Api\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


use App\Http\Controllers\Controller;

class HelperController extends Controller
{
    public function check_unique(Request $request): JsonResponse
    {
        $exist = false;
        if ($request->has('table') && $request->has('column') && $request->has('value')) {
            $table = $request->input('table');
            $column = $request->input('column');
            $value = $request->input('value');
            $exist = unique_check($table, $column, $value);
        }
        return response()->json(['unique' => !$exist]);
    }

    public function select(Request $request)
    {
        $chuckSize = $request->has('chuck') ? (int)$request->input('chuck') : 100;
        $table = $request->input('table');
        $column_id = $request->input('column_id') ?? 'id';
        $column_label = $request->input('column_label') ?? 'name';

        $column_image = $request->input('column_image') ?? null;
        $filter = $request->input('filter') ?? '';
        $data = [];
        if (Schema::hasColumn("$table", "$column_id") && Schema::hasColumn("$table", "$column_label")) {
            DB::table($table)->orderBy($column_label)->where($column_label, 'like', $filter . '%')->chunk($chuckSize, function (Collection $items) use ($column_id, $column_label, $data, $column_image) {

                foreach ($items as $item) {
                    $predata = [
                        'value' => $item->$column_id,
                        'label' => $item->$column_label
                    ];
                    if ($column_image)
                        $predata['image'] = $item->$column_image;
                    $data[] = $predata;
                }
                return response()->json($data);
            });
        } else {
            return response()->json([]);

        }
    }
}
