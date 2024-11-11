successAlert('Cadastro realizado com sucesso!');

function successAlert(message) {
    Swal.fire({
        title: 'Parabéns!',
        text: message,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#399aa8',
        timer: 5000, // O alerta desaparecerá após 5 segundos
        timerProgressBar: true, // Exibe uma barra de progresso
    })
}