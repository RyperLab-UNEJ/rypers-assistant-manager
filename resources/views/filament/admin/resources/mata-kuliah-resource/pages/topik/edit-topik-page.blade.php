<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="mt-5">
            <x-filament-panels::form.actions :actions="$this->getFormActions()" />
        </div>
    </form>
</x-filament-panels::page>
