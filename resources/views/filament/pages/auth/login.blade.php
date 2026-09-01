<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-6 flex flex-col items-center gap-2">
        <p class="text-sm text-gray-500">Or log in via Telegram</p>

        <script async src="https://telegram.org/js/telegram-widget.js?22"
            data-telegram-login="{{ config('services.telegram.bot_username') }}"
            data-size="large"
            data-auth-url="{{ route('telegram.callback') }}"
            data-request-access="write">
        </script>
    </div>
</x-filament-panels::page.simple>