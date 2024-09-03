
<x-filament::modal>
    <x-slot name="header">
        Receipt
    </x-slot>

    <x-slot name="content">
        <img src="{{ $receiptUrl }}" alt="Receipt Image" class="w-full h-auto" />
    </x-slot>

    <x-slot name="footer">
        <x-filament::button wire:click="$toggle('open')" type="button">
            Close
        </x-filament::button>
    </x-slot>
</x-filament::modal>