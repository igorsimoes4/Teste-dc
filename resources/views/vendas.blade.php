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

@section('plugins.Chartjs', true)

@section('title', 'Vendas')

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <meta http-equiv="refresh" content="300">
    <style>
        table {
            overflow-y: scroll;
        }

        .form-control:disabled {
            background-color: transparent;
            border-color: #949494;
        }
    </style>
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    "showDuration": "1200",
                    "hideDuration": "1200",
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
        @if (session('create'))
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "1200",
                "hideDuration": "1200",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.success('{{ session('create') }}');
        @endif
        @if (session('delete_car'))
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "1200",
                "hideDuration": "1200",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.error('{{ session('delete_car') }}');
        @endif

    </script>

@endsection

@section('content_header')
    <h1 style="display: flex; justify-content:space-between; padding: 0 20px 0 20px; margin-bottom:10px;">
        Vendas
        <a class="btn btn-md btn-success" href="{{ route('vendas.create') }}"><i style="margin-right: 5px; font-size:15px;"
                class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Venda</a>
    </h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-3">
                    <form action="{{ route('search') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="search" name="search" id="searchInput"
                                class="form-control form-control-lg @error('search') is-invalid @enderror"
                                placeholder="Digite o Nome do Cliente">
                            <div class="input-group-append">
                                <button class="btn btn-lg btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row ">
                <table class="table table-striped table-container" style="height: 350px !important;">
                    <thead>
                        <tr>
                            <th>Vendedor</th>
                            <th>Cliente</th>
                            <th>Data da Venda</th>
                            <th>Produtos</th>
                            <th>Forma de Pagamento</th>
                            <th>Valor Total</th>
                            <th width="280px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendas as $venda)
                            <tr>
                                <td>{{ $venda->usuario->name ?? 'Não Informado' }}</td>
                                <td>{{ $venda->cliente->nome ?? 'Não informado' }}</td>
                                <td>{{ $venda->data_venda }}</td>
                                <td>

                                    @foreach ($venda->itens as $item)
                                        <ul>
                                            <li>Produto: {{ $item->produto->nome }} Quantidade: {{ $item->quantidade }}
                                            </li>
                                        </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($venda->forma_pagamento == 2)
                                        Pagamento a prazo
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $precoTotalItens = 0;
                                    @endphp
                                    @foreach ($venda->itens as $item)
                                        @php
                                            $precoTotalItens += $item->preco_total;
                                        @endphp
                                    @endforeach
                                    <strong>R$ {{ number_format($precoTotalItens, 2, ',', '.') }}</strong>
                                <td>
                                    <a href="{{ route('vendas.edit', $venda->id) }}"
                                        class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div style="display: flex; justify-content:flex-end;padding: 0 20px 0 20px;">
                {{ $vendas->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <x-show.modal />
@endsection
