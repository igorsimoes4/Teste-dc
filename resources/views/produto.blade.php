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

@section('title', 'Produtos')

@section('content_header')
    <h1 style="display: flex; justify-content:space-between; padding: 0 20px 0 20px; margin-bottom:10px;">
       Produtos
    </h1>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
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
    </script>
    <script type="text/javascript">
        $('#placa').mask('AAA-9999');
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
        <div class="card-header">
            <div class="row">
                <div class="col-10">
                </div>
                <div class="col-2">
                    <a class="btn btn-success" href="{{ route('produtos.create') }}"> Novo Produto</a>
                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>
        <div class="card-body">

            <table class="table table-striped table-container">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Data de Criação</th>
                    <th width="280px">Ações</th>
                </tr>
                @foreach ($produtos as $produto)
                <tr>
                    <td>{{ $produto->id }}</td>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ $produto->preco }}</td>
                    <td>{{ $produto->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <form action="{{ route('produtos.destroy',$produto->id) }}" method="POST">
                            <a class="btn btn-sm btn-warning" href="{{ route('produtos.edit',$produto->id) }}">Editar</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            <div style="display: flex; justify-content:flex-end;padding: 0 20px 0 20px;">
                {!! $produtos->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>

@endsection
