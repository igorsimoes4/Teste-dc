<form action="{{ route('vendas.store') }}" class="form-horizontal" method="POST">
    @csrf
    <div class="row">
        <div class="form-group col-6">
            <label for="cliente_id">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-control">
                <option value="">Selecione um cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6">
            <label for="forma_pagamento">Forma de Pagamento</label>
            <select name="forma_pagamento" id="forma_pagamento" class="form-control">
                <option>Selecione a forma de pagamento</option>
                <option value="2">À Prazo</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-10" for="produtos">Produtos</label>
            <button type="button" class="btn btn-sm btn-success col-2 mb-2" onclick="adicionarProduto()">Adicionar Produto</button>
        </div>
        <div id="produtos">
            <div class="produto row" style="gap:10px;">
                <select name="produtos[0][produto_id]" class="form-control col-6 produto-select">
                    <option value="">Selecione um produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}" data-price="{{ $produto->preco }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
                <input type="number" name="produtos[0][quantidade]" class="form-control col-3 quantidade-input" placeholder="Quantidade">
                <input type="number" name="produtos[0][preco_unitario]" class="form-control col-1 preco-unitario-input" placeholder="Preço Unitário" readonly>
                <input type="number" name="produtos[0][preco_total]" class="form-control col-1 preco-total-input" placeholder="Preço Total" readonly>
            </div>
        </div>
    </div>
    <div class="form-group" id="parcelas-container" style="display:none;">
        <label for="num_parcelas">Número de Parcelas</label>
        <input type="number" id="num_parcelas" class="form-control" placeholder="Quantidade de Parcelas" min="1" value="1">
        <label for="parcelas">Parcelas</label>
        <div id="parcelas"></div>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const parcelasContainer = document.getElementById('parcelas-container');
    const formaPagamentoSelect = document.getElementById('forma_pagamento');
    const numParcelasInput = document.getElementById('num_parcelas');
    const produtosDiv = document.getElementById('produtos');

    formaPagamentoSelect.addEventListener('change', (event) => {
        if (event.target.value == '2') {
            parcelasContainer.style.display = 'block';
            atualizarParcelas();
        } else {
            parcelasContainer.style.display = 'none';
        }
    });

    numParcelasInput.addEventListener('input', atualizarParcelas);

    document.querySelectorAll('.produto-select, .quantidade-input').forEach((element, index) => {
        element.addEventListener('change', () => updatePrice(index));
        element.addEventListener('input', () => updatePrice(index));
    });

    window.adicionarProduto = function() {
        const index = produtosDiv.children.length;
        const novoProdutoHTML = `
            <div class="produto row mt-2" style="gap:10px;">
                <select name="produtos[${index}][produto_id]" class="form-control col-6 produto-select">
                    <option value="">Selecione um produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}" data-price="{{ $produto->preco }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
                <input type="number" name="produtos[${index}][quantidade]" class="form-control col-3 quantidade-input" placeholder="Quantidade">
                <input type="number" name="produtos[${index}][preco_unitario]" class="form-control col-1 preco-unitario-input" placeholder="Preço Unitário" readonly>
                <input type="number" name="produtos[${index}][preco_total]" class="form-control col-1 preco-total-input" placeholder="Preço Total" readonly>
            </div>
        `;
        produtosDiv.insertAdjacentHTML('beforeend', novoProdutoHTML);

        const newSelect = produtosDiv.querySelector(`.produto-select[name="produtos[${index}][produto_id]"]`);
        const newQuantityInput = produtosDiv.querySelector(`.quantidade-input[name="produtos[${index}][quantidade]"]`);

        newSelect.addEventListener('change', () => updatePrice(index));
        newQuantityInput.addEventListener('input', () => updatePrice(index));
    };

    function updatePrice(index) {
        const select = document.querySelectorAll('.produto-select')[index];
        const quantidadeInput = document.querySelectorAll('.quantidade-input')[index];
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

        atualizarParcelas();
    }

    function atualizarParcelas() {
        const numParcelas = parseInt(numParcelasInput.value) || 1;
        const parcelasDiv = document.getElementById('parcelas');
        const dataAtual = new Date();
        const dataInicial = new Date(dataAtual.getFullYear(), dataAtual.getMonth() + 1, 1); // Próximo mês, dia 1
        parcelasDiv.innerHTML = '';

        const total = Array.from(document.querySelectorAll('.preco-total-input'))
            .reduce((sum, input) => sum + parseFloat(input.value || 0), 0);
        const valorParcela = (total / numParcelas).toFixed(2);

        for (let i = 0; i < numParcelas; i++) {
            const dataVencimento = new Date(dataInicial);
            dataVencimento.setMonth(dataVencimento.getMonth() + i);
            const dataVencimentoStr = dataVencimento.toISOString().split('T')[0];

            const novaParcelaHTML = `
                <div class="parcela mt-2 row" style="gap:10px;">
                    <input type="date" name="parcelas[${i}][data_vencimento]" class="form-control col-5" placeholder="Data de Vencimento" value="${dataVencimentoStr}">
                    <input type="number" name="parcelas[${i}][valor]" class="form-control col-5" placeholder="Valor" value="${valorParcela}" readonly>
                </div>
            `;
            parcelasDiv.insertAdjacentHTML('beforeend', novaParcelaHTML);
        }
    }
});
</script>
