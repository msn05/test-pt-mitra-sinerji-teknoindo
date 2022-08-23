<x-adminlte-modal id="modal-{{ $name }}" title="Account Policy" size="{{ $size }}" theme="teal"
    icon="fas fa-bell" v-centered static-backdrop>
    <form method="POST" id="form">
        @csrf
        {{ $slot }}
    </form>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" />
    </x-slot>
</x-adminlte-modal>
