<x-layouts.app.sidebar :title="$title ?? null">
    <flux:header class="block! bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
{{--        <flux:navbar class="lg:hidden w-full">--}}
{{--            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />--}}
{{--            <flux:spacer />--}}
{{--            <flux:dropdown position="top" align="start">--}}
{{--                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />--}}
{{--                <flux:menu>--}}
{{--                    <flux:menu.radio.group>--}}
{{--                        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>--}}
{{--                        <flux:menu.radio>Truly Delta</flux:menu.radio>--}}
{{--                    </flux:menu.radio.group>--}}
{{--                    <flux:menu.separator />--}}
{{--                    <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>--}}
{{--                </flux:menu>--}}
{{--            </flux:dropdown>--}}
{{--        </flux:navbar>--}}
        <flux:navbar >
            <flux:dropdown position="top" align="start">
                <flux:navbar.item icon:trailing="chevron-down">Common Functions</flux:navbar.item>
                <flux:navmenu>
                    <flux:navmenu.item href="#">Send to Scheduler</flux:navmenu.item>
                    <flux:navmenu.item href="#">Change Status</flux:navmenu.item>
                    <flux:navmenu.item href="#">Zone Override</flux:navmenu.item>
                    <flux:navmenu.item href="#">Remove Override</flux:navmenu.item>
                    <flux:navmenu.item href="/students/{{request()->id}}/edit">Edit Student</flux:navmenu.item>
                    <flux:navmenu.item href="#">Assign Instructor</flux:navmenu.item>
                    <flux:navmenu.item href="#">Remove Instructor</flux:navmenu.item>
                    <livewire:student.complete-student :id="request()->id"/>
                    <flux:navmenu.item href="#">Update DriveTimes</flux:navmenu.item>
                    <flux:navmenu.item href="#">Toggle Contract Status</flux:navmenu.item>
                    <flux:navmenu.item href="#">Toggle Permit Verification</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
            <flux:dropdown position="top" align="start">
                <flux:navbar.item icon:trailing="chevron-down">Print Documents</flux:navbar.item>
                <flux:navmenu>
                    <flux:navmenu.item href="/documents/print_coversheet/{{request()->id}}" target="_blank">Coversheet</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/print_account_statement/{{request()->id}}" target="_blank">Account Statement</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/print_contract/{{request()->id}}" target="_blank">LxL Contract</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/sc_activity_log/{{request()->id}}" target="_blank">SC Activity Log</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/print_completion_certificate/{{request()->id}}" target="_blank">Completion Certificate</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/dip_certificate/{{request()->id}}" target="_blank">DIP Certificate</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/dip_letter/{{request()->id}}" target="_blank">DIP Letter</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/beginner_invoice/{{request()->id}}" target="_blank">Beginner Invoice</flux:navmenu.item>
                    <flux:navmenu.item href="/documents/print_instructor_certificate/{{request()->id}}" target="_blank">Instructor Certificate</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
            <flux:dropdown position="top" align="start">
                <flux:navbar.item icon:trailing="chevron-down">Email Functions</flux:navbar.item>
                <flux:navmenu>
                    <livewire:emails.account_statement :id="request()->id"/>
                    <livewire:emails.activity_log :id="request()->id"/>
                    <livewire:emails.completion_certificate :id="request()->id"/>
                    <livewire:emails.scheduling_instructions :id="request()->id"/>
                    <livewire:emails.scheduled_lessons :id="request()->id"/>
                    <livewire:emails.payment_instructions :id="request()->id"/>
                    <flux:navmenu.item href="#">Kia Gift Certificate</flux:navmenu.item>
                    <flux:navmenu.item href="#">Beginner Invoice</flux:navmenu.item>
                    <livewire:emails.general_message :id="request()->id" />

                </flux:navmenu>
            </flux:dropdown>
        </flux:navbar>
    </flux:header>
    <flux:main>
        {{ $slot }}
    </flux:main>
    <flux:toast />
    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="fixed bottom-4 right-4 z-50">
        <flux:radio value="light" icon="sun" />
        <flux:radio value="dark" icon="moon" />
        <flux:radio value="system" icon="computer-desktop" />
    </flux:radio.group>
</x-layouts.app.sidebar>
