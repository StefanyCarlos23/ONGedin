function mostrarCamposEspecificos() {
    const tipoDoacao = document.getElementById('tipo-doacao').value;
    const doacaoDinheiro = document.getElementById('doacao-dinheiro');
    const doacaoAlimentos = document.getElementById('doacao-alimentos');
    const doacaoRoupas = document.getElementById('doacao-roupas');

    doacaoDinheiro.style.display = 'none';
    doacaoAlimentos.style.display = 'none';
    doacaoRoupas.style.display = 'none';

    if (tipoDoacao === 'dinheiro') {
        doacaoDinheiro.style.display = 'block';
    } else if (tipoDoacao === 'alimentos') {
        doacaoAlimentos.style.display = 'block';
    } else if (tipoDoacao === 'roupas') {
        doacaoRoupas.style.display = 'block';
    }
}

window.onload = mostrarCamposEspecificos;