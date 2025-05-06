<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Yajra\DataTables\Facades\DataTables;

// use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('roles.edit', $row->id).'" class="btn btn-sm btn-warning">Edit</a>';
                    $btn .= ' <form action="'.route('roles.destroy', $row->id).'" method="POST" style="display:inline-block;">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                              </form>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $html = "";
                
                    $updateCheck = Permission::checkCRUDPermissionToUser("Items", "update");
                    $deleteCheck = Permission::checkCRUDPermissionToUser("Items", "delete");
                    $isSuperAdmin = Permission::isSuperAdmin();
                
                    if (!$isSuperAdmin && !$updateCheck && !$deleteCheck) {
                        return '';
                    }
                
                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="' . route('roles.edit', $row->id) . '">Edit</a></li>';
                    }
                
                    if ($isSuperAdmin || $deleteCheck) {
                        $html .= '<li>
                            <form action="' . route('roles.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this record?\');" style="margin:0;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="dropdown-item dropdown-trigger-17500btn waves-effect" >
                                    Delete
                                </button>
                            </form>
                        </li>';
                    }
                
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary px-3 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                ' . $html . '
                            </ul>
                        </div>';
                })
                
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = DB::select('SHOW TABLES');
        $fillArr = [];
            // dd($table);    
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validation = FacadesValidator::make($params,[
            'name' => 'required|string|unique:roles,name',
            
        ]);
        
        $params['guard_name'] = 'web';
        // dd($params);
        $role = Role::create($params);

        return redirect()->route('roles.edit',$role->id)->with('success', 'Role created successfully!');
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
    public function edit(Request $request, $id)
    {
        $tablesArr = [];
        $breadcrumbs = [];
        $pageConfigs = ['pageHeader' => true];
        if ($id) {
            $role = Role::find($id);

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $host = $request->getHttpHost();
                if ($host == 'localhost') {
                    $tablesArr[$table->Tables_in_mineology_server] = $table->Tables_in_mineology_server;
                } else {
                   
                    $tablesArr[$table->{'Tables_in_' . env('DB_DATABASE')}] = $table->{'Tables_in_' . env('DB_DATABASE')};
                }
            }

            // dd($tablesArr);
            unset(
                $tablesArr['cache'],
                $tablesArr['cache_locks'],
                $tablesArr['failed_jobs'],
                $tablesArr['job_batches'],
                $tablesArr['jobs'],
                $tablesArr['migrations'],
                $tablesArr['model_has_permissions'],
                $tablesArr['model_has_roles'],
            );

            $filterArr = [];

            if ($tablesArr['laboratories']) {
                $filterArr['Laboratories'] = 'Laboratories';
            }
            if ($tablesArr['pharmacies']) {
                $filterArr['Pharmacies'] = 'Pharmacies';
            }
            if ($tablesArr['roles']) {
                $filterArr['Roles'] = 'Roles';
            }
            if ($tablesArr['users']) {
                $filterArr['Users'] = 'Users';
            }
            if ($tablesArr['medicines']) {
                $filterArr['Medicines'] = 'Medicines';
            }
            if ($tablesArr['otcmedicines']) {
                $filterArr['Otcmedicines'] = 'Otcmedicines';
            }
            if ($tablesArr['popular_brands']) {
                $filterArr['PopularBrand'] = 'PopularBrand';
            }
            if ($tablesArr['popular_categories']) {
                $filterArr['PopularCategory'] = 'PopularCategory';
            }
            if ($tablesArr['phrmacymedicines']) {
                $filterArr['PhrmacyMedicines'] = 'PhrmacyMedicines';
            }
            if ($tablesArr['carts']) {
                $filterArr['Carts'] = 'Carts';
            }
            if ($tablesArr['prescriptions']) {
                $filterArr['Prescriptions'] = 'Prescriptions';
            }
            $permissionData = new Permission();
            return view('role.edit', ['pageConfigs' => $pageConfigs, 'role' => $role, 'accessData' => $filterArr, 'permissionData' => $permissionData]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $param = $request->all();

        $validator = Validator::make($param, [
            'name' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $role_id = $param['id'];
        if (!empty($param['permission'])) {
            Permission::where('role_id', $role_id)->delete();
            foreach ($param['permission'] as $key => $value) {
                $value['module'] = $key;
                $value['role_id'] = $role_id;
                $value['create'] = isset($value['create']) && $value['create'] == 'on' ? 1 : 0;
                $value['read'] = isset($value['read']) && $value['read'] == 'on' ? 1 : 0;
                $value['update'] = isset($value['update']) && $value['update'] == 'on' ? 1 : 0;
                $value['delete'] = isset($value['delete']) && $value['delete'] == 'on' ? 1 : 0;
                Permission::create($value);
            }
        } else {
            Permission::where('role_id', $role_id)->delete();
        }
        if (!empty($param)) {
            $role = Role::find($param['id']);
            unset($param['id']);
            $isUpdated = $role->update($param);
            if ($isUpdated) {
                return redirect('roles')->with('success', 'Updated Successfully.!');
            } else {
                return Redirect::back()->with('error', 'Something Wrong happend.!');
            }
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role=Role::findOrFail($id);
        // dd( $pharmacies);
        $role->delete();

       return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
