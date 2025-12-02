<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
 use Barryvdh\DomPDF\Facade\Pdf;
 use Spatie\Browsershot\Browsershot;

class MemberController extends Controller
{

    public function getIntroducer($id)
    {
        // 1️⃣ Prevent user from entering their own phone or ID
        $currentUserPhone = Session::get('app_user_phone');

        if ($id == $currentUserPhone) {
            return response()->json(['error' => 'You cannot use your own number as introducer']);
        }

        $introducer = DB::table('app_users')
            ->where('id', $id)
            ->orWhere('phone_number', $id)
            ->first();

        if (! $introducer) {
            return response()->json(['error' => 'Introducer not found']);
        }

        return response()->json([
            // 'introducer_id_hidden' => $introducer->id,
            'name'       => $introducer->app_u_name,
            'phone'      => $introducer->phone_number,
            // 'select_plan_name'  => $introducer->select_plan_name,
            // 'select_plan_id'  => $introducer->select_plan_id,
            'address'    => $introducer->app_u_address,
            'wallet_bal' => $introducer->user_wallet,
            // 'position' => null // no position field exists in this table
        ]);
    }

    // Show Add Form
    public function adminCreate()
    {
        $last   = Member::orderBy('id', 'desc')->first();
        $nextId = str_pad(($last ? $last->id + 1 : 1), 7, '0', STR_PAD_LEFT); // e.g. 0000007

        if (request()->routeIs('addAdmin.adminCreate')) {
            return view('admin.logicApp.addAdmin', [
                'nextId'  => $nextId,
                'company' => null,
                'isEdit'  => false,
            ]);
        }
        // return view('admin.member_join', compact('nextId', 'memberJoinDropDpwn'));
    }

    // Store New Company
    public function adminStore(Request $request)
    {
        $request->validate([
            'member_id'    => 'required|unique:members,member_id',
            'CompanyName'  => 'required',
            'phone'        => 'required|unique:members,phone',
            'qrCodeUpload' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('qrCodeUpload')) {
            $file     = $request->file('qrCodeUpload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/qr_company'), $filename);
            $filePath = 'uploads/qr_company/' . $filename;
        }

        $joinDate = Carbon::now();

        Member::create([
            'member_id'    => $request->member_id,
            'name'         => $request->CompanyName,
            'phone'        => $request->phone,
            'password'     => Hash::make('abc11'),
            'email'        => $request->email,
            'address'      => $request->address,
            'pincode'      => $request->pincode,
            'state'        => $request->state,
            'cin_no'       => $request->cin_no,
            'BankName'     => $request->BankName,
            'BankACNo'     => $request->BankACNo,
            'BankIFSC'     => $request->BankIFSC,
            'upiId'        => $request->upiId,
            'qrCodeUpload' => $filePath,
            'join_date'    => $joinDate,
            'expiry_date'  => '2025-07-18',
            // 'status' => Active= 1, Deactive =2, Pending = 3	,
            'status'       => 2,
        ]);

        if (request()->routeIs('addAdmin.adminStore')) {
            return back()->with('success', 'Registration successful!');
        }

        return back()->with('success', 'Company added Successful.');
    }

    // Show Edit Form
    public function adminEdit($id)
    {
        $company = Member::findOrFail($id);
        return view('admin.logicApp.addAdmin', [
            'company' => $company,
            'nextId'  => $company->member_id,
            'isEdit'  => true,
        ]);
    }

    // Update Existing Company

    public function adminUpdate(Request $request, $id)
    {
        $company = Member::findOrFail($id);

        $request->validate([
            'CompanyName'    => 'required',
            'phone'          => 'required|unique:members,phone,' . $id,
            'qrCodeUpload'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'password'       => 'nullable|string|min:4', // Optional password update
            'company_status' => 'required|in:1,2,3',
        ]);

        // Handle QR file
        $filePath = $company->qrCodeUpload;
        if ($request->hasFile('qrCodeUpload')) {
            $file     = $request->file('qrCodeUpload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/qr_company'), $filename);
            $filePath = 'uploads/qr_company/' . $filename;
        }

        // Prepare update data
        $updateData = [
            'name'         => $request->CompanyName,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'pincode'      => $request->pincode,
            'state'        => $request->state,
            'cin_no'       => $request->cin_no,
            'BankName'     => $request->BankName,
            'BankACNo'     => $request->BankACNo,
            'BankIFSC'     => $request->BankIFSC,
            'upiId'        => $request->upiId,
            'qrCodeUpload' => $filePath,
            'status'       => $request->company_status,
        ];

        // If password is filled, update it
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $company->update($updateData);

        return back()->with('success', 'Company updated successfully!');
    }

    // ******************************************

    public function viewAdminsList()
    {
        // $getCompany  = DB::table('members')->get();

        $getCompany = Member::where('member_id', '!=', '0000001')->get();

        // $compantList= DB::table('plan_name_master')->get();

        // return view('admin.plan_master', compact('plans', 'planNames'));

        return view('admin.logicApp.viewAdminsList', compact('getCompany'));

    }

// **************************************************

    public function allMembersList()
    {
        return view('admin.allViewTables.allMembersList');
    }

// ****************************************

// ****************************************Dashbord

    public function index()
    {
        $stats = true;

        return view('admin.logicApp.dashboard', compact('stats'));
    }

    public function uIndex()
    {
        $stats = true;

        return view('admin.users.uDashboard', compact('stats'));
    }

    // ===========================
// BUSINESS PLAN FUNCTIONS
// ===========================

    public function businessPlanAdd()
    {
        $userId  = Session::get('app_user_id');
        $appUser = DB::table('app_users')->where('id', $userId)->first();

        // Get categories already used by this user
        $usedCategoryIds = DB::table('business_plans')
            ->where('user_by', $userId)
            ->pluck('business_category_id')
            ->toArray();

        // Get categories EXCEPT the ones already used
        $categories = DB::table('business_category')
            ->whereNotIn('id', $usedCategoryIds)
            ->get();

        return view('admin.users.businessPlanAdd', [
            'appUser'    => $appUser,
            'categories' => $categories,
            'plan'       => null,
            'isEdit'     => false,
        ]);
    }

    public function businessPlanStore(Request $request)
    {
        $userId   = Session::get('app_user_id');
        $appUser  = DB::table('app_users')->where('id', $userId)->first();
        $category = DB::table('business_category')->where('id', $request->business_category_id)->first();

        // Prevent inserting duplicate category for same user
        $exists = DB::table('business_plans')
            ->where('user_by', $userId)
            ->where('business_category_id', $request->business_category_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This category is already added for this user.');
        }

        DB::table('business_plans')->insert([
            'user_by'                => $userId,                           // FK to app_users.id, From Session::get(app_user_id)
            'add_user_name'          => $appUser->app_u_name ?? 'Unknown', // Fetched from app_users table
            'business_category_id'   => $category->id,                     // ID from business_category table
            'business_category_name' => $category->category_name,          // Name from business_category table
            'off_day'                => $request->off_day,                 // Name from off_day
            'loan_amount'            => $request->loan_amount,             // Loan amount requested
            'extra_amount'           => $request->extra_amount,            // Any extra amount
            'number_of_days'         => $request->number_of_days,          // Number of days for loan
            'membership_per'         => $request->membership_per,          // Membership %
            'membership_charge'      => $request->membership_charge,       // Membership charge
            'emi_amount'             => $request->emi_amount,              // EMI amount
            'processing_charge'      => $request->processing_charge,       // Processing fee
            'loan_insurance_charge'  => $request->loan_insurance_charge,   // Loan insurance charge
            'other_charges'          => $request->other_charges,           // Any other charges
            'interest_amount'        => $request->interest_amount,         // Interest amount
            'interest_rate'          => $request->interest_rate,           // Interest rate
            'final_amount'           => $request->final_amount,            // Final amount to be paid
            'status'                 => 1,                                 // 0=Inactive, 1=Active
            'created_at'             => now(),                             // Record created at
            'updated_at'             => now(),                             // Record updated at
        ]);

        return redirect()->route('business.plan.add')->with('success', 'Business Plan Added Successfully!');
    }

    public function businessPlanEdit($id)
    {
        $userId     = Session::get('app_user_id');
        $appUser    = DB::table('app_users')->where('id', $userId)->first();
        $categories = DB::table('business_category')->get();
        $plan       = DB::table('business_plans')->where('id', $id)->first();

        if (! $plan) {
            return redirect()->back()->with('error', 'Plan not found!');
        }

        return view('admin.users.businessPlanAdd', [
            'appUser'    => $appUser,
            'categories' => $categories,
            'plan'       => $plan,
            'isEdit'     => true,
        ]);
    }

    public function businessPlanUpdate(Request $request, $id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return redirect()->back()->with('error', 'Plan not found!');
        }

        $category = DB::table('business_category')->where('id', $request->business_category_id)->first();

        // Prevent inserting duplicate category for same user
        /*      $exists = DB::table('business_plans')
            ->where('user_by',$id)
            ->where('business_category_id', $request->business_category_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This category is already added for this user.');
        } */

        DB::table('business_plans')->where('id', $id)->update([
            'business_category_id'   => $category->id,            // ID from business_category table
            'business_category_name' => $category->category_name, // Name from business_category table

            'loan_amount'            => $request->loan_amount,           // Loan amount requested
            'extra_amount'           => $request->extra_amount,          // Any extra amount
            'number_of_days'         => $request->number_of_days,        // Number of days for loan
            'membership_per'         => $request->membership_per,        // Membership %
            'membership_charge'      => $request->membership_charge,     // Membership charge
            'emi_amount'             => $request->emi_amount,            // EMI amount
            'processing_charge'      => $request->processing_charge,     // Processing fee
            'loan_insurance_charge'  => $request->loan_insurance_charge, // Loan insurance charge
            'other_charges'          => $request->other_charges,         // Any other charges
            'interest_amount'        => $request->interest_amount,       // Interest amount
            'interest_rate'          => $request->interest_rate,         // Interest rate
            'final_amount'           => $request->final_amount,          // Final amount to be paid
            'status'                 => 1,                               // 0=Inactive, 1=Active
            'updated_at'             => now(),                           // Record updated at
        ]);

        return redirect()->route('business.plan.view')->with('success', 'Business Plan Updated Successfully!');
    }

    public function businessPlanView()
    {
        $userId   = session('app_user_id');
        $userName = DB::table('app_users')->where('id', $userId)->value('app_u_name');
        $plans    = DB::table('business_plans')->where('user_by', $userId)->orderByDesc('id')->get();
        return view('admin.users.businessPlanView', compact('plans', 'userName'));
    }

    public function businessPlanToggle($id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return back()->with('error', 'Plan not found.');
        }

        $newStatus = $plan->status == 1 ? 0 : 1;
        DB::table('business_plans')->where('id', $id)->update(['status' => $newStatus]);

        return back()->with('success', 'Plan status updated successfully.');
    }

    public function businessPlanDelete($id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return back()->with('error', 'Plan not found.');
        }

        DB::table('business_plans')->where('id', $id)->delete();

        return back()->with('success', 'Business Plan deleted successfully.');
    }

    // === DAILY UPDATE ===
// show blank form for creating

    public function registerUserApp(Request $request)
    {
        // Validate input
        $request->validate([
            'MobailNumber' => 'required|string|max:20|unique:app_users,phone_number',
        ]);

        // File upload helper
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = $request->phone_number . '_' . $prefix . '_' . $file->getClientOriginalName();
                $file->move(public_path("uploads/$folder"), $filename);
                return "uploads/$folder/" . $filename;
            }
            return null;
        };

        $profilePicPath = $uploadFile($request, 'profile_picture', 'qr_user', 'profile');
        $qrCodePath     = $uploadFile($request, 'upi_qr_code', 'qr_user', 'qr');

        DB::table('app_users')->insert([
            'app_u_name'             => $request->CompanyName,       // Company Name
            'cin_no'                 => $request->CompanyCIN,        // CIN Number
            'pan_number'             => $request->pan_number,        // PAN
            'phone_number'           => $request->MobailNumber,      // Mobile Number
            'user_email'             => $request->user_email,        // Email
            'app_u_address'          => $request->user_address,      // Address
            'police_station'         => $request->PoliceStation,     // Police Station
            'user_district'          => $request->user_district,     // District
            'user_state'             => $request->user_state,        // State
            'pin_code'               => $request->pin_code,          // Pin Code
            'contact_person_no'      => $request->contact_person_no, // Contact Person

                                                                 // Profile Picture
            'user_pic_img'           => $profilePicPath ?? null, // Profile Picture Path

            // 1st Bank Info
            'bank_name'              => $request->bank_name,
            'bank_account_no'        => $request->bank_account_no,
            'ifsc_code'              => $request->ifsc_code,
            'upi_id'                 => $request->upi_id,

            // 2nd Bank Info
            'second_bank_name'       => $request->second_bank_name,
            'second_bank_account_no' => $request->second_bank_account_no,
            'second_ifsc_code'       => $request->second_ifsc_code,
            'second_upi_id'          => $request->second_upi_id,

            'upi_qr_code'            => $qrCodePath ?? null, // QR Code
            'password'               => Hash::make('0011'),  // Default Password
            'status'                 => 1,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        return redirect()->route('user.company.add')->with('success', '<h3 style="color:#fff;"> Registered Successfully.<br> Login User Name = ' . $request->phone_number . '<br>Login Password Is = 0011</h3>');
    }

    public function companyUpdateUpdate(Request $request, $id)
    {
        // File upload helper
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);

                // Use mobile number safely
                $mobile = $request->MobailNumber ?? 'user';

                $filename = $mobile . '_' . $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("uploads/$folder"), $filename);

                return "uploads/$folder/" . $filename;
            }
            return null;
        };

        // Uploads
        $profilePicPath = $uploadFile($request, 'profile_picture', 'qr_user', 'profile');
        $qrCodePath     = $uploadFile($request, 'upi_qr_code', 'qr_user', 'qr');

        // Prepare update data
        $updateData = [
            'app_u_name'             => $request->CompanyName,
            'cin_no'                 => $request->CompanyCIN,
            'pan_number'             => $request->pan_number,
            'phone_number'           => $request->MobailNumber,
            'user_email'             => $request->user_email,
            'app_u_address'          => $request->user_address,
            'police_station'         => $request->PoliceStation,
            'user_district'          => $request->user_district,
            'user_state'             => $request->user_state,
            'pin_code'               => $request->pin_code,
            'contact_person_no'      => $request->contact_person_no,

            // Bank Information
            'bank_name'              => $request->bank_name,
            'bank_account_no'        => $request->bank_account_no,
            'ifsc_code'              => $request->ifsc_code,
            'upi_id'                 => $request->upi_id,

            // Second Bank Info
            'second_bank_name'       => $request->second_bank_name,
            'second_bank_account_no' => $request->second_bank_account_no,
            'second_ifsc_code'       => $request->second_ifsc_code,
            'second_upi_id'          => $request->second_upi_id,

            'updated_at'             => now(),
        ];

        // Update profile picture only if uploaded
        if ($profilePicPath) {
            $updateData['user_pic_img'] = $profilePicPath;
        }

        // Update QR code only if uploaded
        if ($qrCodePath) {
            $updateData['upi_qr_code'] = $qrCodePath;
        }

        // Run Update
        DB::table('app_users')->where('id', $id)->update($updateData);

        return redirect()
            ->route('user.company.view')
            ->with('success', 'Updated successfully.');
    }

    public function appUsersAdminPanelList(Request $request)
    {

        $query = \DB::table('app_users')->where('id', '!=', 1);

        if ($request->has('phone') && ! empty($request->phone)) {
            $query->where('phone_number', $request->phone);
        }

        $appUsers = $query->orderBy('id', 'desc')->get();

        return view('admin.logicApp.appUsers', compact('appUsers'));

    }

    public function addUserCompany()
    {

        return view('admin.logicApp.addAppUsers', [

            'isEdit' => false,
        ]);
    }

    public function editUserCompany($id)
    {
        $update = DB::table('app_users')->where('id', $id)->first();
        $isEdit = true;

        return view('admin.logicApp.addAppUsers', compact('update', 'isEdit'));
    }


       public function adminLoginAsUser($userId)
    {
        $user = DB::table('app_users')->where('id', $userId)->first();

        if (! $user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Set session variables to simulate login
        session([
            'app_user_id'     => $user->id,
            'app_user_name'   => $user->app_u_name,
            'app_user_wallet' => $user->user_wallet,
            'app_user_phone'  => $user->phone_number,
        ]);

        return redirect()->route('user.dashboard')->with('success', '🔑 You are now logged in as: ' . $user->app_u_name);
    }
// === DAILY UPDATE ===

    public function storeWeeklyUpdate(Request $request)
    {
        $userId = session('app_user_id');
        if (! $userId) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::table('weekly_update')->insert([
                'user_by'     => $userId,
                'weekly_from' => $request->weekly_from,
                'weekly_to'   => $request->weekly_to,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return redirect()->route('daily.update.add')->with('success', 'Weekly update saved successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with('error', "Duplicate weekly range: {$request->weekly_from} - {$request->weekly_to}");
            }
            return redirect()->back()->with('error', 'Database error.');
        }
    }

    private function buildDailyUpdateData()
    {
        $userId = session('app_user_id');
        if (! $userId) {
            abort(403, 'Unauthorized');
        }

        // Get all plans
        $plans = DB::table('business_plans')
            ->where('user_by', $userId)
            ->select('id', 'business_category_id', 'business_category_name', 'off_day')
            ->get();

        /** ----------------------------
         *  DAILY PLAN
         *-----------------------------*/

        $offDays      = [];
        $hasDailyPlan = false;

        $dailyPlan = $plans->firstWhere('business_category_name', 'Daily');

        if ($dailyPlan) {
            $hasDailyPlan = true;

            if (! empty($dailyPlan->off_day)) {
                $offDays = array_map('trim', explode(',', $dailyPlan->off_day));
            }
        }

        /** ----------------------------
         *  WEEKLY PLAN
         *-----------------------------*/

        $hasWeeklyPlan    = false;
        $weeklyUpdates    = [];
        $monthWeeklyDates = [];

        $weeklyPlan = $plans->firstWhere('business_category_name', 'Weekly');

        $currentMonth = now()->month;
        $currentYear  = now()->year;

        if ($weeklyPlan) {
            $hasWeeklyPlan = true;

            $weeklyUpdates = DB::table('weekly_update')
                ->where('user_by', $userId)
                ->whereYear('weekly_from', $currentYear)
                ->whereMonth('weekly_from', $currentMonth)
                ->get();

            // Build month weekly dates with default danger status
            $daysInMonth = now()->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = \Carbon\Carbon::create($currentYear, $currentMonth, $day);

                $monthWeeklyDates[] = [
                    'date'     => $date->format('Y-m-d'),
                    'day_name' => $date->format('l'),
                    'status'   => 'danger',
                ];
            }

            // Mark weekly ranges as primary
            foreach ($weeklyUpdates as $update) {
                $from = \Carbon\Carbon::parse($update->weekly_from);
                $to   = \Carbon\Carbon::parse($update->weekly_to);

                foreach ($monthWeeklyDates as &$day) {
                    if (\Carbon\Carbon::parse($day['date'])->between($from, $to)) {
                        $day['status'] = 'primary';
                    }
                }
            }
        }

        /** ----------------------------
         *  DAILY EXISTING DATES
         *-----------------------------*/

        $existingDates = [];
        $monthDates    = [];

        if ($hasDailyPlan) {
            $existingDates = DB::table('daily_update')
                ->where('user_by', $userId)
                ->whereYear('date_entry', $currentYear)
                ->whereMonth('date_entry', $currentMonth)
                ->pluck('date_entry')
                ->map(fn($d) => date('Y-m-d', strtotime($d)))
                ->toArray();

            $daysInMonth = now()->daysInMonth;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = \Carbon\Carbon::create($currentYear, $currentMonth, $day);

                $monthDates[] = [
                    'date'     => $date->format('Y-m-d'),
                    'day_name' => $date->format('l'),
                ];
            }
        }

        // *************RD Not SHOW*************

        $rdPlan = DB::table('business_plans_rd')
            ->where('user_by', $userId)
            ->first();

        $showRdSection = $rdPlan && $rdPlan->rd_amount > 0;

        // ****************************

        $hasDaily    = $plans->contains('business_category_id', 1);
        $hasWeekly   = $plans->contains('business_category_id', 2);
        $hasBiWeekly = $plans->contains('business_category_id', 3);
        $hasMonthly  = $plans->contains('business_category_id', 4);

        return [
            'plans'            => $plans,
            'hasDailyPlan'     => $hasDailyPlan,
            'offDays'          => $offDays,
            'existingDates'    => $existingDates,
            'monthDates'       => $monthDates,
            'hasWeeklyPlan'    => $hasWeeklyPlan,
            'weeklyUpdates'    => $weeklyUpdates,
            'monthWeeklyDates' => $monthWeeklyDates,
            'showRdSection'    => $showRdSection,
            'rdPlan'           => $rdPlan,
            'hasDaily'         => $hasDaily,
            'hasWeekly'        => $hasWeekly,
            'hasBiWeekly'      => $hasBiWeekly,
            'hasMonthly'       => $hasMonthly,
        ];

    }

    public function dailyUpdateAdd()
    {
        $data           = $this->buildDailyUpdateData();
        $data['isEdit'] = false;
        $data['update'] = null;

        return view('admin.users.dailyUpdateAdd', $data);
    }

    public function dailyUpdateEdit($id)
    {
        $data = $this->buildDailyUpdateData();

        $data['isEdit'] = true;
        $data['update'] = DB::table('daily_update')->where('id', $id)->first();
        $data['editId'] = $id;

        return view('admin.users.dailyUpdateAdd', $data);
    }

    public function dailyUpdateStore(Request $request)
    {
        $userId  = Session::get('app_user_id');
        $appUser = DB::table('app_users')->where('id', $userId)->first();
        $plan    = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('daily_update')->insert([
            'user_by'                    => $userId,                           // User ID from session or request
            'add_user_name'              => $appUser->app_u_name ?? 'Unknown', // User name or default 'Unknown'
            /* 'month_name'                 => $request->month_name, */
            'date_entry'                 => $request->date_entry,                     // Date from input field
            'today_emi'                  => $request->today_emi,                      // EMI collection
            'PreviousCarrentBalance'     => $request->PreviousCarrentBalance,         // Previous current balance
            'PreviousRDBalance'          => $request->PreviousRDBalance ?? 0,         // Previous RD balance
            'AvailableFund'              => $request->AvailableFund,                  // Available fund
            'today_close_customers'      => $request->today_close_customers,          // Number of closed customers
            'today_new_customers'        => $request->today_new_customers,            // New customers today
            'total_daily_colletion'      => $request->total_daily_colletion ?? 0,     // Daily collection loan
            'total_weekly_colletion'     => $request->total_weekly_colletion ?? 0,    // Weekly collection loan
            'total_bi_weekly_colletion'  => $request->total_bi_weekly_colletion ?? 0, // Bi-weekly collection loan
            'total_monthly_colletion'    => $request->total_monthly_colletion ?? 0,   // Monthly collection loan
            'InvestmentAmount'           => $request->InvestmentAmount,               // Investment amount
            'today_loan_in_ac'           => $request->today_loan_in_ac,               // Loan in account today
            'today_loan_in_cash'         => $request->today_loan_in_cash,             // Loan in cash today
            'today_total_loan_amount'    => $request->today_total_loan_amount,        // Total loan amount today
            'today_closing_balance_ac'   => $request->today_closing_balance_ac,       // Closing balance in A/C
            'today_closing_balance_cash' => $request->today_closing_balance_cash,     // Closing balance in Cash
            'current_balance'            => $request->current_balance,                // Current balance in hand and account
            'rd_amount'                  => $request->rd_amount ?? 0,
            'rd_withdrawal'              => $request->rd_withdrawal ?? 0,
            'rd_interest'                => $request->rd_interest ?? 0,
            'created_at'                 => now(), // Timestamp for creation
            'updated_at'                 => now(), // Timestamp for update
        ]);

        return redirect()->route('daily.update.add')->with('success', 'Daily update saved successfully.');
    }

    public function dailyUpdateView()
    {
        $userId   = session('app_user_id');
        $userName = DB::table('app_users')
            ->where('id', $userId)
            ->value('app_u_name');

        $currentYear = now()->year;

        $updates = DB::table('daily_update')
            ->where('user_by', $userId)
            ->whereYear('date_entry', $currentYear) // <-- FILTER BY CURRENT YEAR
            ->orderByDesc('id')
            ->get();

        return view('admin.users.dailyUpdateView', [
            'updates'  => $updates,
            'userName' => $userName,
        ]);
    }

    public function dailyUpdateUpdate(Request $request, $id)
    {
        $plan = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('daily_update')->where('id', $id)->update([

            'date_entry'                 => $request->date_entry,                 // Date of this daily update
            'today_emi'                  => $request->today_emi,                  // EMI collected today
            'PreviousCarrentBalance'     => $request->PreviousCarrentBalance,     // Previous current balance
            'PreviousRDBalance'          => $request->PreviousRDBalance,          // Previous RD balance
            'AvailableFund'              => $request->AvailableFund,              // Available fund
            'today_close_customers'      => $request->today_close_customers,      // Number of customers closed today
            'today_new_customers'        => $request->today_new_customers,        // Number of new customers today
            'total_daily_colletion'      => $request->total_daily_colletion,      // Total daily collection
            'total_weekly_colletion'     => $request->total_weekly_colletion,     // Total weekly collection
            'total_bi_weekly_colletion'  => $request->total_bi_weekly_colletion,  // Total bi-weekly collection
            'total_monthly_colletion'    => $request->total_monthly_colletion,    // Total monthly collection
            'InvestmentAmount'           => $request->InvestmentAmount,           // Investment amount
            'today_loan_in_ac'           => $request->today_loan_in_ac,           // Loan received in account today
            'today_loan_in_cash'         => $request->today_loan_in_cash,         // Loan received in cash today
            'today_total_loan_amount'    => $request->today_total_loan_amount,    // Total loan amount received today
            'today_closing_balance_ac'   => $request->today_closing_balance_ac,   // Closing account balance today
            'today_closing_balance_cash' => $request->today_closing_balance_cash, // Closing cash balance today
            'rd_amount'                  => $request->rd_amount,
            'rd_withdrawal'              => $request->rd_withdrawal,
            'rd_interest'                => $request->rd_interest,
            'current_balance'            => $request->current_balance, // Current total balance
            'updated_at'                 => now(),                     // Record updated at
        ]);

        return redirect()->route('daily.update.view')->with('success', 'Daily update updated successfully.');
    }

// === Monthly UPDATE ===

    public function monthlyUpdateAdd()
    {
        $userId = session('app_user_id');

        // Get only business plans created by this user
        $plans = DB::table('business_plans')
            ->where('user_by', $userId)
            ->select('id', 'business_category_name')
            ->get();

        $isEdit = false;

        return view('admin.users.monthlyUpdateAdd', compact('plans', 'isEdit'));
    }

    public function monthlyUpdateStore(Request $request)
    {
        $userId  = Session::get('app_user_id');
        $appUser = DB::table('app_users')->where('id', $userId)->first();
        $plan    = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('monthly_update')->insert([
            'user_by'                 => $userId,
            'add_user_name'           => $appUser->app_u_name ?? 'Unknown',
            'month_name'              => $request->month_name,
            'director_loan'           => $request->director_loan,
            'bank_loan'               => $request->bank_loan,
            'investment_for_invertor' => $request->investment_for_invertor,
            'director_salary'         => $request->director_salary,
            'staff_salary'            => $request->staff_salary,
            'office_rent'             => $request->office_rent,
            'electricity_bill'        => $request->electricity_bill,
            'recharge_bill'           => $request->recharge_bill,
            'furniture_amount'        => $request->furniture_amount,
            'other_expences'          => $request->other_expences,
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        return redirect()->route('monthly.update.add')->with('success', 'Daily update saved successfully.');
    }

    public function monthlyUpdateView()
    {
        $userId   = session('app_user_id');
        $userName = DB::table('app_users')
            ->where('id', $userId)
            ->value('app_u_name');

        $currentYear = now()->year;

        $updates = DB::table('monthly_update')
            ->where('user_by', $userId)
            ->whereYear('created_at', $currentYear) // <-- Filter by current year
            ->orderByDesc('id')
            ->get();

        return view('admin.users.monthlyUpdateView', [
            'updates'  => $updates,
            'userName' => $userName,
        ]);
    }

    public function monthlyReportmonth()
    {
        $todayMonth = date('n'); // 1-12
        $todayYear  = date('Y');

        // Determine Financial Year (Apr → Mar)
        if ($todayMonth < 4) {
            $fyStartYear = $todayYear - 1;
            $fyEndYear   = $todayYear;
        } else {
            $fyStartYear = $todayYear;
            $fyEndYear   = $todayYear + 1;
        }

        // Month labels
        $monthNames = [
            1  => 'Jan', 2  => 'Feb', 3  => 'Mar',
            4  => 'Apr', 5  => 'May', 6  => 'Jun',
            7  => 'Jul', 8  => 'Aug', 9  => 'Sep',
            10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
        ];

        $months = [];

        // April → December
        for ($m = 4; $m <= 12; $m++) {
            $months[] = [
                'month'  => $m,
                'text'   => $monthNames[$m],
                'year'   => $fyStartYear,
                'format' => sprintf('%04d-%02d', $fyStartYear, $m), // YYYY-MM
            ];
        }

        // January → March
        for ($m = 1; $m <= 3; $m++) {
            $months[] = [
                'month'  => $m,
                'text'   => $monthNames[$m],
                'year'   => $fyEndYear,
                'format' => sprintf('%04d-%02d', $fyEndYear, $m), // YYYY-MM
            ];
        }

                              // Fetch all months for this user
        $userId          = Session::get('app_user_id'); // or use auth()->id() if logged in
        $availableMonths = \DB::table('monthly_update')
            ->where('user_by', $userId)
            ->pluck('month_name') // ['2025-07', '2025-08', '2025-12']
            ->toArray();

        // Convert all months to string format just in case
        $availableMonths = array_map('strval', $availableMonths);

        return view('admin.users.monthlyReportsView', compact('months', 'availableMonths','userId'));
    }

    public function monthlyUpdateEdit($id)
    {
        $update = DB::table('monthly_update')->where('id', $id)->first();
        $plans  = DB::table('business_plans')->select('id', 'business_category_name')->get();
        $isEdit = true;

        return view('admin.users.monthlyUpdateAdd', compact('update', 'plans', 'isEdit'));
    }

    public function monthlyUpdateUpdate(Request $request, $id)
    {

        DB::table('monthly_update')->where('id', $id)->update([
            // 'month_name'              => $request->month_name,
            'director_loan'           => $request->director_loan,
            'bank_loan'               => $request->bank_loan,
            'investment_for_invertor' => $request->investment_for_invertor,
            'director_salary'         => $request->director_salary,
            'staff_salary'            => $request->staff_salary,
            'office_rent'             => $request->office_rent,
            'electricity_bill'        => $request->electricity_bill,
            'recharge_bill'           => $request->recharge_bill,
            'furniture_amount'        => $request->furniture_amount,
            'other_expences'          => $request->other_expences,
            'updated_at'              => now(),
        ]);

        return redirect()->route('monthly.update.view')->with('success', 'Monthly update updated successfully.');
    }

// === RD Add ===

    public function businessPlanAddRd()
    {
        $userId = Session::get('app_user_id');
        $plan   = DB::table('business_plans_rd')->where('user_by', $userId)->first();

        return view('admin.users.businessPlanAddRd', [
            'plan'   => $plan,
            'isEdit' => ! empty($plan),
        ]);
    }

    public function businessPlanStoreRd(Request $request)
    {
        $userId = Session::get('app_user_id');

        DB::table('business_plans_rd')->updateOrInsert(
            ['user_by' => $userId],
            [
                'rd_amount'     => $request->rd_amount,
                'rd_interest'   => $request->rd_interest,
                'add_user_name' => DB::table('app_users')->where('id', $userId)->value('app_u_name'),
                'status'        => 1,
                'updated_at'    => now(),
                'created_at'    => now(),
            ]
        );

        return back()->with('success', 'RD Plan Saved!');
    }

// ******************************************************************

    private function calculateToNetSurplusTwo($value)
    {
        if ($value <= 0) {
            return 0;
        }
        if ($value <= 5000) {
            return 50000;
        }

        if ($value <= 10000) {
            return 100000;
        }

        if ($value <= 15000) {
            return 150000;
        }

        if ($value <= 20000) {
            return 200000;
        }

        if ($value <= 30000) {
            return 300000;
        }

        if ($value <= 40000) {
            return 400000;
        }

        if ($value <= 50000) {
            return 500000;
        }

        return 600000;
    }

    public function monthlyReport(Request $request, $monthYear = null, $userId = null)
    {
        // Use provided userId or session user
        $userId = $userId ?? Session::get('app_user_id');

        // Parse monthYear parameter:
        // Accept '11-2025' or '2025-11' or null (default current month)
        if ($monthYear) {
            if (preg_match('/^\d{1,2}-\d{4}$/', $monthYear)) { // 11-2025
                [$month, $year] = explode('-', $monthYear);

            } elseif (preg_match('/^\d{4}-\d{1,2}$/', $monthYear)) { // 2025-11
                [$year, $month] = explode('-', $monthYear);
            } else {
                // fallback to current if malformed
                $month = date('m');
                $year  = date('Y');
            }
        } else {
            $month = date('m');
            $year  = date('Y');
        }

        // normalize as integers
        $month = (int) $month;
        $year  = (int) $year;

        // Validate month
        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }

        // Fetch data
        $data = $this->getMonthlyReportData($userId, $year, $month);

        $appUser = DB::table('app_users')->where('id', $userId)->first(); // Fetch user details

        if ($request->is('*/api/*') || $request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.reports.monthlyReports', compact('appUser', 'year', 'month') + $data);
    }

   

public function monthlyReportPDF(Request $request, $monthYear = null, $userId = null)
{
    // Use existing logic of monthlyReport()
    $userId = $userId ?? Session::get('app_user_id');

    if ($monthYear) {
        if (preg_match('/^\d{1,2}-\d{4}$/', $monthYear)) {
            [$month, $year] = explode('-', $monthYear);
        } elseif (preg_match('/^\d{4}-\d{1,2}$/', $monthYear)) {
            [$year, $month] = explode('-', $monthYear);
        } else {
            $month = date('m');
            $year  = date('Y');
        }
    } else {
        $month = date('m');
        $year  = date('Y');
    }

    $month = (int) $month;
    $year  = (int) $year;

    $data = $this->getMonthlyReportData($userId, $year, $month);

    $appUser = DB::table('app_users')->where('id', $userId)->first();

    // Load the same Blade view but convert to PDF
    $pdf = Pdf::loadView('admin.reports.monthlyReports', 
            compact('appUser', 'year', 'month') + $data
        )->setPaper('a4', 'portrait');

    return $pdf->download("Monthly_Report_{$month}_{$year}.pdf");
}









/**
 * getMonthlyReportData - returns all keys used in your previous implementation
 *
 * @param int $userId
 * @param int $year
 * @param int $month
 * @return array
 */
    private function getMonthlyReportData($userId, $year, $month)
    {
        // Bind params convenience
        $dailyParams = [$userId, $month, $year];

        // $monthlyParams = [$userId, $month, $year];

        // Create month_name value like "2025-08"
        $monthName = sprintf('%04d-%02d', $year, $month);

        $monthlyParams = [$userId, $monthName];

        // 1) Aggregates from daily_update (filtered by date_entry month/year)
        $dailyAggSql = "
        SELECT
            COALESCE(SUM(today_emi), 0) AS toCreditEMI,

            COALESCE(SUM(total_daily_colletion),0) AS daily_collection_loan,
            COALESCE(SUM(total_weekly_colletion),0) AS weekly_collection_loan,
            COALESCE(SUM(total_bi_weekly_colletion),0) AS bi_weekly_collection_loan,
            COALESCE(SUM(total_monthly_colletion),0) AS monthly_collection_loan,

            COALESCE(SUM(rd_amount),0) AS total_rd_amount,
            COALESCE(SUM(rd_withdrawal),0) AS fund_saving_withdraw,
            COALESCE(SUM(rd_interest),0) AS total_rd_interest,

            COALESCE(SUM(today_closing_balance_ac),0) AS closing_balance_bank,
            COALESCE(SUM(today_closing_balance_cash),0) AS cash_in_hand,

            COALESCE(SUM(total_daily_colletion + total_weekly_colletion + total_bi_weekly_colletion + total_monthly_colletion) * 0.01, 0) AS processing_charge,
            COALESCE(SUM(total_daily_colletion + total_weekly_colletion + total_bi_weekly_colletion + total_monthly_colletion) * 0.02, 0) AS insurance_charge
        FROM daily_update
        WHERE user_by = ?
          AND MONTH(date_entry) = ?
          AND YEAR(date_entry) = ?
             ";

        $dailyAgg = DB::selectOne($dailyAggSql, $dailyParams);

        $monthlyAggSql = "
        SELECT
            COALESCE(SUM(director_loan),0) AS TotalDirectorLoan,
            COALESCE(SUM(bank_loan),0) AS TotalBankLoan_only,
            COALESCE(SUM(investment_for_invertor),0) AS TotalInvestment_for_investor,

            COALESCE(SUM(director_salary),0) AS director_salary,
            COALESCE(SUM(staff_salary),0) AS staff_salary,
            COALESCE(SUM(other_expences) * 0.01,0) AS staff_uniform_id_card,
            COALESCE(SUM(other_expences) * 0.02,0) AS staff_training,
            COALESCE(SUM(other_expences) * 0.2,0) AS customer_awareness_camp,
            COALESCE(SUM(other_expences) * 0.22,0) AS cultural_programme,
            COALESCE(SUM(other_expences) * 0.35,0) AS social_welfare_activity,
            COALESCE(SUM(office_rent),0) AS office_rent,
            COALESCE(SUM(electricity_bill),0) AS electricity_bill,
            COALESCE(SUM(recharge_bill),0) AS internet_mobile_recharge,
            COALESCE(SUM(other_expences) * 0.017,0) AS marketing_cost,
            COALESCE(SUM(other_expences),0) AS total_other_expences
       FROM monthly_update
        WHERE user_by = ?
          AND month_name = ?
            ";

        $monthlyAgg = DB::selectOne($monthlyAggSql, $monthlyParams);

        $membershipSql = "
        SELECT
            COALESCE(SUM(CASE WHEN bp.business_category_name = 'Daily' THEN (du.total_daily_colletion * bp.membership_charge) / 10000 END), 0) AS Daily,
            COALESCE(SUM(CASE WHEN bp.business_category_name = 'Weekly' THEN (du.total_weekly_colletion * bp.membership_charge) / 10000 END), 0) AS Weekly,
            COALESCE(SUM(CASE WHEN bp.business_category_name = 'Bi-Weekly' THEN (du.total_bi_weekly_colletion * bp.membership_charge) / 10000 END), 0) AS BiWeekly,
            COALESCE(SUM(CASE WHEN bp.business_category_name = 'Monthly' THEN (du.total_monthly_colletion * bp.membership_charge) / 10000 END), 0) AS Monthly,
            (
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Daily' THEN (du.total_daily_colletion * bp.membership_charge) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Weekly' THEN (du.total_weekly_colletion * bp.membership_charge) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Bi-Weekly' THEN (du.total_bi_weekly_colletion * bp.membership_charge) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Monthly' THEN (du.total_monthly_colletion * bp.membership_charge) / 10000 END), 0)
            ) AS grand_total_amount
        FROM daily_update du
        LEFT JOIN business_plans bp ON bp.user_by = du.user_by
        WHERE du.user_by = ?
          AND MONTH(du.date_entry) = ?
          AND YEAR(du.date_entry) = ?
        GROUP BY du.user_by
        ";

        $grandTotalRowMembershipCharge = DB::selectOne($membershipSql, $dailyParams);

        // 4) interest amount on microfinance loan (similar structure to membership query)
        $interestSql = "
        SELECT
            (
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Daily' THEN (du.total_daily_colletion * bp.interest_amount) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Weekly' THEN (du.total_weekly_colletion * bp.interest_amount) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Bi-Weekly' THEN (du.total_bi_weekly_colletion * bp.interest_amount) / 10000 END), 0) +
                COALESCE(SUM(CASE WHEN bp.business_category_name = 'Monthly' THEN (du.total_monthly_colletion * bp.interest_amount) / 10000 END), 0)
            ) AS IntarestReceivedOnMicrofinanceLoan
        FROM daily_update du
        LEFT JOIN business_plans bp ON bp.user_by = du.user_by
        WHERE du.user_by = ?
          AND MONTH(du.date_entry) = ?
          AND YEAR(du.date_entry) = ?
        GROUP BY du.user_by
     ";

        $IntarestReceivedOnMicrofinanceLoan = DB::selectOne($interestSql, $dailyParams);

        // 5) fund saving RD query joining business_plans_rd
        $fundSavingSql = "
        SELECT
            COALESCE(SUM(du.rd_amount), 0) AS total_rd_amount,
            COALESCE(MAX(bp.rd_interest), 0) AS rd_interest,
            COALESCE(SUM(du.rd_amount) * MAX(bp.rd_interest) / 100, 0) AS fund_saving_amount
        FROM daily_update du
        LEFT JOIN business_plans_rd bp ON bp.user_by = du.user_by
        WHERE du.user_by = ?
          AND MONTH(du.date_entry) = ?
          AND YEAR(du.date_entry) = ?
        GROUP BY du.user_by
     ";
        $fundSavingRow = DB::selectOne($fundSavingSql, $dailyParams);

        $penaltySql = "
        SELECT
            COALESCE(SUM(other_expences), 0) AS total_other_expences,
            COALESCE(SUM(other_expences) * 0.015, 0) AS penalty
     FROM monthly_update
        WHERE user_by = ?
          AND month_name = ?
        GROUP BY user_by
     ";
        $penaltyRow = DB::selectOne($penaltySql, $monthlyParams);

        $othersSql = "
        SELECT
            COALESCE(SUM(other_expences), 0) AS total_other_expences,
            COALESCE(SUM(other_expences) * 0.025, 0) AS others
     FROM monthly_update
        WHERE user_by = ?
          AND month_name = ?
        GROUP BY user_by
        ";
        $othersRow = DB::selectOne($othersSql, $monthlyParams);

        $interestPaidOnLoanSql = "
        SELECT COALESCE(SUM(director_loan) * 0.02, 0) AS interest_paid_on_loan
        FROM monthly_update
        WHERE user_by = ?
          AND month_name = ?
        GROUP BY user_by
         ";
        $interestPaidOnLoanRow = DB::selectOne($interestPaidOnLoanSql, $monthlyParams);

        $otherChargesRowLoanTakenSql = "
        SELECT COALESCE((SUM(bank_loan) + SUM(investment_for_invertor)) * 0.02, 0) AS other_charges_paid_for_loan_taken
     FROM monthly_update
        WHERE user_by = ?
          AND month_name = ?
        GROUP BY user_by
      ";
        $otherChargesRowLoanTaken = DB::selectOne($otherChargesRowLoanTakenSql, $monthlyParams);

        // 8) paid insurance charge (we used insurance_charge above in dailyAgg)
        //    monthlyUpdateRow is already aggregated as $monthlyAgg

        // 9) balances (closing sums for the month)
        /* $balancesSql = "
        SELECT
            COALESCE(SUM(today_closing_balance_ac), 0) AS closing_balance_bank,
            COALESCE(SUM(today_closing_balance_cash), 0) AS cash_in_hand
        FROM daily_update
        WHERE user_by = ?
          AND MONTH(date_entry) = ?
          AND YEAR(date_entry) = ?
     ";
      $balances = DB::selectOne($balancesSql, $dailyParams); */

        // --------------------------------------------
        // PREVIOUS MONTH CALCULATION // ***********	Opening Balance**************
        // --------------------------------------------
        $givenMonth = (int) $month;
        $givenYear  = (int) $year;

        $prevMonth = $givenMonth - 1;
        $prevYear  = $givenYear;

        if ($prevMonth === 0) {
            $prevMonth = 12;
            $prevYear--;
        }

        // --------------------------------------------
        // GET LAST ENTRY OF PREVIOUS MONTH
        // --------------------------------------------
        $lastEntrySql = "
        SELECT MAX(date_entry) AS last_date
        FROM daily_update
        WHERE user_by = ?
          AND MONTH(date_entry) = ?
          AND YEAR(date_entry) = ?
        ";

        $lastEntry = DB::selectOne($lastEntrySql, [
            $userId,
            $prevMonth,
            $prevYear,
        ]);

        // Default object
        $balances = (object) [
            'closing_balance_bank' => 0,
            'cash_in_hand'         => 0,
        ];

        // --------------------------------------------
        // IF FOUND → GET BALANCE OF THAT LAST DATE
        // --------------------------------------------
        if ($lastEntry && $lastEntry->last_date) {

            $prevBalanceSql = "
        SELECT
            today_closing_balance_ac AS closing_balance_bank,
            today_closing_balance_cash AS cash_in_hand
        FROM daily_update
        WHERE user_by = ?
          AND date_entry = ?
        LIMIT 1
         ";

            $result = DB::selectOne($prevBalanceSql, [
                $userId,
                $lastEntry->last_date,
            ]);

            if ($result) {
                $balances = $result; // MERGED HERE ✔
            }
        }
        // ***********	Opening Balance**************

        // ------------------------------------------------------
        // CURRENT MONTH LAST ENTRY (Closing Balance - CURRENT)
        // Right Side → using $givenMonthRightSide, $givenYearRightSide
        // ------------------------------------------------------

        // --------------------- ensure these are defined ---------------------
        $givenMonthRightSide = isset($month) ? (int) $month : (int) date('m');
        $givenYearRightSide  = isset($year) ? (int) $year : (int) date('Y');

        // ------------------------------------------------------
        // CURRENT MONTH LAST ENTRY (Closing Balance - CURRENT)
        // Right Side → using $givenMonthRightSide, $givenYearRightSide
        // ------------------------------------------------------

        $currentLastEntrySql = "
        SELECT MAX(date_entry) AS last_date
        FROM daily_update
        WHERE user_by = ?
          AND MONTH(date_entry) = ?
          AND YEAR(date_entry) = ?
            ";

            $currentLastEntry = DB::selectOne($currentLastEntrySql, [
                $userId,
                $givenMonthRightSide,
                $givenYearRightSide,
            ]);

            // Default values
            $currentClosing = (object) [
                'closing_balance_bank_rightSide' => 0,
                'cash_in_hand_rightSide'         => 0,
            ];

            // If last entry exists → fetch closing values
            if ($currentLastEntry && $currentLastEntry->last_date) {

                $currentBalanceSql = "
            SELECT
                today_closing_balance_ac AS closing_balance_bank_rightSide,
                today_closing_balance_cash AS cash_in_hand_rightSide
            FROM daily_update
            WHERE user_by = ?
              AND date_entry = ?
            LIMIT 1
             ";

                $result = DB::selectOne($currentBalanceSql, [
                    $userId,
                    $currentLastEntry->last_date,
                ]);

                if ($result) {
                    $currentClosing = $result; // assign
                }
            }

            // usage
            // $closingBankRight = $currentClosing->closing_balance_bank_rightSide;
            // $closingCashRight  = $currentClosing->cash_in_hand_rightSide;

            // $closingBankRight = $currentClosing->closing_balance_bank_rightSide;
            // $closingCashRight = $currentClosing->cash_in_hand_rightSide;

            // Now build the left and right arrays exactly as earlier
        $leftSideSum = [
            'toCreditEMI'                        => $dailyAgg->toCreditEMI ?? 0,
            'short_term_borrowing'               => $monthlyAgg->TotalDirectorLoan ?? 0,
            'long_term_borrowing'                => (($monthlyAgg->TotalBankLoan_only ?? 0) + ($monthlyAgg->TotalInvestment_for_investor ?? 0)),
            'membership_charge'                  => $grandTotalRowMembershipCharge->grand_total_amount ?? 0,
            'processing_charge'                  => $dailyAgg->processing_charge ?? 0,
            'insurance_charge'                   => $dailyAgg->insurance_charge ?? 0,
            'IntarestReceivedOnMicrofinanceLoan' => $IntarestReceivedOnMicrofinanceLoan->IntarestReceivedOnMicrofinanceLoan ?? 0,
            'fund_saving_amount'                 => $fundSavingRow->total_rd_amount ?? 0,
            'penalty'                            => $penaltyRow->penalty ?? 0,
            'others'                             => $othersRow->others ?? 0,
        ];

        $rightSideSum = [
            'daily_collection_loan'             => $dailyAgg->daily_collection_loan ?? 0,
            'weekly_collection_loan'            => $dailyAgg->weekly_collection_loan ?? 0,
            'bi_weekly_collection_loan'         => $dailyAgg->bi_weekly_collection_loan ?? 0,
            'monthly_collection_loan'           => $dailyAgg->monthly_collection_loan ?? 0,
            'fund_saving_withdraw'              => $dailyAgg->fund_saving_withdraw ?? 0,
            'total_rd_interest'                 => $dailyAgg->total_rd_interest ?? 0,
            'interest_paid_on_loan'             => $interestPaidOnLoanRow->interest_paid_on_loan ?? 0,
            'other_charges_paid_for_loan_taken' => $otherChargesRowLoanTaken->other_charges_paid_for_loan_taken ?? 0,
            'paid_insurance_charge'             => $dailyAgg->processing_charge ?? 0/* NOTE: previous code used paid_insurance_charge separately, but processing/insurance handled earlier */,
            'director_salary'                   => $monthlyAgg->director_salary ?? 0,
            'staff_salary'                      => $monthlyAgg->staff_salary ?? 0,
            'staff_uniform_id_card'             => $monthlyAgg->staff_uniform_id_card ?? 0,
            'staff_training'                    => $monthlyAgg->staff_training ?? 0,
            'customer_awareness_camp'           => $monthlyAgg->customer_awareness_camp ?? 0,
            'cultural_programme'                => $monthlyAgg->cultural_programme ?? 0,
            'social_welfare_activity'           => $monthlyAgg->social_welfare_activity ?? 0,
            'office_rent'                       => $monthlyAgg->office_rent ?? 0,
            'electricity_bill'                  => $monthlyAgg->electricity_bill ?? 0,
            'internet_mobile_recharge'          => $monthlyAgg->internet_mobile_recharge ?? 0,
            'marketing_cost'                    => $monthlyAgg->marketing_cost ?? 0,
        ];

        // Opening balances (I use sums inside the same month - keep same names)
        $opening_cash_in_hand  = $balances->cash_in_hand ?? 0;
        $opening_cash_in_bank  = $balances->closing_balance_bank ?? 0;
        $total_opening_balance = (float) $opening_cash_in_hand + (float) $opening_cash_in_bank;

        // left/right totals + other_general_cost calculations (same algorithm as original)
        $lift_side_gran_total_balance = array_sum($leftSideSum) + (float) $total_opening_balance;

        $closingBankRight = $currentClosing->closing_balance_bank_rightSide;
        $closingCashRight = $currentClosing->cash_in_hand_rightSide;

        $total_cloes_balance = (float) $closingBankRight + (float) $closingCashRight;

        // Step 1: temporarily set to 0
        $other_general_cost = 0;

        // Step 2: calculate right side (initially without general cost)
        $right_side_round              = array_sum($rightSideSum) + $other_general_cost;
        $right_side_gran_total_balance = $right_side_round + $total_cloes_balance;

        // Step 3: now calculate the actual general cost difference
        $other_general_cost = $lift_side_gran_total_balance - $right_side_gran_total_balance;

        // Step 4: recalc right side including other_general_cost
        $right_side_round              = array_sum($rightSideSum) + $other_general_cost;
        $right_side_gran_total_balance = $right_side_round + $total_cloes_balance;

        // To Net Surplus
        $ToNetSurplus = array_sum($leftSideSum) - (array_sum($rightSideSum) + $other_general_cost);

        // Excess Income Over Expenditure (same as before)
        $ExcessIncomeOver = (array_sum($rightSideSum) + $other_general_cost) + $ToNetSurplus;

        // ToNetSurplus2 logic (original mapping)

        $ToNetSurplus2 = $this->calculateToNetSurplusTwo($ToNetSurplus);

        // Fixed assets distribution (same as original)
        $FixedAssetsFurniture = $ToNetSurplus2 * 0.4;
        $FixedAssetsComputer  = $ToNetSurplus2 * 0.2;
        $FixedAssetsAC        = ($ToNetSurplus2 <= 50000) ? 0 : $ToNetSurplus2 * 0.35;
        $FixedAssetEquipment  = $ToNetSurplus2 * 0.05;

        $FixedAssetsSum = $FixedAssetsFurniture + $FixedAssetsComputer + $FixedAssetsAC + $FixedAssetEquipment;

        // $BalanceSheetinBank   = ($opening_cash_in_hand ?? 0) + ($opening_cash_in_bank ?? 0);
        $BalanceSheetinBank   = $total_cloes_balance;
        $BalanceSheetRightSum = $FixedAssetsSum + $BalanceSheetinBank;

        $LastAccount = $opening_cash_in_hand + $opening_cash_in_bank + $FixedAssetsSum;

        // dd($LastAccount);

        $GeneralFundSum = $LastAccount + $ToNetSurplus;

        $SundryCreditors = $BalanceSheetRightSum - $GeneralFundSum;

        $BalanceSheetLeftSum = $GeneralFundSum + $SundryCreditors;

        // Prepare final returned array (keeps original keys/naming)

        return [
            // Left side start
            'opening_cash_in_hand'               => $opening_cash_in_hand ?? 0,
            'opening_cash_in_bank'               => $opening_cash_in_bank ?? 0,
            'toCreditEMI'                        => $dailyAgg->toCreditEMI ?? 0,
            'short_term_borrowing'               => $monthlyAgg->TotalDirectorLoan ?? 0,
            'long_term_borrowing'                => (($monthlyAgg->TotalBankLoan_only ?? 0) + ($monthlyAgg->TotalInvestment_for_investor ?? 0)),
            'membership_charge'                  => $grandTotalRowMembershipCharge->grand_total_amount ?? 0,
            'processing_charge'                  => $dailyAgg->processing_charge ?? 0,
            'insurance_charge'                   => $dailyAgg->insurance_charge ?? 0,
            'IntarestReceivedOnMicrofinanceLoan' => $IntarestReceivedOnMicrofinanceLoan->IntarestReceivedOnMicrofinanceLoan ?? 0,
            'fund_saving_amount'                 => $fundSavingRow->total_rd_amount ?? 0,
            'penalty'                            => $penaltyRow->penalty ?? 0,
            'others'                             => $othersRow->others ?? 0,
            'leftSideSum'                        => array_sum($leftSideSum) ?? 0,

            'total_opening_balance'              => $total_opening_balance ?? 0,
            'lift_side_gran_total_balance'       => $lift_side_gran_total_balance ?? 0,

            // Right side start
            'rightSideSum'                       => array_sum($rightSideSum) ?? 0,
            'daily_collection_loan'              => $dailyAgg->daily_collection_loan ?? 0,
            'weekly_collection_loan'             => $dailyAgg->weekly_collection_loan ?? 0,
            'bi_weekly_collection_loan'          => $dailyAgg->bi_weekly_collection_loan ?? 0,
            'monthly_collection_loan'            => $dailyAgg->monthly_collection_loan ?? 0,
            'fund_saving_withdraw'               => $dailyAgg->fund_saving_withdraw ?? 0,
            'total_rd_interest'                  => $dailyAgg->total_rd_interest ?? 0,
            'interest_paid_on_loan'              => $interestPaidOnLoanRow->interest_paid_on_loan ?? 0,
            'other_charges_paid_for_loan_taken'  => $otherChargesRowLoanTaken->other_charges_paid_for_loan_taken ?? 0,
            'paid_insurance_charge'              => $dailyAgg->processing_charge ?? 0,
            'director_salary'                    => $monthlyAgg->director_salary ?? 0,
            'staff_salary'                       => $monthlyAgg->staff_salary ?? 0,
            'staff_uniform_id_card'              => $monthlyAgg->staff_uniform_id_card ?? 0,
            'staff_training'                     => $monthlyAgg->staff_training ?? 0,
            'customer_awareness_camp'            => $monthlyAgg->customer_awareness_camp ?? 0,
            'cultural_programme'                 => $monthlyAgg->cultural_programme ?? 0,
            'social_welfare_activity'            => $monthlyAgg->social_welfare_activity ?? 0,
            'office_rent'                        => $monthlyAgg->office_rent ?? 0,
            'electricity_bill'                   => $monthlyAgg->electricity_bill ?? 0,
            'internet_mobile_recharge'           => $monthlyAgg->internet_mobile_recharge ?? 0,
            'marketing_cost'                     => $monthlyAgg->marketing_cost ?? 0,
            'closingBankRight'                   => $closingBankRight ?? 0,
            'closingCashRight'                   => $closingCashRight ?? 0,
            'total_cloes_balance'                => $total_cloes_balance ?? 0,
            'other_general_cost'                 => $other_general_cost ?? 0,
            'right_side_round'                   => $right_side_round ?? 0,
            'right_side_gran_total_balance'      => $right_side_gran_total_balance ?? 0,

            'ToNetSurplus'                       => $ToNetSurplus ?? 0,
            'ExcessIncomeOver'                   => $ExcessIncomeOver ?? 0,

            // Balance Sheet Right
            'ToNetSurplus2'                      => $ToNetSurplus2 ?? 0,
            'FixedAssetsFurniture'               => $FixedAssetsFurniture ?? 0,
            'FixedAssetsComputer'                => $FixedAssetsComputer ?? 0,
            'FixedAssetsAC'                      => $FixedAssetsAC ?? 0,
            'FixedAssetEquipment'                => $FixedAssetEquipment ?? 0,
            'FixedAssetsSum'                     => $FixedAssetsSum ?? 0,
            'BalanceSheetinBank'                 => $BalanceSheetinBank ?? 0,
            'BalanceSheetRightSum'               => $BalanceSheetRightSum ?? 0,

            // Balance Sheet Left
            'LastAccount'                        => $LastAccount ?? 0,
            'GeneralFundSum'                     => $GeneralFundSum ?? 0,
            'SundryCreditors'                    => $SundryCreditors ?? 0,
            'CashAtBankLeft'                     => $closingBankRight,
            'CashAtHandLeft'                     => $closingCashRight,
            'BalanceSheetLeftSum'                => $BalanceSheetLeftSum ?? 0,
        ];
    }

/*
{{ number_format($FixedAssetsFurniture, 2) }}
 {{ number_format($rightSideSum + $other_general_cost, 2) }}
 <h3>Short Term Borrowing: {{ $short_term_borrowing }}</h3>
<h3>Long Term Borrowing: {{ $long_term_borrowing }}</h3>
<h4>Total Borrowing: {{ $total_borrowing }}</h4>

        <td class="s10">{{ number_format($short_term_borrowing, 2) }}</td>
        <td class="s10">{{ number_format($long_term_borrowing, 2) }}</td>
        <td class="s10">{{ number_format($total_borrowing, 2) }}</td>

        ✅ This setup now:
Works for Blade view (/monthly/report) using session user.
Works for JSON API (/monthly/report/api/{userId}).
Uses raw SQL, can easily add more calculations in getMonthlyReportData().
Number formatting is only for Blade, JSON stays raw.

*/

// *****************************************************************

    public function getPreviousBalance(Request $request)
    {
        $date   = $request->input('date');
        $userId = Session::get('app_user_id');

        // Try to get the previous date (1 day back)
        $previousDate = date('Y-m-d', strtotime('-1 day', strtotime($date)));

        // Fetch the last day's record for this user
        $previousRecord = DB::table('daily_update')
            ->where('user_by', $userId)
            ->where('date_entry', $previousDate)
            ->first();

        if (! $previousRecord) {
            // If no record found for previous day, try 2 days back
            $previousDate = date('Y-m-d', strtotime('-2 days', strtotime($date)));

            $previousRecord = DB::table('daily_update')
                ->where('user_by', $userId)
                ->where('date_entry', $previousDate)
                ->first();
        }

        if ($previousRecord) {
            // Get Current Balance
            $previousCurrentBalance = $previousRecord->current_balance;

            // Calculate RD Balance
            $previousRDBalance = $previousRecord->rd_amount - ($previousRecord->rd_withdrawal + $previousRecord->rd_interest);

            $message = 'Previous record found.';
        } else {
            // Default values if no previous record found
            $previousCurrentBalance = 0.00;
            $previousRDBalance      = 0.00;
            $message                = 'No previous record found';
        }

        return response()->json([
            'previous_balance'    => $previousCurrentBalance,
            'previous_rd_balance' => $previousRDBalance,
            'message'             => $message,
        ]);
    }

// delete **************************************************

    /*
    Your Controller Method (already good)
    // ✅ Only allow specific tables
        $allowedTables = ['members', 'plans', 'companies'];
    ✅ 2. Define Route in web.php

    Route::get('/delete/{table}/{id}', [MemberController::class, 'deleteFromTable'])->name('generic.delete');

    ✅ 3. Use in Blade (Dynamic Delete Button)

    <a href="{{ route('generic.delete', ['table' => 'members', 'id' => $company->id]) }}"
    onclick="return confirm('Are you sure you want to delete {{ $company->name }}?')"
    class="btn btn-danger">
        <i class="fa fa-trash"></i>
    </a>

                   <form action="{{ route('generic.delete', ['table' => 'members', 'id' => $company->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure want to delete {{ $company->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

    */

    public function deleteFromTable(Request $request, $table, $id)
    {
        // ✅ Only allow specific tables
        $allowedTables = ['app_users', 'monthly_update', 'members', 'package_master', 'app_banners', 'business_plans', 'daily_update'];

        if (! in_array($table, $allowedTables)) {
            abort(403, 'Unauthorized table access.');
        }

        DB::table($table)->where('id', $id)->delete();

        // return back()->with('success', ucfirst($table) . ' deleted successfully!');
        return back()->with('success', ' Deleted successfully!');
    }

    // delete **************************************************

    // ***********************************************

}
