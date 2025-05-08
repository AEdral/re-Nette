window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const comune = datatablesSimple.dataset.comune;
        const tipoLettera = datatablesSimple.dataset.tipoLettera;
        const tipoLegge = datatablesSimple.dataset.tipoLegge;
        

        new simpleDatatables.DataTable(datatablesSimple, {
            paging: false
        });

        const addButton = document.createElement('button');
        if (typeof comune !== 'undefined' && typeof tipoLettera !== 'undefined' && typeof tipoLegge !== 'undefined') {
            addButton.textContent = 'Aggiungi sezione';
        } else {
            addButton.textContent = 'Crea sezione';
        }
        
        addButton.classList.add('btn', 'btn-primary', 'mx-4');

        // Get the datatable-top div
        const datatableTop = document.querySelector('.datatable-top');
        if (datatableTop) {
            // Append the button to the datatable-top div
            datatableTop.appendChild(addButton);
        }

        addButton.addEventListener('click', function() {
            let url = "add";
            if (typeof comune !== 'undefined' && typeof tipoLettera !== 'undefined' && typeof tipoLegge !== 'undefined') {
                // Generate the URL dynamically based on the provided variables
                url += "?comune="+comune+"&tipoLettera="+tipoLettera+"&tipoLegge="+tipoLegge;
            }
            //const url = `add/${encodeURIComponent(comune)}/${encodeURIComponent(tipoLettera)}/${encodeURIComponent(tipoLegge)}`;
            // Redirect to the generated URL when the button is clicked
            window.location.href = url;
        });
    }
    
});
