@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)

@section('content_header')
    <div class="d-flex">
        <h1 class="m-0 text-dark">{{ __('Transaction') }}</h1>
    </div>
@stop


@section('content')
    <div class="row">
        @foreach ($table as $data)
            @if ($data === 'product' || $data === 'customer')
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4 class="m-0 text-dark">Data {{ $data }}</h4>
                        </div>
                        <div class="card-body">
                            <table id='table-{{ $data }}'
                                class="table table-hover table-bordered table-stripped dt-responsive nowrap" width="100%">
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12 mt-2">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="m-0 text-dark">Data {{ $data }}</h4>
                        </div>
                        <div class="card-body">
                            <table id='table-{{ $data }}'
                                class="table table-hover table-bordered table-stripped dt-responsive nowrap" width="100%">
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align:right">Total:</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        @foreach ($forms as $forminput => $key)
            @if ($forminput == 'product')
                <x-modal name="product">
                    @foreach ($key as $input)
                        <x-form-input :config="$input"></x-form-input>
                    @endforeach
                </x-modal>
            @endif
            @if ($forminput == 'customer')
                <x-modal name="customer">
                    @foreach ($key as $input)
                        <x-form-input :config="$input"></x-form-input>
                    @endforeach
                </x-modal>
            @endif
            @if ($forminput == 'productOptionsStatus')
                <x-modal name="options-transaction">
                    <div class="col-md-12">
                        @foreach ($key as $input)
                            @if ($input['type'] != 'textarea')
                                <x-input-group-label :config="$input">
                                </x-input-group-label>
                            @endif
                            @if ($input['type'] == 'textarea')
                                <div class="reason">
                                    <div class="form-group row">
                                        <label for="{{ $input['placeholder'] }}"
                                            class="col-sm-4 col-form-label">{{ $input['placeholder'] }}</label>
                                        <div class="col-sm-8">
                                            <x-adminlte-textarea name="{{ $input['name'] }}"
                                                placeholder="Insert {{ $input['value'] }} ..." />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </x-modal>
            @endif
        @endforeach
    </div>

@stop
@push('js')
    <script>
        let formatNumber = function(data) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(data)
        }
        let formatPhoneNumber = (str) => {
            number = str.replace(/(\d{3})(\d{3})(\d{1,3})(\d.*)/, "(0$1)-****-***-$4");
            return number
        };
        $('table').find('thead tr th[class="sorting_disabled"]').css('width', '0px')
        $('div.reason').css('display', 'none')
        $('select#status').change(function() {
            $('div.reason').css('display', '')
        })

        let dataSource = ['product-data', 'customer-data', 'transaction-data']

        let tableColumn = [
            [{
                    'data': function(row, data) {
                        return `<input type="checkbox" name="checkid" value="${row.code}"/>`
                    },
                    "width": "2%"
                },
                {
                    'title': '<button type="button" class="btn btn-primary btn-block btn-sm" id="add" data-route="product-add" data-name="product"><i class="fa fa-plus"></i></button>',
                    'data': 'action',
                    "width:": "2%"
                },
                {
                    'title': "{{ __('Code ') }}",
                    'data': 'code',
                    'name': 'code',
                    'width': "10%"
                },
                {
                    'title': "{{ __('Name') }}",
                    'data': 'name',
                    'name': 'name'
                },
                {
                    'title': "{{ __('Price') }}",
                    'data': function(row, data) {
                        return formatNumber(row.price)
                    },
                    'name': 'price'
                },
            ],
            [{
                    'data': function(row, data) {
                        return `<input type="checkbox" name="checkid" value="${row.code_teachers}"/>`
                    },
                    "width": "2%"
                },
                {
                    'title': '<button type="button" class="btn btn-primary btn-block btn-sm" id="add" data-route="customer-add" data-name="customer"><i class="fa fa-plus"></i></button>',
                    'data': 'action',
                    "width:": "2%"
                },
                {
                    'title': "{{ __('Code ') }}",
                    'data': 'code',
                    'name': 'code',
                    'width': "10%"
                },
                {
                    'title': "{{ __('Name') }}",
                    'data': 'name',
                    'name': 'name'
                },
                {
                    'title': "{{ __('Phone') }}",
                    'data': function(row, data) {
                        return formatPhoneNumber(row.phone)
                    },
                    'name': 'phone'
                },
            ],
            [{
                    'data': function(row, data) {
                        return `<input type="checkbox" name="checkid" value="${row.code}"/>`
                    },
                    "width": "2%"
                },
                {
                    'title': `<a href="{{ route('test-jp/transaction-add') }} " type="button" class="btn btn-primary btn-block btn-sm" id="add"><i class="fa fa-plus"></i></a>`,
                    'data': 'action',
                    "width:": "2%"
                },
                {
                    'title': "{{ __('No Transaction ') }}",
                    'data': 'codeTransaction',
                    'name': 'codeTransaction',
                    'width': "10%"
                },

                {
                    'title': "{{ __('Date of sale') }}",
                    'data': 'dateTransaction',
                    'name': 'dateTransaction'
                },
                {
                    'title': "{{ __('Name Customer') }}",
                    'data': 'CustomerName',
                    'name': 'customer.name'
                },
                {
                    'title': "{{ __('Total Qty') }}",
                    'data': 'totalQty',
                    'width': '10%'
                    // 'name': 'CustomerName'
                },
                {
                    'title': "{{ __('Sub Total') }}",
                    'data': function(row, data) {
                        return formatNumber(parseFloat(row.subTotal))
                    },
                },
                {
                    'title': "{{ __('Total Discount Sale') }}",
                    'data': function(row, data) {
                        return formatNumber(row.totaDiscountSale);
                    },
                },
                {
                    'title': "{{ __('Shipping cost') }}",
                    'data': function(row, data) {
                        return formatNumber(row.shippingCost);
                    },
                },
                {
                    'title': "{{ __('Grand Total') }}",
                    'data': function(row, data) {
                        let Cost = (parseInt(row.subTotal) + parseInt(row.shippingCost))
                        return formatNumber(parseInt(Cost) - parseInt(row.totaDiscountSale))
                    },
                },
            ],
        ];
        let Column = [{
            'targets': [0, 1],
            'orderable': false,
            "searchable": false,
        }]
        let ColumnDefs = [
            Column, Column, [{
                'targets': [0, 1, 5, 6, 7, 8, -0],
                'orderable': false,
                "searchable": false
            }]
        ]
    </script>
    <script>
        function adjustTable() {
            $($.fn.dataTable.tables(true)).css('width', '100%')
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw()
        }
    </script>

    <x-tools></x-tools>
@endpush
