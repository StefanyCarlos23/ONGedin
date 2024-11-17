function showSuccessAlert() {
    Swal.fire({
        icon: 'success',
        title: 'Operação realizada com sucesso!',
        confirmButtonText: 'Entendido',
        confirmButtonColor:'#399aa8',
        timer: 7000,
        timerProgressBar: true
    });
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    console.log("Parâmetros da URL:", urlParams.toString());
    if (urlParams.get('success') === 'true') {
        console.log("Sucesso detectado!");
        showSuccessAlert();
    }
};

