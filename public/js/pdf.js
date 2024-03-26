export function generarPdfDetails(json, headers, titulo, nameFile) {
    const doc = new jsPDF("p", "pt", "letter");

    json = json.map((obj) => Object.values(obj));
    doc.setLineWidth(2);
    doc.text(200, 50, titulo);
    doc.autoTable({
        head: [headers],
        body: json,
        startY: 70,
        theme: "grid",
    });
    // save the data to this file
    doc.save(nameFile);
}
