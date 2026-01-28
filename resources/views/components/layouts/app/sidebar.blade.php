{{--
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
        @hasanyrole('super-admin|admin')
        <flux:sidebar.group expandable heading="Accounts" class="grid">
            <flux:sidebar.item icon="user" :href="route('admin.users')">Users</flux:sidebar.item>
            <flux:sidebar.item icon="key" :href="route('admin.roles')">Roles</flux:sidebar.item>
        </flux:sidebar.group>
        @endhasanyrole
        <flux:sidebar.nav>
            @role('registration')
            <flux:sidebar.item icon="users" :href="route('patients')">Patients</flux:sidebar.item>
            @endrole

            @role('cashier')
            <flux:sidebar.item icon="currency-dollar" :href="route('payments')">Payments</flux:sidebar.item>
            @endrole
            @hasanyrole('doctor|nurse')
            <flux:sidebar.group expandable heading="Clinical" class="grid">
                @role('nurse')
                <flux:sidebar.item icon="home-modern" :href="route('patient.nursing')">Triage
                </flux:sidebar.item>
                @endrole
                @role('doctor')
                <flux:sidebar.item icon="home-modern" :href="route('doctor.queue')">Doctor</flux:sidebar.item>
                @endrole
            </flux:sidebar.group>
            @endhasanyrole

            @hasanyrole('laboratory')
            <flux:sidebar.group expandable heading="Results" class="grid">
                <flux:sidebar.item icon="tag">Lab</flux:sidebar.item>
                <flux:sidebar.item icon="tag">Imaging</flux:sidebar.item>
                <flux:sidebar.item icon="tag">Radiology</flux:sidebar.item>
            </flux:sidebar.group>
            @endhasanyrole
            @hasanyrole('pharmacy')
            <flux:sidebar.group expandable heading="Pharmacy" class="grid">
                <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('pharmacy.masters')">
                    Pharmacy
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-text" :href="route('pharmacy.batches')">Batches / Stock
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endhasanyrole
            @hasanyrole('super-admin|admin')
            <flux:sidebar.group expandable heading="Settings" class="grid">
                <flux:sidebar.item icon="document-text" :href="route('service-category')">Services Categories
                </flux:sidebar.item>
                <flux:sidebar.item icon="server-stack" :href="route('services')">Services</flux:sidebar.item>
                <flux:sidebar.item icon="cog" :href="route('card-fee')">Card Fee</flux:sidebar.item>

            </flux:sidebar.group>
            @endhasanyrole
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

</html> --}}
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
            @canany(['create_invoice','view_invoice','receive_payment','refund_payment'])
            <flux:sidebar.item icon="currency-dollar" :href="route('payments')" wire:navigate>Register Payments
            </flux:sidebar.item>
            @endcanany
            @canany(['super-admin', 'receive_payment'])
            <flux:sidebar.item icon="beaker" :href="route('lab-orders.payments')" wire:navigate>
                Lab Orders Payment
            </flux:sidebar.item>
            <flux:sidebar.item icon="beaker" :href="route('cashier.imaging')" wire:navigate>
                Imaging Orders Payment
            </flux:sidebar.item>
            @endcanany
            {{-- ========================= CLINICAL ========================= --}}
            @if(auth()->user()->hasRole(['doctor', 'nurse', 'clinician','super-admin']))
            <flux:sidebar.group expandable heading="Clinical" class="grid">

                @if(auth()->user()->hasRole(['nurse','super-admin']))
                <flux:sidebar.item icon="home-modern" :href="route('patient.nursing')" wire:navigate>Triage
                </flux:sidebar.item>
                <flux:sidebar.item icon="home-modern" :href="route('triage.encounters')" wire:navigate>Triage New
                </flux:sidebar.item>
                @endif

                @if(auth()->user()->hasRole(['doctor', 'clinician']))
                <flux:sidebar.item icon="home-modern" :href="route('doctor.queue')" wire:navigate>Doctor
                </flux:sidebar.item>
                @endif
            </flux:sidebar.group>
            @endif


            @canany( 'view_lab_result')
            <flux:sidebar.item icon="beaker" :href="route('doctor.lab-results')" wire:navigate>
                Lab Results
            </flux:sidebar.item>
        @endcanany
            {{-- ========================= LABORATORY ========================= --}}
            @canany(['view_lab_order','enter_lab_result','verify_lab_result'])
            <flux:sidebar.group expandable heading="Orders" class="grid">
                <flux:sidebar.item icon="tag" :href="route('lab.dashboard')" wire:navigate>Lab</flux:sidebar.item>
                <flux:sidebar.item icon="tag" wire:navigate>Radiology</flux:sidebar.item>
            </flux:sidebar.group>
            @endcanany
            @can('view_lab_tests')
            <li>
                <a href="{{ route('lab-tests.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('lab-tests.*') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="flex-shrink-0 h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Lab Tests
                </a>
            </li>
            @endcan

            {{-- ========================= PHARMACY ========================= --}}
            @canany(['view_prescription','dispense_drug','manage_drugs'])
            <flux:sidebar.group expandable heading="Pharmacy" class="grid">
                <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('pharmacy.masters')" wire:navigate>
                    Pharmacy
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-text" :href="route('pharmacy.batches')" wire:navigate>Batches / Stock
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