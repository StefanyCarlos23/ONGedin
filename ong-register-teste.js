function nameValidate() {
    if (campos[0].value === "") {
        errorAlert('Preenchimento obrigatório: Nome', 0)
    }
    else if (inputWithoutNumbers(campos[0].value)) {
        setError(0);
    }
    else{
        removeError(0)
    }
}