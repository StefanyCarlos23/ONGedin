document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const startDate = document.getElementById("start-date");
    const endDate = document.getElementById("end-date");
    const eventList = document.getElementById("event-list");
    const fileFormat = document.getElementsByName("file-format");

    form.addEventListener("submit", (event) => {
        let isValid = true;

        // Mensagens de erro padrão
        const errorMessages = {
            eventList: "Por favor, selecione um evento.",
            fileFormat: "Por favor, selecione o formato do arquivo.",
            dateOrder: "A data final deve ser posterior à data inicial.",
        };

        // Limpar mensagens de erro anteriores
        document.querySelectorAll(".error").forEach((el) => el.remove());

        // Verificar se um evento foi selecionado
        if (!eventList.value) {
            isValid = false;
            displayError(eventList, errorMessages.eventList);
        }

        // Verificar se um formato de arquivo foi selecionado
        if (![...fileFormat].some((radio) => radio.checked)) {
            isValid = false;
            displayError(form.querySelector(".file-format"), errorMessages.fileFormat);
        }

        // Verificar se a data final é posterior à inicial, se ambas forem preenchidas
        if (startDate.value && endDate.value && startDate.value > endDate.value) {
            isValid = false;
            displayError(endDate, errorMessages.dateOrder);
        }

        if (!isValid) {
            event.preventDefault(); // Impedir o envio do formulário
        } else {
            event.preventDefault(); // Impedir recarregamento da página
            alert("Relatório baixado com sucesso!");
        }
    });

    /**
     * Exibir mensagem de erro abaixo do campo
     */
    function displayError(element, message) {
        const error = document.createElement("span");
        error.classList.add("error");
        error.style.color = "red";
        error.style.fontSize = "0.9em";
        error.textContent = message;
        element.parentElement.appendChild(error);
    }
});
