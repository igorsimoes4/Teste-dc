@extends('adminlte::page')
@section('adminlte_css')

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}" />
    <link rel="stylesheet" href="{{ asset('fontawesome-free/css/all.min.css') }}" />

    <!-- Inclui os estilos padrão do AdminLTE -->
    @parent
@endsection
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('popper/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

@section('title', 'Editar Vendas')

@section('content_header')
    <h1 style="display: flex; justify-content:space-between; padding: 0 20px 0 20px; margin-bottom:10px;">
        Editar Vendas
    </h1>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "600",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function exibemensagem() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: 'Página Adicionada com sucesso'
            });
        };
    </script>
@endsection


@section('content')


    <div class="card">
        @if ($errors->any())
            <div class="card-header">
                <div class="alert alert-danger" role="alert">
                    Preencha os Campos Solicitados
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="card-body">
            <x-edit.form :clientes="$clientes" :produtos="$produtos" :venda="$venda" />
        </div>

    </div>

    <script>
        let produtoIndex = 1;

        function adicionarProduto() {
            const produtosDiv = document.getElementById('produtos');
            const novoProduto = document.createElement('div');
            novoProduto.classList.add('produto');
            novoProduto.classList.add('mt-2');
            novoProduto.innerHTML = `
            <div class="produto row" style="gap:10px;">
            <select name="produtos[${produtoIndex}][produto_id]" class="form-control col-6">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="produtos[0][quantidade]" class="form-control col-3 quantidade-input" placeholder="Quantidade">
                    <input type="number" name="produtos[0][preco_unitario]" class="form-control col-2 preco-unitario-input" placeholder="Preço Unitário" readonly>
                    <input type="number" name="produtos[0][preco_total]" class="form-control col-2 preco-total-input" placeholder="Preço Total" readonly>
                </div>`;
            produtosDiv.appendChild(novoProduto);
            produtoIndex++;
        }

        let parcelaIndex = 1;

        function adicionarParcela() {
            const parcelasDiv = document.getElementById('parcelas');
            const novaParcela = document.createElement('div');
            novaParcela.classList.add('parcela');
            novaParcela.innerHTML = `
                <input type="date" name="parcelas[${parcelaIndex}][data_vencimento]" class="form-control" placeholder="Data de Vencimento">
                <input type="number" name="parcelas[${parcelaIndex}][valor]" class="form-control" placeholder="Valor">
            `;
            parcelasDiv.appendChild(novaParcela);
            parcelaIndex++;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const produtoSelects = document.querySelectorAll('.produto-select');
            const quantidadeInputs = document.querySelectorAll('.quantidade-input');

            produtoSelects.forEach((select, index) => {
                select.addEventListener('change', () => {
                    updatePrice(index);
                });
            });

            quantidadeInputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    updatePrice(index);
                });
            });

            function updatePrice(index) {
                const select = produtoSelects[index];
                const quantidadeInput = quantidadeInputs[index];
                const precoUnitarioInput = document.querySelectorAll('.preco-unitario-input')[index];
                const precoTotalInput = document.querySelectorAll('.preco-total-input')[index];

                const selectedOption = select.options[select.selectedIndex];
                const precoUnitario = selectedOption.getAttribute('data-price');
                const quantidade = quantidadeInput.value;

                if (precoUnitario && quantidade) {
                    precoUnitarioInput.value = precoUnitario;
                    precoTotalInput.value = (precoUnitario * quantidade).toFixed(2);
                } else {
                    precoUnitarioInput.value = '';
                    precoTotalInput.value = '';
                }
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>


@endsection
