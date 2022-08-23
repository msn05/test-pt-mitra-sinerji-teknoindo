@if ($type === 'date')
    @php
        $config = [
            'format' => 'YYYY-MM-DD HH:mm',
            'minDate' => "js:moment().format('YYYY')",
            'maxDate' => "js:moment().endOf('month')",
        ];
    @endphp
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-4 col-form-label">{{ $labelName }}</label>
        <div class="col-sm-8">
            <x-adminlte-input-date :config="$config" name="{{ $name }}">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-gradient-danger">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-input-date>
        </div>
    </div>
@endif
@if ($type == 'select')
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-4 col-form-label">{{ $labelName }}</label>
        <div class="col-sm-8">
            <x-adminlte-select2 name="{{ $name }}" placeholder=" {{ $placeholder }}"
                data-name="{{ $name }}" igroup-size="md" data-route="{{ $route }}"
                label-class="text-lightblue">
                <x-adminlte-options :options="$value" placeholder="Select an option..." />
            </x-adminlte-select2>
        </div>
    </div>
@endif
@if ($type == 'text' || $type == 'number')
    <div class="form-group row">
        <label for="{{ $name }}" class="col-sm-4 col-form-label">{{ $labelName }}</label>
        <div class="col-sm-8">
            <input type="{{ $type }}" {{ $typeInput }} name="{{ $name }}"
                {{ $type == 'number' ? 'min=1 ' : '' }} class="form-control plaintext" id="{{ $name }}"
                value="{{ $value ?? '' }}" placeholder="{{ $placeholder }}">
        </div>
    </div>
@endif

@push('js')
    <script>
        $(document).ready(function() {
            $("input[type='number']").on("keydown", function(e) {
                var char = e.originalEvent.key.replace(/[^0-9^.^,]/, "")
                if (char.length == 0 && !(e.originalEvent.ctrlKey || e.originalEvent.metaKey)) {
                    e.preventDefault()
                }
            })

            $("input[type='number']").focusout(function(e) {
                if (!isNaN(this.value) && this.value.length != 0) {
                    this.value = Math.abs(parseFloat(this.value))
                } else {
                    this.value = 1
                }
            })
        })
    </script>
@endpush
