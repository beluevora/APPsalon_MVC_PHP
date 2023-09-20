document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    dateFinder();
}

function dateFinder(){
    const dateInput = document.querySelector('#fecha');
    dateInput.addEventListener('input', function(e){
        const selectedDate = e.target.value;
        window.location = `?fecha=${selectedDate}`;
    });
}