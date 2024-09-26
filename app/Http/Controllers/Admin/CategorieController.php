<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Deal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Yajra\DataTables\Facades\DataTables;

class CategorieController extends WebController
{
    /**
     * Display a listing of the resource.
     */

     public $category_obj;
     public $deal_obj;
     public function __construct()
     {
         $this->category_obj = new Category();
         $this->deal_obj = new Deal();
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
     */
    public function create()
    {
        return view('admin.category.create', [
            'title' => "Create Category",
            'breadcrumb' => breadcrumb([
                'Category' => route('admin.category.index')
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);
        $return_data = $request->all();  
        $return_data['sequence'] = !empty($request->sequence) ?  $request->sequence : 0;
        $categories = $this->category_obj->saveCategory($return_data);
        if (isset($categories) && !empty($categories)) {
            success_session('Category created successfully');
        } else {
            error_session('Catgeory not created');
        }
        return redirect()->route('admin.category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->category_obj->find($id);
        if (isset($data) && !empty($data)) {
            return view('admin.category.create', [
                'title' => 'Category Update',
                'breadcrumb' => breadcrumb([
                    'Category' => route('admin.category.index'),
                    'edit' => route('admin.category.edit', $id),
                ]),
            ])->with(compact('data'));
        }
        return redirect()->route('admin.category.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);
        $categories = $this->category_obj->find($id);
        if(isset($categories) && !empty($categories)){
            $return_data = $request->all();
            $return_data['sequence'] = !empty($request->sequence) ?  $request->sequence : 0;
            $this->category_obj->saveCategory($return_data,$id,$categories);
            success_session('Category updated successfully');
        }
        else{
            error_session('Category not found');
        }
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->category_obj::where('id', $id)->first();
        if ($data) {
            $deals =$this->deal_obj->where('category_id',$id)->get();
            if($deals->count() > 0){
                $deals->each->delete();
            }
            $data->delete();
            success_session('Category deleted successfully');
        } else {
            error_session('Category not found');
        }
        return redirect()->route('admin.category.index');
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

    public function status_update($id = 0)
    {
        $data = ['status' => 0, 'message' => 'Category Not Found'];
        $find = $this->category_obj->find($id);
        if ($find) {
            $find->update(['status' => ($find->status == "inactive") ? "active" : "inactive"]);
            $data['status'] = 1;
            $data['message'] = 'Category status updated';
        }
        return $data;
    }
}
