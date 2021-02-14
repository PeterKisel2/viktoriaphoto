document.addEventListener('DOMContentLoaded', () => {
    naja.addEventListener('complete', () =>{
        // Pridane pre ajax
        $(".alert").delay(2000).slideUp(400, function() {
            $(this).alert('close');
        });
    });

    naja.initialize();
});
// Pridane pre bezne zobrazenie bez ajaxu
$(".alert").delay(2000).slideUp(400, function() {
    $(this).alert('close');
});


