<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
// use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     
     */

  
    public function index()
    {
        $role = Role::all();
        return view('role.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = DB::select('SHOW TABLES');
        $fillArr = [];
        // foreach ($tables as $table) {
        //     // dd($table);
        //     // $fillArr = array($table->Tables_in_medical_app);

        //     $tableName = array_values((array) $table)[0];
        //     $fillArr[] = $tableName; // Better than array_push in this context
        // }
        // return $fillArr;
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
         
        ]);
        
        $validatedData['guard_name'] = 'web';
        $role = Role::create($validatedData);

        // foreach ($request->permission as $key => $value) {
        //     $data['role_id'] = $role->id;
        //     $data['module'] = $key;
        //     $data['create'] = isset($value['create']) && $value['create'] == 'on' ? 1 : 0;
        //     $data['read'] = isset($value['read']) && $value['read'] == 'on' ? 1 : 0;
        //     $data['update'] = isset($value['update']) && $value['update'] == 'on' ? 1 : 0;
        //     $data['delete'] = isset($value['delete']) && $value['delete'] == 'on' ? 1 : 0;
        //     Permission::create($data);
        // }


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
