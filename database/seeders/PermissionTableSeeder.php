<?php



namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;



class PermissionTableSeeder extends Seeder

{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $permissions = [
            'قائمة الفواتير'=>'invoices.index',
            'الفواتير المدفوعة'=>'invoicePaid',
            'الفواتير المدفوعة جزئيا'=>'invoicePartiallyPaid',
            'الفواتير الغير مدفوعة'=>'invoiceUnpaid',
            'ارشيف الفواتير'=>'invoiceArchive',
            'اضافة فاتورة'=>'invoices.create',
            'حذف الفاتورة'=>'deleteInvoic',
            'تصدير EXCEL'=>'exportInvoices',
            'تغير حالة الدفع'=>'showStatus',
            'تعديل الفاتورة'=>'invoices.edit',
            'ارشفة الفاتورة'=>'archiveInvoice',
            'استرجاع الفاتورة'=>'restoreInvoice',
            'طباعةالفاتورة'=>'printInvoice',
            'اضافة مرفق'=>'invoiceDetails.store',
            'حذف المرفق'=>'deleteFile',


            'قائمة المستخدمين'=>'users.index',
            'اضافة مستخدم'=>'users.create',
            'تعديل مستخدم'=>'users.edit',
            'حذف مستخدم'=>'deleteUser',
            'صلاحيات المستخدمين'=>'roles.index',
            'عرض صلاحية'=>'roles.show',
            'اضافة صلاحية'=>'roles.create',
            'تعديل صلاحية'=>'roles.edit',
            'حذف صلاحية'=>'roles.destroy ',

            'تقرير الفواتير'=>'invoiceReport',
            'تقرير العملاء'=>'customerReport',

            'الاقسام'=>'sections.index',
            'اضافة قسم'=>'sections.sections',
            'تعديل قسم'=>'sections.update',
            'حذف قسم'=>'sections.destroy',
            'المنتجات'=>'products.index',
            'اضافة منتج'=>'products.store',
            'تعديل منتج'=>'products.update',
            'حذف منتج'=>'products.destroy',

        ];

        foreach ($permissions as $permission=>$route) {

            Permission::create([
                'name' => $permission,
                'routes'=>$route,
                ]);

        }

    }

}
