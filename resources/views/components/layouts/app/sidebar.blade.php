<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Platform" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>Dashboard</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        {{-- ========================= ACCOUNTS ========================= --}}
        @canany(['view_user','create_user','update_user','delete_user','manage_roles'])
        <flux:sidebar.group expandable :expanded="false" heading="Accounts" class="grid">

            @canany(['view_user','create_user','update_user','delete_user'])
            <flux:sidebar.item icon="user" :href="route('admin.users')" wire:navigate>Users</flux:sidebar.item>
            @endcanany

            @can('manage_roles')
            <flux:sidebar.item icon="key" :href="route('admin.roles')" wire:navigate>Roles</flux:sidebar.item>
            @endcan

            @canany(['view_employee','create_employee','update_employee','delete_employee'])
            <flux:sidebar.item icon="users" :href="route('admin.employees')" wire:navigate>Employees</flux:sidebar.item>
            @endcanany

        </flux:sidebar.group>
        @endcanany

        <flux:sidebar.nav>

            {{-- ========================= PATIENTS ========================= --}}
            @canany(['create_patient','view_patient','update_patient'])
            <flux:sidebar.item icon="users" :href="route('patients')" wire:navigate>Patients</flux:sidebar.item>
            @endcanany

            {{-- ========================= PAYMENTS (CASHIER ONLY) ========================= --}}
            @canany(['manage_card_fee','view_lab_payments','view_imaging_payments','view_cashier_medication_orders','process_walkin_payment','view_rehab_cashier_queue','process_cupping_payment'])
            <flux:sidebar.group expandable :expanded="false" heading="Payments" class="grid">

                @canany(['manage_card_fee','create_invoice','view_invoice','receive_payment','refund_payment'])
                <flux:sidebar.item icon="currency-dollar" :href="route('payments')" wire:navigate>Card Payments
                </flux:sidebar.item>
                @endcanany

                @can('view_lab_payments')
                <flux:sidebar.item icon="beaker" :href="route('lab-orders.payments')" wire:navigate>Lab Payments
                </flux:sidebar.item>
                @endcan

                @can('view_imaging_payments')
                <flux:sidebar.item icon="camera" :href="route('cashier.imaging')" wire:navigate>Imaging Payments
                </flux:sidebar.item>
                @endcan

                @can('view_cashier_medication_orders')
                <flux:sidebar.item icon="currency-dollar" :href="route('cashier.medication.orders')" wire:navigate>
                    Pharmacy Payments</flux:sidebar.item>
                @endcan

                @can('process_walkin_payment')
                <flux:sidebar.item icon="shopping-cart" :href="route('pharmacy.walkin.payment')" wire:navigate>Walk-in
                    Payments</flux:sidebar.item>
                @endcan

                @canany(['view_rehab_cashier_queue','process_rehab_cashier_payment'])
                <flux:sidebar.item icon="heart" :href="route('rehab.cashier.queue')" wire:navigate>Rehab Payments
                </flux:sidebar.item>
                @endcanany

                @can('process_cupping_payment')
                <flux:sidebar.item icon="fire" :href="route('cupping.cashier.queue')" wire:navigate>Cupping Payments
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= CLINICAL ========================= --}}
            @canany(['record_vitals','update_vitals','view_doctor_queue'])
            <flux:sidebar.group expandable :expanded="false" heading="Clinical" class="grid">

                @canany(['record_vitals','update_vitals','manage_vital_types'])
                <flux:sidebar.item icon="clipboard" :href="route('triage.encounters')" wire:navigate>Triage
                </flux:sidebar.item>
                <flux:sidebar.item icon="heart" :href="route('vital-types.index')" wire:navigate>Vital Types
                </flux:sidebar.item>
                @endcanany

                @can('view_doctor_queue')
                <flux:sidebar.item icon="credit-card" :href="route('doctor.queue')" wire:navigate>Doctor Queue
                </flux:sidebar.item>
                @endcan
                @can("view_referral_queue")
                <flux:sidebar.item icon="credit-card" :href="route('referrals.queue')" wire:navigate>Referral Queue
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= CUPPING ========================= --}}
            @canany(['create_cupping_order','view_cupping_treatment_queue','manage_cupping_treatment','view_cupping_results','manage_cupping_admin_packages','manage_cupping_locations','manage_cupping_types','view_cupping_sales_report'])
            <flux:sidebar.group expandable :expanded="false" heading="Cupping Therapy" class="grid">

                {{-- @can('create_cupping_order')
                <flux:sidebar.item icon="plus-circle" :href="route('cupping.doctor.order', ['encounter' => 0])"
                    wire:navigate>New Cupping Order</flux:sidebar.item>
                @endcan --}}

                @can('view_cupping_treatment_queue')
                <flux:sidebar.item icon="clock" :href="route('cupping.treatment.queue')" wire:navigate>Treatment Queue
                </flux:sidebar.item>
                @endcan

                @can('view_cupping_results')
                <flux:sidebar.item icon="clipboard-document-check" :href="route('cupping.results')" wire:navigate>
                    Cupping Results</flux:sidebar.item>
                @endcan

                @canany(['manage_cupping_admin_packages','manage_cupping_packages'])
                <flux:sidebar.item icon="archive-box" :href="route('cupping.admin.packages')" wire:navigate>Package
                    Manager</flux:sidebar.item>
                @endcanany

                @can('manage_cupping_locations')
                <flux:sidebar.item icon="map-pin" :href="route('cupping.locations')" wire:navigate>Cupping Locations
                </flux:sidebar.item>
                @endcan

                @can('manage_cupping_types')
                <flux:sidebar.item icon="beaker" :href="route('cupping.types')" wire:navigate>Cupping Types
                </flux:sidebar.item>
                @endcan

                @can('view_cupping_sales_report')
                <flux:sidebar.item icon="chart-bar" :href="route('cupping.sales-report')" wire:navigate>Sales Report
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= REHABILITATION ========================= --}}
            @canany(['view_rehab_queue','fill_rehab_questionnaire','view_rehab_treatment_queue','manage_rehab_treatment','view_rehab_packages','view_rehab_finance_report','manage_rehab_bed_selection'])
            <flux:sidebar.group expandable :expanded="false" heading="Rehabilitation" class="grid">

                @canany(['create_rehab_order','review_rehab_order'])
                <flux:sidebar.item icon="clipboard-document-list" :href="route('doctor.rehab.queue')" wire:navigate>
                    Rehab Reviews</flux:sidebar.item>
                @endcanany

                @canany(['view_rehab_packages','manage_rehab_packages'])
                <flux:sidebar.item icon="archive-box" :href="route('rehab.packages.index')" wire:navigate>Rehab Packages
                </flux:sidebar.item>
                @endcanany

                @can('view_rehab_treatment_queue')
                <flux:sidebar.item icon="clock" :href="route('rehab.treatment.queue')" wire:navigate>Treatment Queue
                </flux:sidebar.item>
                @endcan

                @can('view_rehab_queue')
                <flux:sidebar.item icon="users" :href="route('rehab.queue')" wire:navigate>Patient Queue
                </flux:sidebar.item>
                @endcan

                @can('fill_rehab_questionnaire')
                <flux:sidebar.item icon="clipboard-document-check" :href="route('rehab.questionnaire', ['id' => 0])"
                    wire:navigate>Questionnaire</flux:sidebar.item>
                @endcan

                @canany(['view_rehab_templates','manage_rehab_templates'])
                <flux:sidebar.item icon="rectangle-group" :href="route('rehab.templates.index')" wire:navigate>Question
                    Templates</flux:sidebar.item>
                @endcanany

                @can('manage_rehab_treatment_types')
                <flux:sidebar.item icon="beaker" :href="route('rehab.treatment-types')" wire:navigate>Treatment Types
                </flux:sidebar.item>
                @endcan

                @can('manage_rehab_bed_selection')
                <flux:sidebar.item icon="credit-card" :href="route('rehab.bed-manager.queue')" wire:navigate>Bed
                    Selection</flux:sidebar.item>
                @endcan

                @can('view_rehab_finance_report')
                <flux:sidebar.item icon="document-text" :href="route('rehab.finance.report')" wire:navigate>Finance
                    Report</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= APPOINTMENTS ========================= --}}
            @canany(['create_appointment','view_today_appointments','view_appointment_calendar'])
            <flux:sidebar.group expandable :expanded="false" heading="Appointments" class="grid">

                @can('view_today_appointments')
                <flux:sidebar.item icon="calendar" :href="route('doctor.appointments.today')" wire:navigate>Today's
                    Schedule</flux:sidebar.item>
                @endcan

                @can('view_appointment_calendar')
                <flux:sidebar.item icon="calendar" :href="route('doctor.appointments.calendar')" wire:navigate>Calendar
                    View</flux:sidebar.item>
                @endcan

                @can('view_upcoming_appointments')
                <flux:sidebar.item icon="calendar-days" :href="route('doctor.appointments.upcoming')" wire:navigate>
                    Upcoming</flux:sidebar.item>
                @endcan

                @can('create_appointment')
                <flux:sidebar.item icon="plus-circle" :href="route('doctor.appointments.create')" wire:navigate>New
                    Appointment</flux:sidebar.item>
                @endcan

                @can('view_all_appointments')
                <flux:sidebar.item icon="clipboard-document-list" :href="route('doctor.appointments.all')"
                    wire:navigate>All Appointments</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= LABORATORY ========================= --}}
            @canany(['view_lab_dashboard','view_lab_results','manage_lab_tests'])
            <flux:sidebar.group expandable :expanded="false" heading="Laboratory" class="grid">

                @canany(['collect_lab_sample','report_lab_result'])
                <flux:sidebar.item icon="credit-card" :href="route('lab.dashboard')" wire:navigate>Lab Dashboard
                </flux:sidebar.item>
                @endcanany

                @can('view_lab_results')
                <flux:sidebar.item icon="beaker" :href="route('doctor.lab-results')" wire:navigate>Lab Results
                </flux:sidebar.item>
                @endcan

                @can('manage_lab_tests')
                <flux:sidebar.item icon="list-bullet" :href="route('lab-tests.index')" wire:navigate>Lab Tests
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= RADIOLOGY ========================= --}}
            @canany(['view_radiology_dashboard','upload_imaging_result','view_imaging_results'])
            <flux:sidebar.group expandable :expanded="false" heading="Radiology" class="grid">

                @can('view_radiology_dashboard')
                <flux:sidebar.item icon="camera" :href="route('radiology.dashboard')" wire:navigate>Radiology Dashboard
                </flux:sidebar.item>
                @endcan

                @can('upload_imaging_result')
                <flux:sidebar.item icon="cloud-arrow-up" :href="route('radiology.dashboard')" wire:navigate>Upload
                    Results</flux:sidebar.item>
                @endcan

                @can('manage_imaging_types')
                <flux:sidebar.item icon="tag" :href="route('admin.imaging-types')" wire:navigate>Imaging Types
                </flux:sidebar.item>
                @endcan

                @can('manage_body_parts')
                <flux:sidebar.item icon="tag" :href="route('admin.body-parts')" wire:navigate>Body Parts
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= PHARMACY ========================= --}}
            @canany(['view_pharmacy_dashboard','view_pharmacy_items','view_pharmacy_batches','manage_custom_medications','create_walkin_order','dispense_walkin_medication','view_pharmacy_sales_report'])
            <flux:sidebar.group expandable :expanded="false" heading="Pharmacy" class="grid">

                @canany(['view_pharmacy_items','view_pharmacy_masters'])
                <flux:sidebar.item icon="beaker" :href="route('pharmacy.masters')" wire:navigate>Pharmacy Items
                </flux:sidebar.item>
                @endcanany

                @canany(['view_pharmacy_batches','manage_pharmacy_batches'])
                <flux:sidebar.item icon="document-text" :href="route('pharmacy.batches')" wire:navigate>Batches / Stock
                </flux:sidebar.item>
                @endcanany

                @can('view_pharmacy_dashboard')
                <flux:sidebar.item icon="clipboard-document-list" :href="route('pharmacy.dashboard')" wire:navigate>
                    Pharmacy Queue</flux:sidebar.item>
                @endcan

                @can('manage_custom_medications')
                <flux:sidebar.item icon="beaker" :href="route('pharmacy.custom-medications')" wire:navigate>Custom
                    Medications</flux:sidebar.item>
                @endcan

                @can('manage_consumables')
                <flux:sidebar.item icon="credit-card" :href="route('inventory.consumables')" wire:navigate>Consumables
                </flux:sidebar.item>
                @endcan

                @can('view_pharmacy_sales_report')
                <flux:sidebar.item icon="chart-bar" :href="route('pharmacy.report.sales')" wire:navigate>Sales Report
                </flux:sidebar.item>
                @endcan

                @can('create_walkin_order')
                <flux:sidebar.item icon="shopping-cart" :href="route('pharmacy.walkin.create')" wire:navigate>Walk-in
                    Order</flux:sidebar.item>
                @endcan

                @can('dispense_walkin_medication')
                <flux:sidebar.item icon="truck" :href="route('pharmacy.walkin.dispense')" wire:navigate>Walk-in Dispense
                </flux:sidebar.item>
                @endcan

                @can('view_walkin_sales_report')
                <flux:sidebar.item icon="document-chart-bar" :href="route('pharmacy.walkin.report')" wire:navigate>
                    Walk-in Sales Report</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= BED MANAGEMENT ========================= --}}
            @canany(['view_beds','create_bed','update_bed','delete_bed','assign_bed'])
            <flux:sidebar.group expandable :expanded="false" heading="Bed Management" class="grid">
                <flux:sidebar.item icon="credit-card" :href="route('beds.index')" wire:navigate>Bed Dashboard
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= CONFIGURATION ========================= --}}
            @canany(['manage_medical_history_templates','manage_chief_complaint_templates','manage_examination_templates','manage_assessment_templates'])
            <flux:sidebar.group expandable :expanded="false" heading="Configuration" class="grid">

                @can('manage_medical_history_templates')
                <flux:sidebar.item icon="clipboard-document-list" :href="route('config.medical-history-templates')"
                    wire:navigate>Medical History</flux:sidebar.item>
                @endcan

                @can('manage_chief_complaint_templates')
                <flux:sidebar.item icon="exclamation-triangle" :href="route('config.chief-complaint-templates')"
                    wire:navigate>Chief Complaint</flux:sidebar.item>
                @endcan

                @can('manage_examination_templates')
                <flux:sidebar.item icon="document-magnifying-glass" :href="route('examination-templates')"
                    wire:navigate>Examination</flux:sidebar.item>
                @endcan

                @can('manage_assessment_templates')
                <flux:sidebar.item icon="clipboard-document-check" :href="route('config.assessment-templates')"
                    wire:navigate>Assessment</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= SETTINGS (ADMIN ONLY) ========================= --}}
            @canany(['manage_service_categories','manage_services'])
            <flux:sidebar.group expandable :expanded="false" heading="Settings" class="grid">

                <flux:sidebar.item icon="folder" :href="route('service-category')" wire:navigate>Service Categories
                </flux:sidebar.item>
                <flux:sidebar.item icon="server-stack" :href="route('services')" wire:navigate>Services
                </flux:sidebar.item>
                <flux:sidebar.item icon="credit-card" :href="route('card-fee')" wire:navigate>Card Fee
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= FINANCIAL REPORTS ========================= --}}
            @canany(['view_registration_payment_report','view_lab_payment_report','view_imaging_payment_report','view_pharmacy_payment_report','view_rehab_payment_report','view_bed_payment_report','view_cupping_sales_report'])
            <flux:sidebar.group expandable :expanded="false" heading="Financial Reports" class="grid">

                @can('view_registration_payment_report')
                <flux:sidebar.item icon="credit-card" :href="route('reports.registration-payments')" wire:navigate>
                    Registration Payments</flux:sidebar.item>
                @endcan

                @can('view_lab_payment_report')
                <flux:sidebar.item icon="beaker" :href="route('reports.lab-payments')" wire:navigate>Laboratory Payments
                </flux:sidebar.item>
                @endcan

                @can('view_imaging_payment_report')
                <flux:sidebar.item icon="camera" :href="route('reports.imaging-payments')" wire:navigate>Radiology
                    Payments</flux:sidebar.item>
                @endcan

                @can('view_pharmacy_payment_report')
                <flux:sidebar.item icon="currency-dollar" :href="route('reports.pharmacy-payments')" wire:navigate>
                    Pharmacy Payments</flux:sidebar.item>
                @endcan

                @can('view_rehab_payment_report')
                <flux:sidebar.item icon="heart" :href="route('reports.rehab-payments')" wire:navigate>Rehabilitation
                    Payments</flux:sidebar.item>
                @endcan

                @can('view_bed_payment_report')
                <flux:sidebar.item icon="credit-card" :href="route('reports.bed-payments')" wire:navigate>Bed Payments
                </flux:sidebar.item>
                @endcan

                @can('view_cupping_sales_report')
                <flux:sidebar.item icon="fire" :href="route('cupping.sales-report')" wire:navigate>Cupping Sales
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany

        </flux:sidebar.nav>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
