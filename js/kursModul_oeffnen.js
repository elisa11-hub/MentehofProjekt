function openKursModal(event) {
    event.preventDefault(); // verhindert Weiterleitung bei <a href="#">
    document.getElementById("kursModal").style.display = "flex";
}

function closeKursModal() {
    document.getElementById("kursModal").style.display = "none";
}

// Optional: Schließen beim Klicken außerhalb
window.addEventListener("click", function(e) {
    const modal = document.getElementById("kursModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
});