<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Security\Role;
use App\models\security\Permission;
use App\Models\Security\PermissionRol;
use App\Http\Requests\Security\RoleRequest;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::all();
        $permissions = Permission::all();

        return response()->json([
        'role' => $role,
        'permissions' => $permissions
        ]);
    }

    public function create(RoleRequest $request)
    {
        DB::beginTransaction();
        try{
            $role =  new Role ();
            $role->name = $request->get('name') ;
            $role->sort = $request->get('sort') ;

            if ($request->get('associated-permissions') == 'all'){//input recibido desde el front
                $role->all_permissions = 1 ;
            }else{
                $role->all_permissions = 0 ;
            }
            $role->save();

            if ($request->get('associated-permissions') != 'all'){//custom

                foreach ($request->get('permissions') as $permiso) {
                    $Permission_role = new PermissionRol();
                    $Permission_role->permission_id = $permiso;
                    $Permission_role->role_id = $role->id;
                    $Permission_role-> save();
                }
            }

            DB::commit();

            return response()->json(['message'=>'Rol creado satisfactoriamente']);

        }catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error'=>'El Rol no pudo ser creado']);
        }

    }

    public function edit (Request $request, $id)
    {
        DB::transaction(function () use ($id){

            $role = Role::FindOrFail($id);
            $permissions = Permission::orderBy('sort', 'asc')->get()->groupby('module');
            $role_permissions[] = [];
            foreach ($Role->permissions as $permiso) {
              $role_permissions[]= $permiso->id;
            }

        },3);

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' =>$role_permissions
        ]);
    }

    public  function update (UpdateRoleRequest $request, $id) {

        if ($request->get('associated-permissions') != 'all'){
            $permission_role= PermissionRol::where('role_id',$id)->get();
            foreach ($permission_role as $permission_rol) {
                $permission_rol_d = PermissionRol::FindOrFail($permission_rol->id);
                $permission_rol_d->delete();
            }
        }

        DB::beginTransaction();
        try{
            $role =Role::FindOrFail ($id);
            $role->name = $request->get('name') ;
            if ($request->get('associated-permissions')=='all'){
                $role->all_permissions = 1 ;
            }else{
                $role->all_permissions = 0 ;
            }
            $role->save();
        
            if ($request->get('associated-permissions')!='all'){
                foreach ($request->get('permissions') as $permiso) {
                $Permission_role= new PermissionRol();
                $Permission_role ->permission_id = $permiso;
                $Permission_role ->role_id = $role->id;
                $Permission_role -> save();
                }
            }
            DB::commit();
            
            return response()->json(['message'=>'Rol actualizado satisfactoriamente']);
            
        }catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error'=>'el rol no pudo ser actualizado']);

        }
    }

    public function activate($id)
    {
        DB::transaction(function () use ($id){
            $role = Role::find($id);
            $role->status = 1;
            $role->save();
        },3);

        return response()->json(['message' => 'Rol activado satisfactoriamente!'], 201);
    }

    public function desactivate($id)
    {
        DB::transaction(function () use ($id){
            $rol = Role::find($id);
            $rol->status = 0;
            $rol->save();
        },3);

        return response()->json(['Rol desactivado con exito'], 201);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id){
            $rol = Role::find($id)->delete();
        },3);

        return response()->json(['Usuario eliminado con exito'], 201);
    }
}
