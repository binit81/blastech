<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->string('employee_code',100);
            $table->string('employee_firstname',100);
            $table->string('employee_middlename',100);
            $table->string('employee_lastname',100);
            $table->string('employee_mobileno_dial_code',10);
            $table->string('employee_mobileno',15);
            $table->date('employee_joiningdate')->nullable();
            $table->tinyInteger('employee_login')->default('1')->comment = "0=cant login; 1=can login";
            $table->string('email',200);
            $table->string('password',255)->nullable();
            $table->string('encrypt_password',255)->nullable();
            $table->string('employee_alternate_mobile_dial_code',10);
            $table->string('employee_alternate_mobile',15);
            $table->string('employee_family_member_mobile_dial_code',10);
            $table->string('employee_family_member_mobile',15);
            $table->string('employee_designation',255);
            $table->longText('employee_duties');
            $table->string('employee_salary_offered',10);
            $table->longText('employee_skills');
            $table->longText('employee_education');
            $table->longText('employee_past_experience');
            $table->date('employee_dob')->nullable();
            $table->tinyInteger('employee_marital_status')->nullable()->comment = "1=single; 2=married; 3:divorced; 3:widow";
            $table->tinyInteger('employee_address_type')->nullable()->comment = "1=house; 2=building; 3=street";
            $table->longText('employee_address');
            $table->string('employee_area',255);
            $table->string('employee_city_town',255);
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('state_id')->on('states');
            $table->string('employee_zipcode',10);
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('country_id')->on('countries');
            $table->string('employee_picture',255);
            $table->longText('employee_reference');
            $table->date('employee_resigned_date')->nullable();
            $table->longText('employee_resigned_reason');
            $table->longText('employee_remarks');
            $table->tinyInteger('is_master')->nullable()->comment = "0=normal; 1=master";
            $table->string('api_token',255);
            $table->string('app_id',255);
            $table->string('app_secret',255);
            $table->tinyInteger('is_active')->default('1')->comment = "1=active,0=inactive";
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
            $table->softDeletes('deleted_at');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });

        DB::table('users')->insert(array(
        
          'company_id' => '1',
          'employee_firstname' => 'Administrator',
          'employee_lastname' => '',
          'email' => 'test@retailcore.in',
          'password' => bcrypt('123456'),
          'encrypt_password' => bcrypt('123456'),
          'is_master' => '1',
          'app_id' => md5(microtime().'Administrator'),
          'app_secret' => md5(microtime().'test@retailcore.in'),
          'employee_joiningdate' => date('Y-m-d')

        ));

        Schema::disableForeignKeyConstraints();

        Schema::table('home_navigations', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('home_navigation_id');
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('created_by')->unsigned()->nullable()->after('is_active');
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
        });

        Schema::table('home_navigations_datas', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('home_navigation_id');
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->integer('created_by')->unsigned()->nullable()->after('is_active');
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('user_id')->on('users');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('user_id')->on('users');
        });

        //////////////////////////////////////////
        //////////// HOME NAVIGATIONS ////////////
        //////////////////////////////////////////

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Dashboard',
          'nav_tab_display_name' => 'Dashboard',
          'nav_url' => 'dashboard',
          'nav_label' => '',
          'nav_icon_class' => 'icon dripicons-home',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '1'

        ));

        // SALES

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Sales',
          'nav_tab_display_name' => 'Sales',
          'nav_url' => '',
          'nav_label' => 'SL',
          'nav_icon_class' => '',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '2'

        ));

        // PURCHASE

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Purchase',
          'nav_tab_display_name' => 'Purchase',
          'nav_url' => '',
          'nav_label' => 'PR',
          'nav_icon_class' => '',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

        // INVENTORY

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Inventory',
          'nav_tab_display_name' => 'Inventory',
          'nav_url' => '',
          'nav_label' => 'INV',
          'nav_icon_class' => '',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));

        // REPORTS

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Reports',
          'nav_tab_display_name' => 'Reports',
          'nav_url' => '',
          'nav_label' => 'REP',
          'nav_icon_class' => '',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

        // OPTIONS

        DB::table('home_navigations')->insert(array(
        
          'company_id' => '1',
          'nav_display_name' => 'Company',
          'nav_tab_display_name' => 'Company',
          'nav_url' => '',
          'nav_label' => 'OPT',
          'nav_icon_class' => '',
          'parent' => '0',
          'is_active' => '1',
          'ordering' => '6'

        ));


        //////////////////////////////////////////
        ///////// HOME NAVIGATIONS DATA //////////
        //////////////////////////////////////////

        // SALES

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Sales Bill',
          'nav_tab_display_name' => 'Sales Bill',
          'nav_url' => 'sales_bill',
          'nav_icon_class' => 'icon dripicons-card',
          'nav_keywords'  =>  'SALES BILL, MAKE BILL, MAKE INVOICE, SELL, BILLING, ADD BILL, ADD INVOICE, INVOICE, SELL BILL, SELL INVOICE, BILL, INVOICE, NEW SALES BILL, NEW BILL, NEW INVOICE, SALES INVOICE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '1'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'View Bills',
          'nav_tab_display_name' => 'View Bills',
          'nav_url' => 'view_bill',
          'nav_icon_class' => 'icon dripicons-checklist',
          'nav_keywords'  =>  'VIEW BILLS, VIEW SALES, SHOW SALES, SHOW BILLS, SHOW INVOICE, BILLS, INVOICE, VIEW SELL, VIEW SELL BILLS, EDIT BILL, VIEW INVOICE, SALES RECORD, INCOME',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '1',
          'option_upload' => '1',
          'is_active' => '1',
          'ordering' => '2'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Sales Return',
          'nav_tab_display_name' => 'Sales Return',
          'nav_url' => 'sales_return',
          'nav_icon_class' => 'icon dripicons-return',
          'nav_keywords'  =>  'SALES RETURN, SALE RETURN, SELL RETURN, PRODUCT RETURN BY CUSTOMER, CREDIT NOTE, MAKE CREDIT NOTE, CUSTOMER PRODUCT RETURN, PRODUCT EXCHANGE, CASH REFUND, REJECTED, CREDITNOTE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Returned Products',
          'nav_tab_display_name' => 'Returned Products',
          'nav_url' => 'returned_products',
          'nav_icon_class' => 'icon dripicons-return',
          'nav_keywords'  =>  'RETURNED PRODUCTS, PRODUCTS RETURNED BY CUSTOMER, DAMANGE PRODUCT, RETURN PRODUCT, REJECTED PRODUCT, DISCARD PRODUCT',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));
	
	  DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Customer Source',
          'nav_tab_display_name' => 'Customer Source',
          'nav_url' => 'customer_source',
          'nav_icon_class' => 'icon dripicons-user-group',
          'nav_keywords'  =>  'CUSTOMER, ADD NEW CUSTOMER, NEW CUSTOMER, VIEW CUSTOMERS, ADD CUSTOMER, UPDATE CUSTOMER DETAIL, DELETE CUSTOMER, REMOVE CUSTOMER, CUSTOMER CONTACT, CUSTOMAR PHONE, CUSTOMER PHONE, CUSTOMER EMAIL, CUSTOMAR EMAIL, CASTOMAR, CUSTOMAR, BUYER, CLIENT, CUSTOMER GSTIN, CUSTOMER LOCATION, CUSTOMER ADDRESS, CUSTOMER INFORMATION, SEARCH CUSTOMER, FIND CUSTOMER, CUSTOMER CREDIT LIMIT, CLIENT CREDIT LIMIT, SET CREDIT LIMIT, SET CUSTOMER BALANCE LIMIT, CUSTOMER BALANCE LIMIT, ASSETS, BIRTHDAY, BIRTH DATE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Customers',
          'nav_tab_display_name' => 'Customers',
          'nav_url' => 'customer_show',
          'nav_icon_class' => 'icon dripicons-user-group',
          'nav_keywords'  =>  'CUSTOMER, ADD NEW CUSTOMER, NEW CUSTOMER, VIEW CUSTOMERS, ADD CUSTOMER, UPDATE CUSTOMER DETAIL, DELETE CUSTOMER, REMOVE CUSTOMER, CUSTOMER CONTACT, CUSTOMAR PHONE, CUSTOMER PHONE, CUSTOMER EMAIL, CUSTOMAR EMAIL, CASTOMAR, CUSTOMAR, BUYER, CLIENT, CUSTOMER GSTIN, CUSTOMER LOCATION, CUSTOMER ADDRESS, CUSTOMER INFORMATION, SEARCH CUSTOMER, FIND CUSTOMER, CUSTOMER CREDIT LIMIT, CLIENT CREDIT LIMIT, SET CREDIT LIMIT, SET CUSTOMER BALANCE LIMIT, CUSTOMER BALANCE LIMIT, ASSETS, BIRTHDAY, BIRTH DATE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Customer Balance',
          'nav_tab_display_name' => 'Customer Balance',
          'nav_url' => 'customer_credit_summary',
          'nav_icon_class' => 'icon dripicons-user-id',
          'nav_keywords'  =>  'CUSTOMER BALANCE, CUSTOMER CREDIT SUMMARY, CUSTOMER OUTSTANDING PAYMENT, CUSTOMER BALANCE PAYMENT, OUTSTANDING BILLS, OUTSTANDING AMOUNT, OUTSTANDING MONEY, OUTSTANDING PAYMENT, AMOUNT TO BE RECEIVED, CUSTOMER OUTSTANDING AMOUNT, MONEY TO BE RECEIVED, GET MONEY, CREDIT GIVEN, ACCOUNT RECEIVABLES, ASSETS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '6'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '2',
          'company_id' => '1',
          'nav_display_name' => 'Customer Receipts',
          'nav_tab_display_name' => 'Customer Receipts',
          'nav_url' => 'view_customer_creditreceipt',
          'nav_icon_class' => 'icon dripicons-document-new',
          'nav_keywords'  =>  'CUSTOMER RECEIPTS, CUSTOMER PAYMENT RECEIVED, CUSTOMER OUTSTANDING PAID, CUSTOMER BALANCE RECEIPT, CUSTOMER BALANCE UPDATE, PAYMENT RECEIVED FROM CUSTOMER, ASSETS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '7'

        ));

        // PURCHASE

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'FMCG Inward Stock',
          'nav_tab_display_name' => 'FMCG Inward Stock',
          'nav_url' => 'inward_stock',
          'nav_icon_class' => 'icon dripicons-enter',
          'nav_keywords'  =>  'STOCK INWARD, INWARD STOCK, IN WARD, FMCG INWARD STOCK, FMCG STOCK INWARD, PURCHASE INWARD, PURCHASE STOCK INWARD, PRODUCT INWARD, PRODUCTS INWARD, INWARD ENTRY, UPLOAD INWARD STOCK, BULK UPLOAD INWARD STOCK, UPLOAD PURCHASE STOCK, UPLOAD PURCHASE PRODUCT, IMPORT INWARD STOCK, BULK IMPORT INWARD STOCK, SHOW INWARD STOCK, SHOW STOCK INWARD, CHANGE COST PRICE, CHANGE SELLING PRICE, UPDATE COST PRICE, UPDATE SELLING PRICE, MANUFACTURING DATE, EXPIRY DATE, BATCH NO., BATCH NUMBER, PRODUCT BATCH NUMBER, ITEM BATCH NUMBER, PURCHASE ENTRY, NEW STOCK ENTRY, NEW PRODUCT ENTRY, STOCK IN',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '1',
          'is_active' => '1',
          'ordering' => '1'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Garment Inward Stock',
          'nav_tab_display_name' => 'Garment Inward Stock',
          'nav_url' => 'inward_stock_show',
          'nav_icon_class' => 'icon dripicons-enter',
          'nav_keywords'  =>  'STOCK INWARD, INWARD STOCK, GARMENT INWARD STOCK, GARMENT STOCK INWARD, APPAREL INWARD STOCK, APPAREL STOCK INWARD, CLOTH INWARD STOCK, CLOTH STOCK INWARD, PURCHASE INWARD, PURCHASE STOCK INWARD, PRODUCT INWARD, PRODUCTS INWARD, INWARD ENTRY, UPLOAD INWARD STOCK, BULK UPLOAD INWARD STOCK, UPLOAD PURCHASE STOCK, UPLOAD PURCHASE PRODUCT, IMPORT INWARD STOCK, BULK IMPORT INWARD STOCK, SHOW INWARD STOCK, SHOW STOCK INWARD, ADD STOCK, ADD TO STOCK, ADD IN STOCK, CHANGE COST PRICE, CHANGE SELLING PRICE, PURCHASE ENTRY, NEW STOCK ENTRY, NEW PRODUCT ENTRY, STOCK IN',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '1',
          'is_active' => '1',
          'ordering' => '2'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'View Inward Stock',
          'nav_tab_display_name' => 'View Inward Stock',
          'nav_url' => 'view_inward_stock',
          'nav_icon_class' => 'icon dripicons-checklist',
          'nav_keywords'  =>  'VIEW INWARD STOCK, VIEW STOCK INWARD, VIEW PURCHASE STOCK ENTRY, VIEW STOCK ENTRIES, EXPORT TO EXCEL INWARD STOCK, EDIT INWARD STOCK, PURCHASE, UPDATE IN STOCK, UPDATE STOCK, VIEW NEW STOCK, INWARD LIST',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Issue PO(Additional Module)',
          'nav_tab_display_name' => 'Issue PO(Additional Module)',
          'nav_url' => 'issue_po',
          'nav_icon_class' => 'icon dripicons-blog',
          'nav_keywords'  =>  'ISSUE PO, MAKE PO, ISSUE PURCHASE ORDER, MAKE PURCHASE ORDER, ISSUE WORK ORDER, MAKE WORK ORDER, REQUISITION, ORDERED, BUY, SUPPLIER ORDER, ORDER FORM, PLACE ORDER, PLACE AN ORDER, REQUEST PRODUCTS, REQUEST STOCK',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));

        DB::table('home_navigations_datas')->insert(array(

            'home_navigation_id' => '3',
            'company_id' => '1',
            'nav_display_name' => 'View PO(Additional Module)',
            'nav_tab_display_name' => 'View PO(Additional Module)',
            'nav_url' => 'view_issue_po',
            'nav_icon_class' => 'icon dripicons-checklist',
            'nav_keywords'  =>  'VIEW PO, VIEW PURCHASE ORDER, VIEW ISSUE PO, VIEW ISSUED PO, VIEW ISSUED PURCHASE ORDER, ORDERS, ORDERED, VIEW WORK ORDER, SHOW PO, SHOW PURCHASE ORDER, SHOW WORK ORDER, WORKORDER, PURCHASEORDER',
            'option_view' => '1',
            'option_add' => '1',
            'option_edit' => '1',
            'option_delete' => '1',
            'option_export' => '1',
            'option_print' => '0',
            'option_upload' => '0',
            'is_active' => '1',
            'ordering' => '5'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Suppliers',
          'nav_tab_display_name' => 'Suppliers',
          'nav_url' => 'supplier',
          'nav_icon_class' => 'icon dripicons-user-group',
          'nav_keywords'  =>  'SUPPLIERS, VENDORS, ADD VENDOR, NEW SUPPLIER, NEW VENDOR, ADD SUPPLIER, ADD NEW SUPPLIER, SUPPLIER LIST, SUPPLIER GSTIN, ADD SUPPLIER GSTIN, EDIT SUPPLIERS, DELETE SUPPLIERS, REMOVE SUPPLIERS, UPDATE SUPPLIERS, SUPPLIER ADDRESS, SUPPLIER BANK ACCOUNT, BIRTHDAY, BIRTH DATE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '6'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Amount Payable to Supplier',
          'nav_tab_display_name' => 'Amount Payable to Supplier',
          'nav_url' => 'supplier_payment',
          'nav_icon_class' => 'icon dripicons-card',
          'nav_keywords'  =>  'AMOUNT PAYABLE TO SUPPLIER, SUPPLIER PAYMENT, SUPPLIER OUSTANDING PAYMENT, OUTSTANDING PAYMENT TO SUPPLIERS, MAKE PAYMENT TO SUPPLIER, GIVE PAYMENT TO SUPPLIER, CLEAR SUPPLIER PAYMENT, OUTSTANDING AMOUNT, OUTSTANDING PAYMENT, ACCOUNTS PAYABLES, ACCOUNT PAYABLE, DUE PAYMENTS, LIABILITIES, DEBTOR ACCOUNTS, DEBTORS ACCOUNTS, PAYABLE LIABILITIES, AMOUNT PAYABLE TO VENDOR, VENDOR PAYMENT, VENDOR OUSTANDING PAYMENT, OUTSTANDING PAYMENT TO VENDORS, MAKE PAYMENT TO VENDOR, GIVE PAYMENT TO VENDOR, CLEAR VENDOR PAYMENT, EXPENSE',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '7'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Supplier Payment Receipt',
          'nav_tab_display_name' => 'Supplier Payment Receipt',
          'nav_url' => 'supplier_payment_receipt',
          'nav_icon_class' => 'icon dripicons-blog',
          'nav_keywords'  =>  'SUPPLIER PAYMENT RECEIPT, PAID TO SUPPLIERS, EXPENSES, AMOUNT PAID TO SUPPLIERS, PAYAMENT TO SUPPLIER, PAYMENT GIVEN TO SUPPLIERS, VENDOR PAYMENT',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '8'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'Debit Note',
          'nav_tab_display_name' => 'Debit Note',
          'nav_url' => 'debit_note',
          'nav_icon_class' => 'icon dripicons-blog',
          'nav_keywords'  =>  'DEBIT NOTE, DEBITNOTE, MAKE DEBITNOTE, MAKE DEBIT NOTE, RETURN PRODUCT TO SUPPLIER, RETURN STOCK TO SUPPLIER, GOODS RETURNED TO SUPPLIER, GOODS RETURN TO SUPPLIER, STOCK RETURNED TO SUPPLIER, ITEM RETURNED TO SUPPLIER, RETURN PRODUCT TO SUPPLIER, RETURN STOCK TO SUPPLIER, GOODS RETURNED TO SUPPLIER, GOODS RETURN TO SUPPLIER, STOCK RETURNED TO SUPPLIER, ITEM RETURNED TO SUPPLIER, STOCK OUT',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '9'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '3',
          'company_id' => '1',
          'nav_display_name' => 'View Debit Note',
          'nav_tab_display_name' => 'View Debit Note',
          'nav_url' => 'view_debit_note',
          'nav_icon_class' => 'icon dripicons-blog',
          'nav_keywords'  =>  'VIEW DEBIT NOTES, SHOW DEBIT NOTES, SHOW GOODS RETURNED TO SUPPLIER, SHOW PRODUCT RETURNED TO SUPPLIER, QUANTITY RETURNED, STOCK RETURNED TO SUPPLIER, STOCK OUT',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '10'

        ));

        // INVENTORY

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Products',
          'nav_tab_display_name' => 'Products',
          'nav_url' => 'product_show',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  'PRODUCT MANAGER, PRODUCT MASTER, MERCHANDISE, ITEMS, ITEM MANAGER, ITEM MASTER, ADD NEW ITEM, ADD NEW PRODUCT, SHOW PRODUCTS, SHOW ITEMS, SHOW PRODUCT PROFILE, PRODUCT BRANDS, PRODUCT CATEGORIES, COLOR, COLOUR, SIZE, MATERIAL, BRAND, SKU, PRODUCT DESCRIPTION, HSN, UQC, PRODUCT EXPIRY ALERT, LOW STOCK ALERT, SUPPLIER BARCODE, PRODUCT BARCODE, PRODUCT PICTURES, PRODUCT IMAGES, PRODUCT CODE, OFFER PRICE, WHOLESALE PRICE, MRP, COST PRICE, SELLING PRICE, PROFIT MARGIN, ASSETS, NEW PRODUCT',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '1'

        ));
      
        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Stock Audit',
          'nav_tab_display_name' => 'Stock Audit',
          'nav_url' => '#',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  'inventory, stock audit',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '2'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Adjust Stock',
          'nav_tab_display_name' => 'Adjust Stock',
          'nav_url' => '#',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  'inventory, adjust stock',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Re-Order Stock',
          'nav_tab_display_name' => 'Re-Order Stock',
          'nav_url' => '#',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  'inventory, re-order stock',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Damage/Used Products',
          'nav_tab_display_name' => 'Damage/Used Products',
          'nav_url' => 'damage-used-products',
          'nav_icon_class' => 'icon dripicons-archive',
          'nav_keywords'  =>  'DAMAGE PRODUCT, USED PRODUCT, STOCK OUT, DISCARD PRODUCT, ISSUE DAMANGE PRODUCTS, ISSUE USED PRODUCTS, ISSUE SAMPLE PRODUCTS, DISCARD DAMAGED PRODUCT, DISCARD BROKEN PRODUCT, REMOVE DAMAGED PRODUCT, REMOVE DAMAGED ITEM, INTERNAL USE PRODUCTS, LOSS',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

        DB::table('home_navigations_datas')->insert(array(
            'home_navigation_id' => '4',
            'company_id' => '1',
            'nav_display_name' => 'Barcode Printing',
          'nav_tab_display_name' => 'Barcode Printing',
          'nav_url' => 'barcode-printing',
          'nav_icon_class' => 'ion ion-md-barcode',
          'nav_keywords'  =>  'BARCODE PRINTING, BARCODE LABEL PRINTING, LABEL, A4 LABEL, BAR CODE, PRINT, REPRINT, RE PRINT, BARCODE REPRINT, BAR CODE RE PRINT, BARCODE RE PRINT, PRINT BARCODE, PRINT BAR CODE, PRODUCT LABEL',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '6'
	    
        ));

        // REPORTS

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Product Wise Sale Report',
          'nav_tab_display_name' => 'Product Wise Sale Report',
          'nav_url' => 'view_productwise_bill',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'BILLS PRODUCT WISE, PRODUCT WISE SALES REPORT, TOTAL SALES REPORT, TOTAL SALES AMOUNT, MONTHLY SALES REPORT, PRODUCT WISE INVOICE, INCOME',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '1'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Sale GST Report (%) Wise',
          'nav_tab_display_name' => 'Sale GST Report (%) Wise',
          'nav_url' => 'productgst_perwise_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'BILLS GST% WISE, BILLS GST PERCENTAGE WISE REPORT, GST% WISE SALES REPORT, GST PERCENTAGE WISE REPORT, GST PERCENTAGE WISE SALES REPORT, MONTHLY GST SALES REPORT, OUTWARD SUPPLIES, INCOME, GST WISE INVOICE, GST WISE BILLS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '2'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Stock Report',
          'nav_tab_display_name' => 'Stock Report',
          'nav_url' => 'stock_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'STOCK REPORT, INVENTORY REPORT, STOCK LIST, MERCHANDISE, SHOW INVENTORY REPORT, STOCK DETAIL REPORT, SHOW STOCK REPORT, STOCK VALUATION, TOTAL STOCK VALUATION, REPORT, TOTAL STOCK REPORT, PERIODIC STOCK REPORT, MONTHLY STOCK REPORT, INVENTORY VALUATION REPORT, ASSETS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

         DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Price Master',
          'nav_tab_display_name' => 'Price Master',
          'nav_url' => 'price_master_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'report, price master, purchase',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));

         DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Damage/Used',
          'nav_tab_display_name' => 'Damage/Used ',
          'nav_url' => 'damage-used-report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'DAMAGED PRODUCT REPORT, USED PRODUCT REPORT, DISCARDED PRODUCT REPORT, DISCARDED ITEM REPORT, USED ITEM REPORT, DAMAGED ITEM REPORT, REMOVE DAMAGED PRODUCT, EDIT DAMANGED PRODUCT, REMOVE USED PRODUCT, EDIT DAMANGED PRODUCT, DELETE DAMAGED PRODUCT ITEM, LOSS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

         DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Damage Used Product ',
          'nav_tab_display_name' => 'Damage Used Product ',
          'nav_url' => 'damage-used-product-wise',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'DAMAGED PRODUCT WISE REPORT, USED PRODUCT WISE REPORT, DISCARDED PRODUCT WISE REPORT, CUSTOMER RETURNED DAMANGE PRODUCT, DAMAGED ITEM  WISE REPORT, USED ITEM WISE REPORT, DISCARDED ITEM WISE REPORT, CUSTOMER RETURNED DAMANGE ITEM, DAMAGED STOCK REPORT, INTERNAL USED PRODUCTS REPORT, LOSS',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '6'
        ));

          DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Inward Report (Product Wise)',
          'nav_tab_display_name' => 'Inward Report (Product Wise)',
          'nav_url' => 'product_wise_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'PRODUCT WISE INWARD REPORT, SHOW INWARD STOCK REPORT, SHOW INVENTORY INWARD REPORT, SHOW INWARD INVENTORY REPORT, INWARD STOCK REPORT, INVENTORY INWARD REPORT, INWARD INVENTORY REPORT, ITEM WISE INWARD REPORT, PURCHASE STOCK REPORT, PRODUCT PURCHASE REPORT, TOTAL PURCHASE',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '7'

        ));


          DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Inward Report (Invoice Wise)',
          'nav_tab_display_name' => 'Inward Report (Invoice Wise)',
          'nav_url' => 'supplier_wise_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'SUPPLIER WISE INWARD REPORT, SHOW SUPPLIER INWARD STOCK REPORT, SHOW SUPPLIER INVENTORY INWARD REPORT, SHOW SUPPLIER WISE INWARD INVENTORY REPORT, TOTAL PURCHASE FROM SUPPLIER, PO WISE INWARD REPORT, TOTAL COST, TOTAL INWARD QUANTITY',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '8'

        ));


          DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Debit Note Report',
          'nav_tab_display_name' => 'Debit Note Report',
          'nav_url' => 'debit_note_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'DEBIT NOTE REPORT, DEBIT RECEIPT NO., DEBIT RECEIPT NUMBER, ITEMS RETURNED TO SUPPLIER, PRODUCTS RETURNED TO SUPPLIER, PRODUCT RETURN TO SUPPLIER, DEBIT NOTE BALANCE AMOUNT, AMOUNT RECEIVABLE FROM SUPPLIER, SUPPLIER DEBIT NOTE',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '9'

        ));



           DB::table('home_navigations_datas')->insert(array(

          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Inward/GST(%) Wise Report',
          'nav_tab_display_name' => 'Inward/GST(%) Wise Report',
          'nav_url' => 'inward_gst_percent_wise_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  '',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '10'
        ));


        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Credit Note Report',
          'nav_tab_display_name' => 'Credit Note Report',
          'nav_url' => 'creditnote_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'CREDITNOTE REPORT, CREDIT NOTE REPORT, CREDIT NOTES, ISSUED CREDIT NOTES, VIEW CREDIT NOTES, SHOW CREDIT NOTES, SHOW ISSUED CREDIT NOTES, SHOW USED CREDITS NOTES, USED CREDIT NOTES, PRINT CREDIT NOTE, REPRINT CREDIT NOTES, RE PRINT CREDIT NOTES, LIABILITIES',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '11'

        ));

        

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Profit & Loss Report(Product Wise)',
          'nav_tab_display_name' => 'Profit & Loss Report(Product Wise)',
          'nav_url' => 'profit_loss_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'PROFIT AND LOSS REPORT, PROFIT & LOSS REPORT, PROFIT ON PRODUCT, PRODUCT WISE PROFIT REPORT, SALES WISE PROFIT AND LOSS REPORT, PROFIT REPORT, LOSS REPORT',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '12'

        ));


        

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '5',
          'company_id' => '1',
          'nav_display_name' => 'Batch No. Wise Inward',
          'nav_tab_display_name' => 'Batch No. wise Report',
          'nav_url' => 'batch_no_wise_report',
          'nav_icon_class' => 'icon dripicons-pulse',
          'nav_keywords'  =>  'BATCH NUMBER WISE STOCK REPORT, BATCH NUMBER WISE INVENTORY REPORT, BATCH NO. WISE STOCK REPORT, BATCH NO. WISE INVENTORY REPORT',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '13'

        ));

       
        

       

        

       

        // OPTIONS

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'Company Profile',
          'nav_tab_display_name' => 'Company Profile',
          'nav_url' => 'company_profile',
          'nav_icon_class' => 'icon dripicons-store',
          'nav_keywords'  =>  'COMPANY PROFILE, OWNER, BUSINESS PROFILE, STORE PROFILE, STORE SETUP, CONFIGURATION, SOCIAL MEDIA, FOOTER, BILL FOOTER, INVOICE FOOTER, INVOCIE SETUP, BILL SETUP, BANK ACCOUNT DETAILS ON BILL, DETAILS ON BILL, INFORMATION ON BILL, BILL NUMBER PREFIX, DEBIT RECEIPT PREFIX, RETURN POLICY, SALES RETURN POLICY, NAVIGATION TYPE CHANGE, WEBSITE, INSTAGRAM, WHATSAPP, FACEBOOK, PINTEREST, TWITTER',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '1'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'GST Slabs',
          'nav_tab_display_name' => 'GST Slabs',
          'nav_url' => 'gst_slabs',
          'nav_icon_class' => 'fa fa-percent',
          'nav_keywords'  =>  'GST SLABS, VARIABLE GST RANGE, GST RANGE, GST% RANGE, GST PERCENTAGE RANGE, GST% RANGE, GST% SLABS',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '2'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'Manage Backup',
          'nav_tab_display_name' => 'Manage Backup',
          'nav_url' => 'backups',
          'nav_icon_class' => 'icon dripicons-stack',
          'nav_keywords'  =>  'MANAGE BACKUPS, BACKUP, DATA BACKUP, FULL DATA BACKUP, CREATE DATA BACKUP, GET BACKUP, BACK UP, TAKE BACKUP',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'Employees',
          'nav_tab_display_name' => 'Employees',
          'nav_url' => 'employee_master',
          'nav_icon_class' => 'icon dripicons-user-group',
          'nav_keywords'  =>  'option, employee master',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '1',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'Cash Book',
          'nav_tab_display_name' => 'Cash Book',
          'nav_url' => '#',
          'nav_icon_class' => 'icon dripicons-document-new',
          'nav_keywords'  =>  'misc, cash book',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '5'

        ));

        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '6',
          'company_id' => '1',
          'nav_display_name' => 'Expense Manager',
          'nav_tab_display_name' => 'Expense Manager',
          'nav_url' => '#',
          'nav_icon_class' => 'icon dripicons-document-new',
          'nav_keywords'  =>  'misc, expense manager',
          'option_view' => '1',
          'option_add' => '0',
          'option_edit' => '0',
          'option_delete' => '0',
          'option_export' => '0',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '6'
        ));
        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Make Kit',
          'nav_tab_display_name' => 'Make Kit',
          'nav_url' => 'addproducts_kit',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  '',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '2'

        ));
        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'View Kit',
          'nav_tab_display_name' => 'View Kit',
          'nav_url' => 'viewproducts_kit',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  '',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '3'

        ));
        DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'Kit Inward',
          'nav_tab_display_name' => 'Kit Inward',
          'nav_url' => 'inward_productskit',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  '',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));
         DB::table('home_navigations_datas')->insert(array(
        
          'home_navigation_id' => '4',
          'company_id' => '1',
          'nav_display_name' => 'View Kit Inward',
          'nav_tab_display_name' => 'View Kit Inward',
          'nav_url' => 'view_kitinward',
          'nav_icon_class' => 'icon dripicons-view-thumb',
          'nav_keywords'  =>  '',
          'option_view' => '1',
          'option_add' => '1',
          'option_edit' => '1',
          'option_delete' => '1',
          'option_export' => '1',
          'option_print' => '0',
          'option_upload' => '0',
          'is_active' => '1',
          'ordering' => '4'

        ));
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
