const resetForm = (form) => {
    const inputs = form.querySelectorAll("input, textarea, select");
    inputs.forEach((input) => {
        if ("checkbox" === input.type || "radio" === input.type) {
            input.checked = false;
            return;
        }

        if ("SELECT" === input.tagName) {
            input.selectedIndex = 0;
            return;
        }

        input.value = "";
    });
}
