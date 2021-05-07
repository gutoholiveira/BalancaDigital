<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Balança') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="row mb-4">
                    <div class="mb-2 col-md-2">
                        <label style="font-size:10.5pt; color:#151515;">Quantidade</label>
                        <x-jet-input id="quantidade" class="block mt-1 w-full" type="number" min="1" value="1" />
                    </div>
                    <div class="mb-2 col-md-4">
                        <label style="font-size:10.5pt; color:#151515; margin-bottom:5px;">Código</label>
                        <select id="codigo" class="form-control select2codigo" name="codigo"
                            style="width: 100%;"></select>
                    </div>
                    <div class="mb-2 col-md-6">
                        <label style="font-size:10.5pt; color:#151515; margin-bottom:5px;">Descrição</label>
                        <select id="descricao" class="form-control select2descricao" name="descricao"
                            style="width: 100%;"></select>
                    </div>
                </div>

                <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar" style="height:500px;">
                    <table class="table table-hover table-borderless table-sm" style="font-size: 10pt;">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Peso Unitário</th>
                                <th>Valor Unitário</th>
                                <th>Quantidade</th>
                                <th>Peso Total</th>
                                <th>Valor Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="itens">
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-danger" id="limpar">Limpar Lista</button>
                    </div>
                    <span class="col" style="font-size:10pt;" id="peso_total">
                        Peso total: <strong style="font-size:14pt;">0</strong>
                    </span>
                    <span class="col" style="font-size:10pt;" id="valor_total">
                        Valor total: R$ <strong style="font-size:14pt;">0</strong>
                    </span>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    var peso = 0;
        var valor = 0;
    $(document).ready(function() {
        /* Formatação para o Select2 */
        $('.select2').select2();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        var produto;


        $('.select2codigo').select2({
            language: {
                inputTooShort: function() {
                    return "Digite 1 caracter";
                },
                noResults: function() {
                    return "Nenhum produto encontrado";
                }
            },
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('produto.getCodigo') }}",
                dataType: "json",
                type: "GET",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.codigo
                            };
                        })
                    };
                },
                cache: true,
            }
        });

        $('.select2descricao').select2({
            language: {
                inputTooShort: function() {
                    return "Digite 3 caracteres.";
                },
                noResults: function() {
                    return "Nenhum produto encontrado";
                }
            },
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('produto.getDescricao') }}",
                dataType: "json",
                type: "GET",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.descricao
                            };
                        })
                    };
                },
                cache: true,
            }
        });
        var cont = 0;

        $('#codigo').on('change', function(e) {
            $.ajax({
                url: "{{ route('produto.getId') }}",
                data: {
                    id: this.value,
                },
                type: 'GET',
                success: function(data) {
                    $('#itens').append(
                        '<tr id="linha' + cont + '">' +
                        '<td id="cod' + cont + '">' + data.codigo + '</td>' +
                        '<td id="desc' + cont + '">' + data.descricao + '</td>' +
                        '<td id="peso' + cont + '">' + data.peso + 'g</td>' +
                        '<td id="valor' + cont + '">R$ ' + data.valor + '</td>' +
                        '<td id="qtd' + cont + '">' + $('#quantidade').val() + '</td>' +
                        '<td id="peso_tot' + cont + '">' + (data.peso * $('#quantidade').val()) + 'g</td>' +
                        '<td id="valor_tot' + cont + '">R$ ' + (data.valor * $('#quantidade').val()) + '</td>' +
                        '<td><i class="fas fa-trash m-1 remove_field" style="color:red; cursor:pointer;" title="remover" id="remover' +
                        data.id + '" onClick="remover(' + cont + ');"></i></td>' +
                        '</tr>'
                    );
                    cont++;

                    peso = (peso + (data.peso * $('#quantidade').val()));
                    valor = (valor + (data.valor * $('#quantidade').val()));

                    var vt = document.getElementById("valor_total");
                    while (vt.hasChildNodes()) {
                        vt.removeChild(vt.firstChild);
                    }
                    var pt = document.getElementById("peso_total");
                    while (pt.hasChildNodes()) {
                        pt.removeChild(pt.firstChild);
                    }

                    $('#peso_total').append(
                        'Peso total: <strong style="font-size:14pt;" id="ptot">' +
                        peso +
                        'g</strong>');
                    $('#valor_total').append(
                        'Valor Total: R$ <strong style="font-size:14pt;" id="vtot">' +
                        valor +
                        '</strong>');

                    document.getElementById('quantidade').value = "1";
                    var op1 = document.getElementById("codigo");
                    while (op1.hasChildNodes()) {
                        op1.removeChild(op1.firstChild);
                    }

                    var op2 = document.getElementById("descricao");
                    while (op2.hasChildNodes()) {
                        op2.removeChild(op2.firstChild);
                    }
                },
                error: function(data) {}
            });
        });

        $('#descricao').on('change', function(e) {
            $.ajax({
                url: "{{ route('produto.getId') }}",
                data: {
                    id: this.value,
                },
                type: 'GET',
                success: function(data) {
                    $('#itens').append(
                        '<tr id="linha' + cont + '">' +
                        '<td id="cod' + cont + '">' + data.codigo + '</td>' +
                        '<td id="desc' + cont + '">' + data.descricao + '</td>' +
                        '<td id="peso' + cont + '">' + data.peso + 'g</td>' +
                        '<td id="valor' + cont + '">R$ ' + data.valor + '</td>' +
                        '<td id="qtd' + cont + '">' + $('#quantidade').val() + '</td>' +
                        '<td id="peso_tot' + cont + '">' + (data.peso * $('#quantidade').val()) + 'g</td>' +
                        '<td id="valor_tot' + cont + '">R$ ' + (data.valor * $('#quantidade').val()) + '</td>' +
                        '<td><i class="fas fa-trash m-1 remove_field" style="color:red; cursor:pointer;" title="remover" id="remover' +
                        data.id + '" onClick="remover(' + cont + ');"></i></td>' +
                        '</tr>'
                    );
                    cont++;

                    peso = (peso + (data.peso * $('#quantidade').val()));
                    valor = (valor + (data.valor * $('#quantidade').val()));

                    var vt = document.getElementById("valor_total");
                    while (vt.hasChildNodes()) {
                        vt.removeChild(vt.firstChild);
                    }
                    var pt = document.getElementById("peso_total");
                    while (pt.hasChildNodes()) {
                        pt.removeChild(pt.firstChild);
                    }
                    var op1 = document.getElementById("codigo");
                    while (op1.hasChildNodes()) {
                        op1.removeChild(op1.firstChild);
                    }

                    var op2 = document.getElementById("descricao");
                    while (op2.hasChildNodes()) {
                        op2.removeChild(op2.firstChild);
                    }

                    $('#peso_total').append(
                        'Peso total: <strong style="font-size:14pt;" id="ptot">' +
                        peso +
                        'g</strong>');
                    $('#valor_total').append(
                        'Valor total: R$ <strong style="font-size:14pt;" id="vtot">' +
                        valor +
                        '</strong>');

                    document.getElementById('quantidade').value = "1";
                },
                error: function(data) {}
            });
        });

        $('#limpar').on('click', function() {
            peso = 0;
            valor = 0;

            var lista = document.getElementById("itens");
            while (lista.hasChildNodes()) {
                lista.removeChild(lista.firstChild);
            }

            var vt = document.getElementById("valor_total");
            while (vt.hasChildNodes()) {
                vt.removeChild(vt.firstChild);
            }
            var pt = document.getElementById("peso_total");
            while (pt.hasChildNodes()) {
                pt.removeChild(pt.firstChild);
            }
            var op1 = document.getElementById("codigo");
            while (op1.hasChildNodes()) {
                op1.removeChild(op1.firstChild);
            }

            var op2 = document.getElementById("descricao");
            while (op2.hasChildNodes()) {
                op2.removeChild(op2.firstChild);
            }

            $('#peso_total').append('Peso total: <strong style="font-size:14pt;" id="ptot">' +
                peso +
                'g</strong>');
            $('#valor_total').append(
                'Valor total: R$ <strong style="font-size:14pt;" id="vtot">' + valor +
                '</strong>');
        });
    });


    function remover(data) {
        var val = document.getElementById('valor' + data).innerHTML;
        val = val.replace('R$ ', '');
        val = parseFloat(val);

        var qtd = parseInt(document.getElementById('qtd' + data).innerHTML);

        var pes = document.getElementById('peso' + data).innerHTML;
        pes = pes.replace('g', '');
        pes = parseInt(pes);

        var peso_total = document.getElementById('ptot').innerHTML;
        peso_total = peso_total.replace('g', '');
        peso_total = parseFloat(peso_total);

        var valor_total = document.getElementById('vtot').innerHTML;
        valor_total = parseFloat(valor_total);

        peso = (peso_total - (pes * qtd));
        valor = (valor_total - (val * qtd));

        var vt = document.getElementById("valor_total");
        while (vt.hasChildNodes()) {
            vt.removeChild(vt.firstChild);
        }
        var pt = document.getElementById("peso_total");
        while (pt.hasChildNodes()) {
            pt.removeChild(pt.firstChild);
        }

        $('#peso_total').append('Peso total: <strong style="font-size:14pt;" id="ptot">' +
            peso +
            'g</strong>');
        $('#valor_total').append(
            'Valor total: R$ <strong style="font-size:14pt;" id="vtot">' + valor +
            '</strong>');

        $('#linha' + data).remove();

    }

</script>
