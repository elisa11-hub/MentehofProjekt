document.addEventListener("DOMContentLoaded", () => {
                        fetch("../php/schüler_kurse.php")
                            .then(response => response.json())
                            .then(data => {
                                const tbody = document.querySelector("#dashboard tbody");
                                tbody.innerHTML = "";

                                if (data.length === 0) {
                                    tbody.innerHTML = `<tr><td colspan="10" style="padding: 8px; font-style: italic; color: #777; text-align: center;">Noch keine Kurse gebucht.</td></tr>`;
                                    return;
                                }

                                data.forEach(kurs => {
                                    const tr = document.createElement("tr");
                                    tr.innerHTML = `
                                        <td>${kurs.kursname}</td>
                                        <td>${kurs.bezeichnung}</td>
                                        <td>${kurs.min_reitlevel}</td>
                                        <td>${kurs.kosten} €</td>
                                        <td>${kurs.datum}</td>
                                        <td>${kurs.uhrzeit}</td>
                                        <td>${kurs.dauer} min</td>
                                        <td>${kurs.ort}</td>
                                        <td>${kurs.max_teilnehmeranzahl}</td>
                                        <td>${kurs.lehrer_vorname} ${kurs.lehrer_nachname}</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                            });
                    });