<?php

namespace App\Http\Controllers\Admin;
use App\CategoryModel;
use App\ParentCatModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends WebController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $category_obj,$parent_cat_obj;
    public function __construct()
    {
        $this->category_obj = new CategoryModel();
        $this->parent_cat_obj =  new ParentCatModel();
    }

    public function index()
    {

        return view('admin.category.index', [
            'title' => 'Category',
            'breadcrumb' => breadcrumb([
                'Category' => route('admin.category.index'),
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category= $this->category_obj->orderBy('name')->where('status','0')->get();
        $mastercat = $this->parent_cat_obj;
        return view('admin.category.create', [
            'title' => "Add Parent Category",
            'category'=>$category,
            'mastercat'=>$mastercat,
            'breadcrumb' => breadcrumb([
                'Category' => route('admin.category.index')
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);
        $return_data = $request->all();  
        $categories = $this->category_obj->saveCategory($return_data);
        if (isset($categories) && !empty($categories)) {
            success_session('Parent Category created successfully');
        } else {
            error_session('Parent Catgeory not created');
        }
        return redirect()->route('admin.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("123");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd("123");
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function listing(Request $request)
    {
        
        $data = $this->category_obj::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $param = [
                    'id' => $row->id,
                    'url' => [
                        'status' => route('admin.category.status_update', $row->id),
                    ],
                    'checked' => ($row->status == 'active') ? 'checked' : ''
                ];
                return  $this->generate_switch($param);
            })
            ->addColumn('action', function ($row) {
                $param = [
                    'id' => $row->id,
                    'url' => [
                        'delete' => route('admin.category.destroy', $row->id),
                        'edit' => route('admin.category.edit', $row->id),
                        // 'view' => route('admin.news.show', $row->id),
                    ]
                ];
                return $this->generate_actions_buttons($param);
            })
            ->rawColumns(["status","action"])
            ->make(true);
    }
}
