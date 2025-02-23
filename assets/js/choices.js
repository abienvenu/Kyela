// Drag'n drop for choices
document.addEventListener("DOMContentLoaded", function () {
    let tbody = document.getElementById("choices-list");

    new Sortable(tbody, {
        animation: 150,
        ghostClass: "sortable-ghost",
        onEnd: function (evt) {
            let order = [];
            document.querySelectorAll("#choices-list tr").forEach((row, index) => {
                order.push({ id: row.dataset.id, priority: index + 1 });
            });

            fetch("/" + pollUrl + "/choice/reorder", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(order),
            });
        },
    });
});
