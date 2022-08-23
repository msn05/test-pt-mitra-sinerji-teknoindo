@if ($type == 'date')
    <x-adminlte-input-date :config="$params" name="{{ $name }}"
        placeholder="{{ __('Choose a date') }} {{ Str::kebab($name) }}"
        label="{{ __('Choose a date') }}  {{ Str::kebab($name) }}">
        <x-slot name="appendSlot">
            <div class="input-group-text bg-gradient-danger">
                <i class="{{ $icon }}"></i>
            </div>
        </x-slot>
    </x-adminlte-input-date>
@endif
@if ($type == 'text' || $type == 'number')
    <x-adminlte-input name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
        igroup-size="{{ $size }}" label-class="text-danger">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-danger">
                <i class="{{ $icon }}"></i>
            </div>
        </x-slot>
    </x-adminlte-input>
@endif
