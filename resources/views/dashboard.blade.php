<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-36 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex justify-between items-center p-2 h-36">
                @for($i=0;$i<6;$i++)
                    @php
                        $day = now()->subDays($i);
                    @endphp
                    <livewire:dashboard.daily-payments :date="$day->format('Y-m-d')" />
                @endfor
            </div>
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 h-[325px]">
            <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:recent-inquiries />
            </div>
            <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:recent-enrollments />
            </div>
            <div class="relative h-[300px] overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <livewire:current-mileages />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <livewire:recent-notes />
        </div>
    </div>
</x-layouts.app>
