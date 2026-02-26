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
        <flux:sidebar.group expandable heading="Accounts" class="grid">

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

            {{-- ========================= PAYMENTS ========================= --}}

            @canany(['super-admin', 'receive_payment'])
            <flux:sidebar.group expandable heading="Payment" class="grid">
                @canany(['create_invoice','view_invoice','receive_payment','refund_payment'])
                <flux:sidebar.item icon="currency-dollar" :href="route('payments')" wire:navigate>Register Payments
                </flux:sidebar.item>
                @endcanany
                <flux:sidebar.item icon="currency-dollar" :href="route('lab-orders.payments')" wire:navigate>
                    Lab Payment
                </flux:sidebar.item>
                <flux:sidebar.item icon="currency-dollar" :href="route('cashier.imaging')" wire:navigate>
                    Imaging Payment
                </flux:sidebar.item>
                <flux:sidebar.item icon="currency-dollar" :href="route('cashier.medication.orders')" wire:navigate>
                    Pharmacy Payment
                </flux:sidebar.item>
            </flux:sidebar.group>

            @endcanany
            {{-- ========================= CLINICAL ========================= --}}
            @if(auth()->user()->hasRole(['doctor', 'nurse', 'clinician', 'rehab', 'super-admin']))
            <flux:sidebar.group expandable heading="Clinical" class="grid">

                @if(auth()->user()->hasRole(['nurse','super-admin']))
                <flux:sidebar.item icon="home-modern" :href="route('triage.encounters')" wire:navigate>Triage
                </flux:sidebar.item>
                <flux:sidebar.item icon="home-modern" :href="route('vital-types.index')" wire:navigate>Vital Types
                </flux:sidebar.item>
                @endif

                @if(auth()->user()->hasRole(['doctor', 'clinician']))
                <flux:sidebar.item icon="home-modern" :href="route('doctor.queue')" wire:navigate>Doctor
                </flux:sidebar.item>
                <flux:sidebar.item icon="paper-airplane" :href="route('referrals.queue')" wire:navigate>
                    Referral
                </flux:sidebar.item>
                @endif
            </flux:sidebar.group>
            @endif
           

            {{-- rehab sidebar-start--}}
            @if(auth()->user()->hasRole(['doctor', 'nurse', 'clinician', 'rehab', 'bed_manager', 'cashier',
            'super-admin']))
            <flux:sidebar.group expandable heading="Rehabilitation" class="grid">

                <!-- Doctor Routes -->
                @if(auth()->user()->hasRole(['doctor', 'clinician', 'super-admin']))
                <flux:sidebar.item icon="clipboard-document-list" :href="route('doctor.rehab.queue')" wire:navigate>
                    Rehab Reviews
                </flux:sidebar.item>
                <flux:sidebar.item icon="archive-box" :href="route('rehab.packages.index')" wire:navigate>
                    Rehab Packages
                </flux:sidebar.item>
                <flux:sidebar.item icon="clock" :href="route('rehab.treatment.queue')" wire:navigate>
                    Treatment Queue
                </flux:sidebar.item>
                @endif

                <!-- Rehab Staff Routes -->
                @if(auth()->user()->hasRole(['rehab', 'super-admin']))
                <flux:sidebar.item icon="clipboard-document-check" :href="route('rehab.queue')" wire:navigate>
                    Patient Queue
                </flux:sidebar.item>
                <flux:sidebar.item icon="rectangle-group" :href="route('rehab.templates.index')" wire:navigate>
                    Question Templates
                </flux:sidebar.item>
                <flux:sidebar.item icon="beaker" :href="route('rehab.treatment-types')" wire:navigate>
                    Treatment Types
                </flux:sidebar.item>
                
                @endif

                <!-- Bed Manager Routes -->
                @if(auth()->user()->hasRole(['bed_manager', 'super-admin']))
                <flux:sidebar.item icon="home-modern" :href="route('rehab.bed-manager.queue')" wire:navigate>
                    Bed Selection Queue
                </flux:sidebar.item>
                @endif

                <!-- Cashier Routes -->
                @if(auth()->user()->hasRole(['cashier', 'super-admin']))
                <flux:sidebar.item icon="currency-dollar" :href="route('rehab.cashier.queue')" wire:navigate>
                    Payment Queue
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-text" :href="route('cashier.rehab.payments')" wire:navigate>
                    Payment History
                </flux:sidebar.item>
                @endif

                <!-- Admin/Manager Routes (only super-admin) -->
                @if(auth()->user()->hasRole(['super-admin']))
                <flux:sidebar.item icon="cube" :href="route('rehab.packages.create')" wire:navigate>
                    Create Package
                </flux:sidebar.item>
                <flux:sidebar.item icon="pencil-square" :href="route('rehab.templates.create')" wire:navigate>
                    Create Template
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-chart-bar" :href="route('reports.rehab-payments')" wire:navigate>
                    Rehab Reports
                </flux:sidebar.item>
                @endif

            </flux:sidebar.group>
            @endif
{{-- rehabitation end --}}
 {{-- ========================= LABORATORY ========================= --}}
            @canany(['view_lab_result','view_lab_order','enter_lab_result','verify_lab_result'])
            <flux:sidebar.group expandable heading="Labratory" class="grid">
                @can('enter_lab_result')
                <flux:sidebar.item icon="tag" :href="route('lab.dashboard')" wire:navigate>Laboratory Dashboard
                </flux:sidebar.item>
                @endcan
                <flux:sidebar.item icon="beaker" :href="route('doctor.lab-results')" wire:navigate>
                    Laboratory Results
                </flux:sidebar.item>
                @can('view_lab_tests')
                <flux:sidebar.item icon="beaker" :href="route('lab-tests.index')" wire:navigate>
                    Laboratory Tests
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            @endcanany
            {{-- ========================= Radiology ========================= --}}
            @canany([ 'view_imaging_order','upload_imaging_result',])
            <flux:sidebar.group expandable heading="Radiology" class="grid">

                <flux:sidebar.item icon="tag" :href="route('radiology.dashboard')" wire:navigate>Radiology
                </flux:sidebar.item>
                <flux:sidebar.item icon="tag" :href="route('admin.imaging-types')" wire:navigate>Imaging Types
                </flux:sidebar.item>
                <flux:sidebar.item icon="tag" :href="route('admin.body-parts')" wire:navigate>Body parts
                </flux:sidebar.item>

            </flux:sidebar.group>
            @endcan
            {{-- ========================= Bed Management: ========================= --}}
            @canany([
            'view_beds',
            'create_bed',
            'update_bed',
            'delete_bed',
            'assign_bed',
            'transfer_bed',
            'discharge_bed'
            ])
            <flux:sidebar.group expandable heading="Bed Management" class="grid">
                <flux:sidebar.item icon="banknotes" :href="route('beds.index')" wire:navigate>
                    Bed Dashboard
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= PHARMACY ========================= --}}
            @canany(['view_prescription','dispense_drug','manage_drugs'])
            <flux:sidebar.group expandable heading="Pharmacy" class="grid">
                <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('pharmacy.masters')" wire:navigate>
                    Pharmacy
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-text" :href="route('pharmacy.batches')" wire:navigate>Batches / Stock
                </flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" :href="route('pharmacy.dashboard')" wire:navigate>
                    pharmacy Queue
                </flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" :href="route('pharmacy.custom-medications')"
                    wire:navigate>
                    Custom Medications
                </flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" :href="route('inventory.consumables')" wire:navigate>
                    Consumables
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany

            {{-- ========================= CONFIGURATION ========================= --}}
            @canany(['manage_medical_history_templates', 'manage_chief_complaint_templates',
            'manage_examination_templates', 'manage_assessment_templates'])
            <flux:sidebar.group expandable heading="Configuration" class="grid">
                <flux:sidebar.item icon="clipboard-document-list" :href="route('config.medical-history-templates')"
                    wire:navigate>
                    Medical History
                </flux:sidebar.item>
                <flux:sidebar.item icon="exclamation-triangle" :href="route('config.chief-complaint-templates')"
                    wire:navigate>
                    Chief Complaint
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-magnifying-glass" :href="route('examination-templates')"
                    wire:navigate>
                    Examination
                </flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-check" :href="route('config.assessment-templates')"
                    wire:navigate>
                    Assessment
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany
            {{-- ========================= SETTINGS ========================= --}}
            @canany(['view_reports','export_reports','manage_inventory','manage_services'])
            <flux:sidebar.group expandable heading="Settings" class="grid">

                @canany(['manage_services'])
                <flux:sidebar.item icon="document-text" :href="route('service-category')" wire:navigate>Services
                    Categories
                </flux:sidebar.item>
                <flux:sidebar.item icon="server-stack" :href="route('services')" wire:navigate>Services
                </flux:sidebar.item>
                <flux:sidebar.item icon="cog" :href="route('card-fee')" wire:navigate>Card Fee</flux:sidebar.item>
                @endcanany
            </flux:sidebar.group>

          
            @endcanany

            <flux:sidebar.group expandable heading="Financial Management" class="grid">

                <flux:sidebar.item icon="currency-dollar" :href="route('reports.registration-payments')" wire:navigate>
                    Registration Payments
                </flux:sidebar.item>


                <flux:sidebar.item icon="beaker" :href="route('reports.lab-payments')" wire:navigate>
                    Laboratory Payments
                </flux:sidebar.item>

                <flux:sidebar.item icon="camera" :href="route('reports.imaging-payments')" wire:navigate>
                    Radiology Payments
                </flux:sidebar.item>

                <flux:sidebar.item icon="beaker" :href="route('reports.pharmacy-payments')" wire:navigate>
                    Pharmacy Payments
                </flux:sidebar.item>

                <flux:sidebar.item icon="heart" :href="route('reports.rehab-payments')" wire:navigate>
                    Rehabilitation Payments
                </flux:sidebar.item>
                <flux:sidebar.item icon="home-modern" :href="route('reports.bed-payments')" wire:navigate>
                   Bed Payments Report
                </flux:sidebar.item>
            </flux:sidebar.group>

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

                <flux:menu.separator />

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