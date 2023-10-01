<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

use DB;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;



class RoleController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    function __construct()

    {

        $this->middleware('permission:المستخدمين|صلاحيات المستخدمين',['only' => ['index','store']]);

        $this->middleware('permission:اضافة صلاحية', ['only' => ['create','store']]);

       $this->middleware('permission:تعديل صلاحية', ['only' => ['edit','update']]);

        $this->middleware('permission:حذف صلاحية', ['only' => ['destroy']]);

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request): View
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);

        return view('roles.index',compact('roles'))

            ->with('i', ($request->input('page', 1) - 1) * 5);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(): View

    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request): RedirectResponse

    {

        $this->validate($request, [

            'name' => 'required|unique:roles,name',

            'permission' => 'required',

        ],[
            'name.required'=>'يرجي ادخال الدور المستخدم',
            'name.unique'=>'دور المستخدم موجود مسبقا',
            'permission'=>'يرجي اختيار الصلاحية',
        ]);


         $role = Role::create(['name' => $request->input('name')]);
//
          $role->syncPermissions($request->input('permission'));


        return redirect()->route('roles.index')

            ->with('success','تم انشاء الصلاحية بنجاح');


    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id): View

    {

        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('roles.show',compact('role','rolePermissions'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id): View

    {

        $role = Role::find($id);

        $permission = Permission::get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)

            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')

            ->all();



        return view('roles.edit',compact('role','permission','rolePermissions'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id): RedirectResponse

    {

        $this->validate($request, [

            'name' => 'required',

            'permission' => 'required',

        ],[
            'name.required'=>'يرجي ادخال الدور المستخدم',
            'name.unique'=>'دور المستخدم موجود مسبقا',
            'permission'=>'يرجي اختيار الصلاحية',
        ]);



        $role = Role::find($id);

        $role->name = $request->input('name');

        $role->save();



        $role->syncPermissions($request->input('permission'));



        return redirect()->route('roles.index')

            ->with('success','تم تعديل الدور بنجاح');

    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id): RedirectResponse

    {

        DB::table("roles")->where('id',$id)->delete();

        return redirect()->route('roles.index')

            ->with('success','تم اضافة الدور بنجاح');

    }

}
