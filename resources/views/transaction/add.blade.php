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
    <form method="POST" id="formTransaction" class="form-input mt-2">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-card title="Transaction" class="h-100" theme="line" header-class="bg-light">
                    @foreach ($form['transaction'] as $item)
                        <x-input-group-label :config="$item">
                        </x-input-group-label>
                    @endforeach
                </x-adminlte-card>
            </div>
            <div class="col-md-6">
                <x-adminlte-card title="Customer" class="h-100" theme="line" header-class="bg-light">
                    <div class="dependency-customer">
                        @foreach ($form['dependencyCustomer'] as $val)
                            @if ($val['name'] === 'code')
                                <div class="form-group row">
                                    <label for="setCustomer"
                                        class="col-sm-4 col-form-label">{{ Str::ucfirst($val['name']) }}</label>
                                    <div class="col-sm-6">
                                        <input type="{{ $val['type'] }}" name="{{ $val['name'] }}" readonly
                                            class="form-control plaintext" id="{{ $val['name'] }}"
                                            placeholder="Set Customer this">
                                    </div>
                                    <div class="col-sm-2">
                                        <x-adminlte-button theme="primary" icon="fab fa-lg fa fa-search" id="setCustomer" />
                                    </div>
                                </div>
                            @else
                                <div class="noteCustomer">
                                    <x-input-group-label :config="$val">
                                    </x-input-group-label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-md-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0 text-dark">Data Transaction</h4>
                    </div>
                    <div class="card-body">
                        <table id='table-transaction-sale'
                            class="table table-hover table-bordered table-stripped dt-responsive display nowarp"
                            cellspacing="1" cellpading="2" width="100%">
                            <tfoot>
                                <tr>
                                    <th colspan="9" style="text-align:right">Total:</th>
                                    <th id="TotalsPiceProduct"></th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="text-align:right">{{ __('Discount') }}:</th>
                                    <th>
                                        <input type="number" name="discountTransaction" class="input form-control"
                                            value="0">
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="text-align:right">{{ __('Shipping Cost') }}:</th>
                                    <th>
                                        <input type="number" name="shippingCost" class="input form-control" value="0">
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="text-align:right">{{ __('Grand Total') }}:</th>
                                    <th>
                                        <input type="text" name="grandTotal" class="input form-control" readonly>
                                    </th>
                                </tr>
                            </tfoot>
                            <thead>
                                <tr>
                                    <th rowspan="2" width="7%">
                                        <button type="button" class="btn btn-primary btn-block btn-sm" id="add"><i
                                                class="fa fa-plus"></i></button>
                                    </th>
                                    <th rowspan="2">{{ __('No') }}</th>
                                    <th rowspan="2" width="10%">{{ __('Code Product') }}</th>
                                    <th rowspan="2">{{ __('Name Product') }}</th>
                                    <th rowspan="2" width="7%">{{ __('Qty Sale') }}</th>
                                    <th rowspan="2" width="10%">{{ __('Normal Price') }}</th>
                                    <th colspan="2">{{ __('Discount') }}</th>
                                    <th rowspan="2">{{ __('Sub Total') }}</th>
                                    <th rowspan="2" width="20%">{{ __('Grand Total') }}</th>
                                </tr>
                                <tr>
                                    <th>%</th>
                                    <th>Rp</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="float-right">
                            <x-adminlte-button type="submit" id="saveTransaction" class="mr-auto" theme="success"
                                label="Save" icon="fa fa-fw fa-save" />
                            <a href="{{ route('test-jp/transaction') }}">
                                <x-adminlte-button type="button" id="cancel" class="mr-auto" theme="danger"
                                    label="Cancel" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <x-modal name="customer">
        <div class="customer">
            @foreach ($form['customer'] as $item)
                <x-input-group-label :config="$item">
                </x-input-group-label>
            @endforeach
        </div>
    </x-modal>
    <x-modal name="transaction" size="lg">
        <form id="insertProductAdd">
            <div class="row">
                <div class="col-md-6">
                    @foreach ($form['product'] as $item)
                        <x-input-group-label :config="$item">
                        </x-input-group-label>
                    @endforeach
                </div>
            </div>
            <div class="dependency-product" style="display: none;">
                <div class="form-group row">
                    @foreach ($form['dependencyProduct'] as $item)
                        <div class="col-md-6">
                            <x-input-group-label :config="$item">
                            </x-input-group-label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                @foreach ($form['transactionPrduct'] as $item)
                    <div class="col-md-6">
                        <x-input-group-label :config="$item">
                        </x-input-group-label>
                    </div>
                @endforeach
            </div>
        </form>
    </x-modal>
@stop
@push('js')
    <x-notification></x-notification>
    <script>
        let table
        let transactionData = []

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            let formatNumber = function(data) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(data)
            }
            let intVal = function(i) {
                let val = i.substring(0, i.length - 3).replace(/[\$,'Rp','.']/g, '')
                return typeof val === 'string' ? val.replace(/[\$,'Rp']/g, '') * 1 :
                    typeof val ===
                    'number' ? val :
                    0
            }


            let Column = [{
                    'data': function(row, data) {
                        return `<button class="btn btn-warning btn-sm ActionTable" data-id="${row.id}"  id="edit"><i class="fa fa-pen"></i></button> 
                    <button class="btn btn-danger btn-sm ActionTable"  id="delete" data-id="${row.id}"><i class="fa fa-trash"></i></button>
                    `
                    },
                },
                {
                    'data': function(row, data) {
                        return `<label>${row.id} <input type="hidden" class="idRows" readonly name="id" value="${row.id}"> </label>`
                    }
                },
                {
                    'data': function(row, data) {
                        return `<label>${row.codeProduct} <input type="hidden" class="idCodeProduct" id="codeProduct" readonly name="product[${row.id}][codeProduct]" value="${row.codeProduct}"> </label>`
                    }
                },
                {
                    'data': 'nameProduct'
                },
                {
                    'data': function(row, data) {
                        return `<label>${row.QtySale} <input type="hidden" readonly name="product[${row.id}][QtySale]" value="${row.QtySale}"> </label>`
                    }
                },
                {
                    'data': function(row, data) {
                        Price = formatNumber(parseInt(row.Price))
                        return `<label>${Price} <input type="hidden" readonly name="product[${row.id}][Price]" value="${row.Price}"> </label>`
                    }
                },
                {
                    'data': function(row, data) {
                        return `<label>${row.Discount} <input type="hidden" readonly name="product[${row.id}][Discount]" value="${row.Discount}"> </label>`
                    }
                },

                {
                    'data': function(row, data) {
                        Price = formatNumber(parseInt(row.Price * row.Discount / 100) * row.QtySale)
                        return `<label>${Price} <input type="hidden" readonly name="DiscountRp[]" value="${Price}"> </label>`
                    }
                },
                {
                    'data': function(row, data) {
                        Price = formatNumber(parseInt(row.Price - (row.Price * row.Discount / 100)))
                        return `<label>${Price} <input type="hidden" readonly name="sumPriceDiscount[]" value="${Price}"> </label>`
                    }
                },
                {
                    'data': function(row, data) {
                        let price = parseInt((row.Price - (row.Price * row.Discount / 100)))
                        Price = formatNumber(parseInt(price * row.QtySale))
                        return Price
                    }
                },
            ]

            table = $('#table-transaction-sale').DataTable({
                "responsive": true,
                "ordering": true,
                "pageLength": 5,
                "bPaginate": false,
                "footer": true,
                "info": false,
                "searching": false,
                "aaSorting": [],
                "border-collapse": "collapse",
                data: transactionData,
                columns: Column,
                "initComplate": () => {
                    actionTableJson()
                },
                "drawCallback": () => {
                    actionTableJson()
                },
                "fnRowCallback": function(row, data, start, end, display) {
                    let api = this.api()
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,'Rp']/g, '') * 1 :
                            typeof i ===
                            'number' ? i :
                            0
                    }
                    total = api.column(9).data().reduce(function(a, b) {
                        char = b.substring(0, b.length - 3).replace(/[\$,'Rp','.']/g, '')
                        return intVal(a) + intVal(char)
                    }, 0);
                    $(api.column(9).footer()).html(formatNumber(total))
                },
            })

            function actionTableJson() {
                $('.ActionTable').on('click', function(e) {
                    e.preventDefault()
                    let id = $(this).data('id')
                    if ($(this).attr('id') === 'delete') {
                        $($(this)).parents('tr').addClass('selected');
                        const SwalConfirm = Swal.fire({
                            title: "{{ __('Are you sure?') }}",
                            text: "{{ __('You wont be able to revert this!') }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: "{{ __('Yes, delete it!') }}"
                        }).then((result) => {
                            if (result.value) {
                                Toast.fire({
                                    icon: 'success',
                                    title: "{{ __('Success remove data.!') }}"
                                })
                                table.row('.selected').remove().draw();
                            }
                        })
                    }
                    if ($(this).attr('id') === 'edit') {
                        $('.modal-title').html(
                            `<i class="fa fa-plus fa-fw"></i>{{ __('Form edit data') }}`
                        )
                        $('.modal-footer').html(`
                        <x-adminlte-button type="submit" id="update" class="mr-auto" theme="success"  label="Update" icon="fa fa-fw fa-save" data-id=${id}/>
                        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" />
                        `)
                        let product = `product[${id}][codeProduct]`
                        let qty = `product[${id}][QtySale]`
                        let discount = `product[${id}][Discount]`
                        let Price = `product[${id}][Price]`
                        product = $(`input[name="${product}"]`).val()
                        qty = $(`input[name="${qty}"]`).val()
                        discount = $(`input[name="${discount}"]`).val()
                        total = $(`input[name="${Price}"]`).val()
                        val = $("<option selected></option>").val(product).text(product).attr(
                            'data-route', `test-jp/product-show/${product}`)
                        $(`select[id="product"]`).append(val).trigger('change')
                        // $(`input[name="discount"]`).val(filter[0].Discount)
                        $(`input[name="discount"]`).val(discount)
                        $(`input[name="qty"]`).val(qty)
                        $(`input[name="total"]`).val(formatNumber(total * qty))
                        // $('input[name="total"]').val(formatNumber(intVal($('input[name="price"]').val()) *
                        //     $(`input[name="qty"]`).val()))
                        $('#modal-transaction').modal('show');
                    }
                })
            }
            let Customer = []
            $('label[for="codeProduct"]').text('Code')
            $('input[id="name"],input[id="phone"],input[id="price"],input[id="code"],input[name="codeProduct"]')
                .prop('disabled', true)
            $('select').each(function(i, el) {
                let name = $(this).attr('id')
                let route;
                if (typeof name !== "undefined") {
                    route = $(this).data('route')
                }
                $(`#${name}`).select2({
                    allowClear: true,
                    theme: 'bootstrap4',
                    placeholder: "{{ __('Search for a data ') }}" + $(this).attr('placeholder'),
                    "ajax": {
                        "datatype": 'JSON',
                        "url": route,
                        "data": function(response) {
                            return {
                                search: $.trim(response.term)
                            }
                        },

                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                }),
                            }
                        },
                        cache: true
                    },

                })
            })
            $('button[id="setCustomer"],button[id="add"]').on('click', function(e) {
                e.preventDefault()
                if ($(this).attr('id') == 'add') {
                    $('.modal-title').html(
                        `<i class="fa fa-plus fa-fw"></i>{{ __('Form add transaction product') }}`)
                    $('.modal-footer').css('display', '').prepend(`
                        <x-adminlte-button type="submit" id="save" class="mr-auto" theme="success" label="save" icon="fa fa-fw fa-save" />
                        `)
                    $('#modal-transaction').modal('show');
                } else {

                    $('.modal-title').html(
                        `<i class="fa fa-plus fa-fw"></i>{{ __('Form set customer') }}`)
                    $('#modal-customer').modal('show');
                }
            })

            $('#customer,#product').change(function() {
                let id = $(this).val()
                let name = $(this).attr('id')
                if (name == 'product') {
                    if (id == null) $(`div.dependency-product`).css('display', 'none')
                    else $(`div.dependency-product`).css('display', '')
                }
                if (id !== null)
                    $.get(`${name}-show/${id}`, function(data) {
                        $.each(data.message.text, function(value, index) {
                            $(`div.dependency-${name} input[id=${value}]`).val(index)
                        })
                        $.each(data.message.number, function(value, index) {
                            if (name == 'product' && value == 'price')
                                $(`div.dependency-${name} input[id=${value}]`).eq(0).attr(
                                    'type',
                                    'text').val(formatNumber(index))
                            else
                                $(`div.dependency-${name} input[id=${value}]`).val(index)
                        })
                    })
            })

            $('input[name="total"]').prop('disabled', true)


            $("input[type='number']").on("keydown",
                function(e) {
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

                if ($(this).attr('name') === 'shippingCost') {
                    $('input[name="grandTotal"]').val(formatNumber(intVal($(
                        '#TotalsPiceProduct').text()) + parseFloat($(
                        'input[name="shippingCost"]').val()) - parseFloat($(
                        'input[name="discountTransaction"]').val())))
                }
            })

            let errorMessage = function(message) {
                return Toast.fire({
                    icon: 'error',
                    title: message
                })
            }

            $('input[name="qty"]').on('keyup', function(e) {
                if ($(this).val() == 0)
                    errorMessage('Please input value greater than 0')
                let product = $('select[name="product"]').val()
                if (product == null || product == '')
                    errorMessage('Please select product to calculate.!')
                else
                    $('input[name="total"]').val(formatNumber(intVal($('input[name="price"]').val()) * $(
                        this).val()))
                e.preventDefault()
            })

            function inputValTransaction(rowCount) {
                transactionData = {
                    'id': rowCount,
                    'codeProduct': $('input[name="codeProduct"]').val(),
                    'nameProduct': $('.dependency-product input[name="name"]').val(),
                    'QtySale': $('input[name="qty"]').val(),
                    'Price': intVal($('input[name="price"]').val()),
                    'Discount': parseFloat($('input[name="discount"]').val()),
                }
            }

            $('#modal-transaction').on('click', 'button#save,button#update', function(e) {
                e.preventDefault()
                let idVal = $('input[name="codeProduct"]').val()
                let filters = transactionData.length
                // console.log(filters)
                let rowCount = 0
                if (filters === undefined) {
                    let CodeProduct = $('#table-transaction-sale input.idCodeProduct')
                    CodeProduct = [...CodeProduct].map(val => val.value)
                    let OldCode = CodeProduct.filter(val => val == idVal)
                    if ($(this).attr('id') == 'update') {
                        if (OldCode.length == 1 || OldCode.length == 0) {
                            inputValTransaction($(this).data('id'))
                            Toast.fire({
                                icon: 'success',
                                title: "{{ __('Success update data.!') }}"
                            })
                            table.row(($(this).data('id') - 1)).remove()
                            table.row.add(transactionData).draw()
                        } else errorMessage('Data already')
                    } else {
                        if (OldCode.length > 0)
                            errorMessage('Data already.!')
                        else {
                            rowCount = parseInt($('#table-transaction-sale input[name="id"]:last').val())
                            rowCount += 1
                            inputValTransaction(rowCount)
                            Toast.fire({
                                icon: 'success',
                                title: "{{ __('Success add data.!') }}"
                            })
                            table.row.add(transactionData).draw()
                        }
                    }
                } else {
                    rowCount += 1
                    inputValTransaction(rowCount)

                    Toast.fire({
                        icon: 'success',
                        title: "{{ __('Success add data.!') }}"
                    })
                    table.row.add(transactionData).draw()
                }

                $('form#form').trigger('reset')
                $('#product').val(null).change()
            })

            $('#saveTransaction').on('click', function(e) {
                e.preventDefault()
                let codeProducts = []
                let QtyProducts = []
                let DiscountProducts = []
                let CodeProduct = $('#table-transaction-sale input.idCodeProduct')
                codeProducts = [...CodeProduct].map(val => val.value)

                $.each(codeProducts, function(val, i) {
                    QtyProducts.push($(`input[name="product[${val+1}][QtySale]"`).val())
                    DiscountProducts.push($(`input[name="product[${val+1}][Discount]"`).val())
                })
                let data = {
                    'codeTransaction': $('input[name="codeTransaction"]').val(),
                    'dateTransaction': $('input[name="date_of_sale"]').val(),
                    'codeCustomer': $('input[name="code"]').val(),
                    'TotalDiscount': $('input[name="discountTransaction"]').val(),
                    'shippingCost': $('input[name="shippingCost"]').val(),
                    'CodeProduct': codeProducts,
                    'QtyProduct': QtyProducts,
                    'DiscountProduct': DiscountProducts,
                    'GrandTotal': intVal($('input[name="grandTotal"]').val()),
                    'SubTotal': intVal($('#TotalsPiceProduct').text())
                }
                $.ajax({
                    url: "{{ route('test-jp/transaction-store') }}",
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                }).done(function(data) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    }).then((data) => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    })
                }).fail(function(xhr, status, error, responseJSON) {
                    let errors = xhr.responseJSON.errors
                    let message = ''
                    if (typeof errors == 'string')
                        message += errors
                    else
                        $.each(errors, function(value, index) {
                            message += `${errors[value]} \n`;
                        })
                    Toast.fire({
                        icon: 'error',
                        title: message
                    })
                })
            })

            $('#modal-customer,#modal-transaction').on('hidden.bs.modal', function() {
                $('#save,#update').remove()
                // $('.modal-footer #update').remove()
                $('#product').val(null).change()
            })
        })

        // console.log(transactionData.length)
    </script>
@endpush
