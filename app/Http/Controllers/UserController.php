<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;



class UserController extends Controller

{
    function __construct()
    {

        $this->middleware('permission:المستخدمين|قائمة المستخدمين',['only' => ['index','store']]);

        $this->middleware('permission:اضافة مستخدم', ['only' => ['create','store']]);

         $this->middleware('permission:تعديل مستخدم', ['only' => ['edit','update']]);

          $this->middleware('permission:حذف مستخدم', ['only' => ['delete']]);

    }
    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request): View

    {

        $data = User::latest()->paginate(5);

        return view('users.show_users',compact('data'))

            ->with('i', ($request->input('page', 1) - 1) * 5);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.add_user',compact('roles'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request): RedirectResponse

    {

         $request->validate([
             'name' => 'required',
             'email' => 'required|email|unique:users,email',
             'password' => 'required|same:confirm-password',
             'roles' => 'required',
             'status'=>'required',
         ],[
             'name.required' => 'يرجي ادخال اسم المستخدم',
             'email.required' => 'يرجي ادخال الايميل',
             'email.unique' => 'هذا الايميل موجود من قبل',
             'password.required' => 'يرجي ادخال كلمه المرور',
             'password.same'=>'كلمه المرور يجب ان تطابق تاكيد كلمه المرور',
             'roles.required' => 'يرجي اختيار دور المستخدم',
             'status.required'=>'يرجي ادخال حالة المستخدم',
         ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')

            ->with('success','تم اضافة المستخدم بنجاح');

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id): View

    {

        $user = User::find($id);
        return view('users.show',compact('user'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id): View

    {

        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();

        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));

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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'

        ],[
            'name.required' => 'يرجي ادخال اسم المستخدم',
            'email.required' => 'يرجي ادخال الايميل',
            'email.unique' => 'هذا الايميل موجود من قبل',
            'password.required' => 'يرجي ادخال كلمه المرور',
            'password.same'=>'كلمه المرور يجب ان تطابق تاكيد كلمه المرور',
            'roles.required' => 'يرجي اختيار دور المستخدم',
            'status.required'=>'يرجي ادخال حالة المستخدم',
        ]);



        $input = $request->all();

        if(!empty($input['password'])){

            $input['password'] = Hash::make($input['password']);

        }else{

            $input = Arr::except($input,array('password'));

        }



        $user = User::find($id);

        $user->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')

            ->with('success','تم تعديل المستخدم بنجاح');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function delete(Request $r): RedirectResponse

    {
        $id=$r->user_id;
        User::find($id)->delete();
        return redirect()->route('users.index')

            ->with('success','تم حذف المستخدم بنجاح');

    }

}
