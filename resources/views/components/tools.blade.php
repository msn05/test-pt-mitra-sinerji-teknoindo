@push('js')
    <x-notification></x-notification>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            let ResetTable = []
            let Tables = []
            $('.table').not('#table-transaction').each(function(i, value) {
                ResetTable.push('#' + $(this).attr('id'))
                Tables[i] = $('#' + $(this).attr('id')).DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "ordering": true,
                    "pageLength": 5,
                    "info": true,
                    "aaSorting": [],
                    "border-collapse": "collapse",
                    // "width": "100%",
                    "ajax": {
                        "url": dataSource[i],
                        "type": "POST",
                        "datatype": "json",
                    },
                    "columns": tableColumn[i],
                    "columnDefs": ColumnDefs[i]
                })
            })
            // $('#table-transaction').append(
            // )
            $('#table-transaction').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ordering": true,
                "pageLength": 5,
                "footer": true,
                "info": true,
                "aaSorting": [],
                "border-collapse": "collapse",
                // "width": "100%",
                "ajax": {
                    "url": dataSource[2],
                    "type": "POST",
                    "datatype": "json",
                },
                "columns": tableColumn[2],
                "columnDefs": ColumnDefs[2],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api()
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,'Rp']/g, '') * 1 :
                            typeof i ===
                            'number' ? i :
                            0
                    }
                    totalProduct = api.column(5).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b)
                    }, 0);
                    subTotal = api.column(6).data().reduce(function(a, b) {
                        char = b.substring(0, b.length - 3).replace(/[\$,'Rp','.']/g, '')
                        return intVal(a) + intVal(char)
                    }, 0);
                    totalDiscount = api.column(7).data().reduce(function(a, b) {
                        char = b.substring(0, b.length - 3).replace(/[\$,'Rp','.']/g, '')
                        return intVal(a) + intVal(char)
                    }, 0);
                    totalShipping = api.column(8).data().reduce(function(a, b) {
                        char = b.substring(0, b.length - 3).replace(/[\$,'Rp','.']/g, '')
                        return intVal(a) + intVal(char)
                    }, 0);

                    totalPay = api.column(9).data().reduce(function(a, b) {
                        char = b.substring(0, b.length - 3).replace(/[\$,'Rp','.']/g, '')
                        return intVal(a) + intVal(char)
                    }, 0);

                    pageTotal = api.column(9, {
                            page: 'current'
                        }).data()
                        .reduce(function(a, b) {}, 0);
                    $(api.column(5).footer()).html(totalProduct);
                    $(api.column(6).footer()).html(formatNumber(subTotal));
                    $(api.column(7).footer()).html(formatNumber(totalDiscount));
                    $(api.column(8).footer()).html(formatNumber(totalShipping));
                    $(api.column(9).footer()).html(formatNumber(totalPay));
                },

            })

            $('#table-transaction').on('click', 'button#delete,button#edit', function(e) {
                e.preventDefault()
                let id = $(this).data('id')
                let route = $(this).data('route')
                let name = $(this).data('name')
                if ($(this).attr('id') === 'delete')
                    confirmData(route, id)
                if ($(this).attr('id') === 'edit') {
                    $('.modal-title').html(
                        `<i class="fa fa-plus fa-fw"></i>{{ __('Form edit status transaction') }}`
                    )
                    $('.modal-footer').css('display', '').prepend(`
                        <x-adminlte-button type="submit" id="update" class="mr-auto" theme="success" data-route="${route}/${id}" label="Update" icon="fa fa-fw fa-save"  data-value="${name}"/>
                        `)
                    $('#codeTransacton').val(id).prop('disabled', true)
                    $('#modal-options-transaction').modal('show')
                }
            })

            $(ResetTable.toString()).on('click', 'button#edit,button#add,button#delete',
                function(e) {
                    e.preventDefault()
                    let id = $(this).data('id')
                    let route = $(this).data('route')
                    let name = $(this).data('name')
                    if ($(this).attr('id') == 'edit') {
                        // console.log(name)
                        let update = $(this).data('update')
                        $('.modal-title').html(
                            `<i class="fa fa-plus fa-fw"></i>{{ __('Form edit data') }} ${name}`
                        )
                        $('.modal-footer').css('display', '').prepend(`
                        <x-adminlte-button type="submit" id="update" class="mr-auto" theme="success" data-value="${name}" data-route="${update}/${id}" label="Update" icon="fa fa-fw fa-save" />
                        `)
                        dataForm(route, id)
                        $(`#modal-${name}`).modal('show')
                    } else if ($(this).attr('id') == 'add') {
                        $('.modal-title').html(
                            `<i class="fa fa-plus fa-fw"></i>{{ __('Form add data') }} ${name}`)
                        $('.modal-footer').css('display', '').prepend(`
                    <x-adminlte-button type="submit" id="save" class="mr-auto" theme="success" data-route="${route}" label="Save" icon="fa fa-fw fa-save" data-value="${name}" />
                    `)
                        $(`#modal-${name}`).modal('show')

                    } else
                        confirmData(route, id)

                })

            function confirmData(url, id, params = null) {
                const SwalConfirm = Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You wont be able to revert this!') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                id: id,
                                params: params ?? null,
                                method: 'DELETE',
                                submit: true
                            }
                        }).done(function(data) {
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            })
                            $('#' + data.table).DataTable().draw(false)
                        }).fail(function(xhr, status, error, responseJSON) {
                            let errors = xhr.responseJSON.errors
                            let message = '';
                            $.each(errors, function(value, index) {
                                message += `${errors[value]} \n`;
                            })
                            Toast.fire({
                                icon: 'error',
                                title: message
                            })
                        })
                    }
                })
            }


            $('#modal-product,#modal-customer,#modal-options-transaction').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                let cacheButton = $(this).find('.modal-footer').children().attr('id')
                $(`button`).remove(`#${cacheButton}`)
                $("form input").not('[name="_token"]').val('').change()
                $("#form select").val('').change()
                $('.modal-title').empty()
                $('.tag-required').empty()
            });

        })

        function dataForm(url, id) {
            $.get(`${url}/${id}`, function(data) {
                $.each(data.message.text, function(value, index) {
                    $(`input[name=${value}`).val(index)
                })
                $.each(data.message.number, function(value, index) {
                    $(`input[name=${value}`).val(index)
                })
                $.each(data.message.textarea, function(value, index) {
                    $(`textarea[name=${value}`).val(index)
                })
                $.each(data.message.select, function(value, index) {
                    $(`select[name="${value}`).val(index).trigger('change');
                })

            })
        }

        $(document).on('click', 'button[id="save"],button[id="update"]', function() {
            let url = $(this).data('route')
            let button = $(this)
            let form = $('#modal-' + $(this).data('value') + ' form#form').serialize()
            send(url, button, form)
        })

        let errorMessage = (($type = null, message) => {
            Toast.fire({
                icon: $type ?? 'success',
                title: message
            })
        })

        $("input[type='number']").on("keydown", function(e) {
            var char = e.originalEvent.key.replace(/[^0-9^.^,]/, "")
            if (char.length == 0 && !(e.originalEvent.ctrlKey || e.originalEvent.metaKey)) {
                errorMessage('danger', 'Please input value greater than 0')
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

        function send(route = null, button, form) {
            action = button.attr('id')
            let Send = $.ajax({
                url: route,
                type: action == 'save' ?
                    'POST' : 'PUT',
                data: form,
                dataType: "json",
                processData: false,
            })
            Send.done(function(data) {
                Toast.fire({
                    icon: 'success',
                    title: data.message
                }).then((data) => {
                    if (action == 'save' || action ==
                        'multiple-add-insert') {
                        triggerRest()
                    }
                })
                $('#' + data.table).DataTable().draw(false)
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
        }

        function triggerRest() {
            $("#form").trigger("reset")
            $("#form select").val('').change();
        }
    </script>
@endpush
