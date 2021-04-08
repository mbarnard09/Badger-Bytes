function sortTable() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("order-table");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[4].getElementsByTagName("select")[0].value
            y = rows[i + 1].getElementsByTagName("TD")[4].getElementsByTagName("select")[0].value
            if (x > y) {
              shouldSwitch = true;
              break;
            } else if (x == y) {
                let p1 = rows[i].getElementsByTagName("TD")[5].getElementsByTagName("div")[0].getElementsByTagName("select")[0].value
                let p2 = rows[i+1].getElementsByTagName("TD")[5].getElementsByTagName("div")[0].getElementsByTagName("select")[0].value
                if (p1 < p2) {
                    shouldSwitch = true;
                    break;
                } else if (p1 == p2) {
                    let d1 = rows[i].getElementsByTagName("TD")[6];
                    let d2 = rows[i + 1].getElementsByTagName("TD")[6];
                    if (d1.innerHTML > d2.innerHTML) {
                      shouldSwitch = true;
                      break;
                    }
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

window.onload = () => {
    sortTable()
}

let priorities = document.getElementsByName("priority")
priorities.forEach(stat => {
    $(`#${stat.id}`).change(()=>{
        let priority = $(`#${stat.id}`).val()
        let id = $(`#${stat.id}`).attr("oid")
        let update = {
            "priority": priority,
            "id": id
        }
        $.ajax({
            type: "post",
            url: "index.php",
            data: update,
            success: () =>{
                sortTable()
                $("#sorted-alert").show()
            },
            error: function() {
                alert("ERROR")
            }
        })
    })
});

let statuses = document.getElementsByName("status")
statuses.forEach(stat => {
    $(`#${stat.id}`).change(()=>{
        let status = $(`#${stat.id}`).val()
        let id = $(`#${stat.id}`).attr("oid")
        let update = {
            "status": status,
            "id": id
        }
        $.ajax({
            type: "post",
            url: "index.php",
            data: update,
            success: () => {
                sortTable()
                $("#sorted-alert").show()
            },
            error: function() {
                alert("ERROR")
            }
        })
    })
});
