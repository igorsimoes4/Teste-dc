@extends('adminlte::page')
@section('adminlte_css')
    <!-- Adiciona o favicon -->

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

@section('plugins.Chartjs', true)

@section('plugins.Chartjs', true)

@section('title', 'Painel | Home')

@section('logo', 'Tetse')

@section('content_header')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <div class="small-box" style="background-color: rgba(54, 162, 235, 0.7);">
                        <div class="inner">
                            <h3>{{ $data['TotalVendas'] }}</h3>
                            <p>Total de Vendas</p>
                        </div>
                        <div class="icon"><i class="fa fa-fw fa-cash-register"></i></div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="small-box" style="background-color: rgba(54, 162, 235, 0.7);">
                        <div class="inner">
                            <h3>{{ $data['TotalClientes'] }}</h3>
                            <p>Total de Clientes</p>
                        </div>
                        <div class="icon"><i class="fa fa-fw fa-users"></i></div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="small-box" style="background-color: rgba(54, 162, 235, 0.7);">
                        <div class="inner">
                            <h3>{{ $data['TotalProdutos'] }}</h3>
                            <p>Total de Produtos</p>
                        </div>
                        <div class="icon"><i class="fa fa-fw fa-box"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
