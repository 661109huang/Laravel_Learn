<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Resources\TypeCollection;
use App\Http\Resources\TypeResource;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 考量到分類少直接全部輸出
        $types = Type::select('id', 'name', 'sort')->get();

        // return response([
        //     'data' => $types // 輸出使用data包住
        // ], Response::HTTP_OK);

        return new TypeCollection($types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            // 另外一種驗證的寫法，使用陣列傳入驗證關鍵字
            'name' => [
                'required',
                'max:50',
                // type 資料表中name欄位資料是唯一值
                Rule::unique('types', 'name')
            ],
            'sort' => 'nullable|integer',
        ]);
        // 如果沒有傳入sort欄位內容
        if (!isset($request->sort)) {
            // 找到目前資料表的排序欄位最大值
            $max = Type::max('sort');
            $request['sort'] = $max + 1; // 最大值加1寫入請求的資料中
        }
        $type = Type::create($request->all()); // 寫入

        // return response([
        //     'data' => $type
        // ], Response::HTTP_CREATED);
        return new TypeResource($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        // return response([
        //     'data' => $type
        // ], Response::HTTP_OK);
        return new TypeResource($type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request, [
            // 另外一種驗證的寫法，使用陣列傳入驗證關鍵字
            'name' => [
                'max:50',
                // type 資料表中name欄位資料是唯一值
                Rule::unique('types', 'name')->ignore($type->name, 'name')
            ],
            'sort' => 'nullable|integer',
        ]);

        $type->update($request->all());

        // return response([
        //     'data' => $type
        // ], Response::HTTP_OK);
        return new TypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
