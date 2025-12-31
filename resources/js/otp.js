document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".otp-input");
    const hiddenInput = document.getElementById("otp_code");

    inputs.forEach((input, index) => {
        input.addEventListener("input", function (e) {
            const value = e.target.value;

            if (value && !/^\d$/.test(value)) {
                e.target.value = "";
                return;
            }

            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            updateHiddenInput();
        });

        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener("paste", function (e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData("text").trim();

            if (/^\d{6}$/.test(pastedData)) {
                inputs.forEach((input, i) => {
                    input.value = pastedData[i] || "";
                });
                inputs[inputs.length - 1].focus();
                updateHiddenInput();
            }
        });
    });

    function updateHiddenInput() {
        const otp = Array.from(inputs)
            .map((input) => input.value)
            .join("");
        hiddenInput.value = otp;
    }
});
