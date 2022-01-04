$(document).ready(function () {

    let y;
    let isValid = false;
    let xField = document.getElementById("x_value");
    let value = $("#x_value").val().replace(',', '.');
    const error = document.querySelector('#x_value+span.error');
    let array = Array.prototype.slice.call(document.getElementsByName("y_value"));

    function select(element) {
        element.onclick = function () {
            y = this.value;
            console.log(y);
        }
    }

    function isValidValue(node) {
        return node.validity.valid;
    }

    function checkX() {
        xField.addEventListener("input", function (event) {
            console.log("check x");
            if (isValidValue(xField)) {
                isValid = true;
                error.textContent = '';
                error.className = 'error';
            } else {
                showMessage();
                event.preventDefault();
            }
        });
    }

    function setPointer() {
        let pointer = document.getElementById("pointer");
        pointer.style.visibility = "visible";
        pointer.setAttribute("cx",value*26+110);
        pointer.setAttribute("cy", (-1)*y*26+110);
    }


    function showMessage() {
        if (xField.validity.valueMissing || !(value instanceof Number)) {
            error.textContent = 'Please, enter a number! :(';
        } else if (!isNaN(parseFloat(value))) {
            error.textContent = 'You entered a non-valid number!';
        }

        if (xField.validity.rangeOverflow) {
            error.textContent = 'The maximum value of x = 5!';
        }
        if (xField.validity.rangeUnderflow) {
            error.textContent = "The minimum value of x = -3!"
        }
        error.className = 'error active';
    }

    checkX();
    array.forEach(select);
    $("form").on("submit", function (event) {
        event.preventDefault();
        console.log("submitted");

        if (!isValid) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "script.php",
            data: $(this).serialize() + "&y_value=" + y + "&timezone=" + new Date().getTimezoneOffset(),
            beforeSend: function () {
                $(".send_form").attr("disabled", "disabled");
            },
            success: function (data) {
                console.log("ajax_success: " + data);
                $(".send_form").attr("disabled", false);
                let result_style = document.getElementById('row').style;
                result_style.display = 'table-row';
                $("#receiver").append(data);
                setPointer();
            },
            error: function () {
                console.log("error");
                $(".send_form").attr("disabled", false);
            }
        })
        return true;
    });

});