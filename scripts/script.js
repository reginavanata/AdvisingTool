window.onload = function () {
    let button = document.getElementById("submitButton");
    let printButton = document.getElementById("printButton");

    button.addEventListener("click", function() {
        alert("Plan Saved");
    });

    printButton.addEventListener("click", function() {
        window.print();
    });
}