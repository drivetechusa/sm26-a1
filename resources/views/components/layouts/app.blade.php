<x-layouts.app.sidebar :title="$title ?? null">
    {{ $header = null }}
    <flux:main>
        {{ $slot }}
    </flux:main>
    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="fixed bottom-4 right-4 z-50">
        <flux:radio value="light" icon="sun" />
        <flux:radio value="dark" icon="moon" />
        <flux:radio value="system" icon="computer-desktop" />
    </flux:radio.group>
</x-layouts.app.sidebar>
