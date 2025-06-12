document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("bewertungForm");

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const response = await fetch("bewertung_absenden.php", {
            method: "POST",
            body: formData
        });

        const result = await response.text();
        alert(result);
        form.reset();
    });
});
