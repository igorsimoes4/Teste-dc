<form id="venda-form" action="{{route('vendas.update', $venda->id)}}" class="form-horizontal" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="id" hidden value="{{$venda->id}}">
    <div class="row">
        <div class="form-group col-6">
            <label for="cliente_id">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-control">
                <option value="">Selecione um cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $venda->cliente_id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6">
            <label for="forma_pagamento">Forma de Pagamento</label>
            <select name="forma_pagamento" id="forma_pagamento" class="form-control">
                <option value="0" {{ $venda->forma_pagamento == 0 ? 'selected' : '' }}>Selecione um método de Pagamento</option>
                <option value="2" {{ $venda->forma_pagamento == 2 ? 'selected' : '' }}>À Prazo</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="row mt-2">
            <label class="col-10" for="produtos">Produtos</label>
            <button type="button" class="btn btn-sm btn-success col-2 mb-2 mt-2" onclick="adicionarProduto()">Adicionar Produto</button>
        </div>
        <div id="produtos">
            @foreach ($venda->itens as $index => $produto)
                <div class="produto row mt-2" style="gap:10px;">
                    <select name="produtos[{{ $index }}][produto_id]" class="form-control col-6 produto-select">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $prod)
                            <option value="{{ $prod->id }}" data-price="{{ $prod->preco }}" {{ $prod->id == $produto->produto_id ? 'selected' : '' }}>{{ $prod->nome }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="produtos[{{ $index }}][quantidade]" class="form-control col-2 quantidade-input" value="{{ $produto->quantidade }}" placeholder="Quantidade">
                    <input type="number" name="produtos[{{ $index }}][preco_unitario]" class="form-control col-1 preco-unitario-input" value="{{ $produto->produto->preco }}" placeholder="Preço Unitário" readonly>
                    <input type="number" name="produtos[{{ $index }}][preco_total]" class="form-control col-1 preco-total-input" value="{{ $produto->quantidade * $produto->produto->preco }}" placeholder="Preço Total" readonly>
                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removerProduto(this)">Remover</button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group" id="parcelas-container" style="{{ $venda->forma_pagamento == 2 ? 'display:block;' : 'display:none;' }}">
        <label for="num_parcelas">Número de Parcelas</label>
        <input type="number" name="num_parcelas" id="num_parcelas" class="form-control" placeholder="Quantidade de Parcelas" min="1" value="{{ count($venda->parcelas) }}">
        <div class="row mt-2">
            <label class="col-10" for="parcelas">Parcelas</label>
            <button type="button" class="btn btn-sm btn-primary col-2" onclick="adicionarParcela()">Adicionar Parcela</button>
        </div>
        <div id="parcelas">
            @foreach ($venda->parcelas as $index => $parcela)
                <div class="parcela mt-2 row" style="gap:10px;">
                    <input type="date" name="parcelas[{{ $index }}][data_vencimento]" class="form-control col-5" placeholder="Data de Vencimento" value="{{ $parcela->data_vencimento }}">
                    <input type="number" name="parcelas[{{ $index }}][valor]" class="form-control col-5 valor-parcela-input" placeholder="Valor" value="{{ $parcela->valor }}">
                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removerParcela(this)">Remover Parcela</button>
                </div>
            @endforeach
        </div>

    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const produtosDiv = document.getElementById('produtos');
    const parcelasContainer = document.getElementById('parcelas-container');
    const formaPagamentoSelect = document.getElementById('forma_pagamento');
    const numParcelasInput = document.getElementById('num_parcelas');
    const vendaForm = document.getElementById('venda-form');

    // Function to dynamically add products
    window.adicionarProduto = function() {
        const index = produtosDiv.children.length;
        const novoProdutoHTML =
            `<div class="produto row mt-2" style="gap:10px;">
                <select name="produtos[${index}][produto_id]" class="form-control col-6 produto-select">
                    <option value="">Selecione um produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}" data-price="{{ $produto->preco }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
                <input type="number" name="produtos[${index}][quantidade]" class="form-control col-2 quantidade-input" placeholder="Quantidade">
                <input type="number" name="produtos[${index}][preco_unitario]" class="form-control col-1 preco-unitario-input" placeholder="Preço Unitário" readonly>
                <input type="number" name="produtos[${index}][preco_total]" class="form-control col-1 preco-total-input" placeholder="Preço Total" readonly>
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removerProduto(this)">Remover</button>
            </div>`;
        produtosDiv.insertAdjacentHTML('beforeend', novoProdutoHTML);

        const newSelect = produtosDiv.querySelector(`.produto-select[name="produtos[${index}][produto_id]"]`);
        const newQuantityInput = produtosDiv.querySelector(`.quantidade-input[name="produtos[${index}][quantidade]"]`);

        newSelect.addEventListener('change', () => updatePrice(index));
        newQuantityInput.addEventListener('input', () => updatePrice(index));
    };

    // Function to update price based on selected product and quantity
    function updatePrice(index) {
        const select = produtosDiv.querySelectorAll('.produto-select')[index];
        const quantidadeInput = produtosDiv.querySelectorAll('.quantidade-input')[index];
        const precoUnitarioInput = produtosDiv.querySelectorAll('.preco-unitario-input')[index];
        const precoTotalInput = produtosDiv.querySelectorAll('.preco-total-input')[index];

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

        atualizarParcelas();
    }

    // Initial binding for existing products
    document.querySelectorAll('.produto-select, .quantidade-input').forEach((element, index) => {
        element.addEventListener('change', () => updatePrice(index));
        element.addEventListener('input', () => updatePrice(index));
    });

    // Ensure to call updatePrice for each existing product initially loaded
    document.querySelectorAll('.produto').forEach((produto, index) => {
        updatePrice(index); // Update price for each existing product initially
    });

    // Function to dynamically remove products
    window.removerProduto = function(btn) {
        const produtoDiv = btn.parentElement;
        produtoDiv.remove();
        atualizarParcelas();
    };

    // Function to dynamically add parcels
    window.adicionarParcela = function() {
        const parcelasDiv = document.getElementById('parcelas');
        const index = parcelasDiv.children.length;

        const novaParcelaHTML =
            `<div class="parcela mt-2 row" style="gap:10px;">
                <input type="date" name="parcelas[${index}][data_vencimento]" class="form-control col-5" placeholder="Data de Vencimento">
                <input type="number" name="parcelas[${index}][valor]" class="form-control col-5 valor-parcela-input" placeholder="Valor">
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removerParcela(this)">Remover Parcela</button>
            </div>`;
        parcelasDiv.insertAdjacentHTML('beforeend', novaParcelaHTML);
    };

    // Function to dynamically remove parcels
    window.removerParcela = function(btn) {
        const parcelaDiv = btn.parentElement;
        parcelaDiv.remove();
        atualizarParcelas();
    };

    // Function to update parcels based on num_parcelas input and total value
    function atualizarParcelas() {
        const numParcelas = parseInt(numParcelasInput.value) || 1;
        const parcelasDiv = document.getElementById('parcelas');
        const dataAtual = new Date();
        const dataInicial = new Date(dataAtual.getFullYear(), dataAtual.getMonth() + 1, 1); // Próximo mês, dia 1

        const total = Array.from(produtosDiv.querySelectorAll('.preco-total-input'))
            .reduce((sum, input) => sum + parseFloat(input.value || 0), 0);
        const valorParcela = (total / numParcelas).toFixed(2);

        const existingParcelas = Array.from(parcelasDiv.children).map((parcela, i) => ({
            data_vencimento: parcela.querySelector('input[type="date"]').value,
            valor: parcela.querySelector('input[type="number"]').value,
        }));

        parcelasDiv.innerHTML = '';

        for (let i = 0; i < numParcelas; i++) {
            const dataVencimento = new Date(dataInicial);
            dataVencimento.setMonth(dataVencimento.getMonth() + i);
            const dataVencimentoStr = existingParcelas[i] ? existingParcelas[i].data_vencimento : dataVencimento.toISOString().split('T')[0];
            const parcelaValor = valorParcela; // Use valorParcela for all parcels, updating the existing ones as well

            const novaParcelaHTML =
                `<div class="parcela mt-2 row" style="gap:10px;">
                    <input type="date" name="parcelas[${i}][data_vencimento]" class="form-control col-5" placeholder="Data de Vencimento" value="${dataVencimentoStr}">
                    <input type="number" name="parcelas[${i}][valor]" class="form-control col-5 valor-parcela-input" placeholder="Valor" value="${parcelaValor}" readonly>
                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removerParcela(this)">Remover Parcela</button>
                </div>`;
            parcelasDiv.insertAdjacentHTML('beforeend', novaParcelaHTML);
        }
    }

    // Event listener for num_parcelas input
    numParcelasInput.addEventListener('input', atualizarParcelas);

    // Ensure to call atualizarParcelas if there are already parcelas loaded initially
    if (document.querySelectorAll('.parcela').length > 0) {
        atualizarParcelas();
    }

    // Show/hide parcelas container based on payment method
    formaPagamentoSelect.addEventListener('change', () => {
        if (formaPagamentoSelect.value == "2") {
            parcelasContainer.style.display = 'block';
        } else {
            parcelasContainer.style.display = 'none';
        }
    });

    // Ensure correct display of parcelas container on page load
    if (formaPagamentoSelect.value == "2") {
        parcelasContainer.style.display = 'block';
    } else {
        parcelasContainer.style.display = 'none';
    }

    // Ensure form submission for both payment methods
    vendaForm.addEventListener('submit', (event) => {
        if (formaPagamentoSelect.value != "2") {
            // Remove all parcela elements to avoid validation issues
            const parcelasDiv = document.getElementById('parcelas');
            parcelasDiv.innerHTML = '';
        }
    });
});
</script>
