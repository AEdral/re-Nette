window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }
});

window.addEventListener('DOMContentLoaded', event => {
    var elements = document.querySelectorAll("td");
    elements.forEach(function(element) {
      element.classList.addClass("p-0 mb-0");
    }) 
});


window.addEventListener('DOMContentLoaded', event => {
    const datatablesSimple = $('#datatablesSimpleRend'); // Seleziona la tabella tramite jQuery
    if (datatablesSimple.length) {
        datatablesSimple.DataTable({
            paging: true,        // Abilita la paginazione
            searching: true,     // Abilita la ricerca
            ordering: true,      // Abilita l'ordinamento delle colonne
            info: true,          // Mostra informazioni sulla tabella
            lengthChange: false, // Disabilita la selezione del numero di righe per pagina
            scrollX: true       // Abilita lo scorrimento orizzontale
        });
    }
});
/*
window.addEventListener('DOMContentLoaded', event => {
    var elements = document.querySelectorAll("td");
    elements.forEach(function(element) {
      element.classList.add("p-0", "mb-0");
    });
});

*/