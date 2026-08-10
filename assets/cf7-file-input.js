(function () {
    'use strict';

    function initCF7FileInputs() {
        document.querySelectorAll('.wpcf7-form input[type="file"]').forEach(function (input) {
            var wrapper = document.createElement('div');
            wrapper.className = 'cf7-file-wrapper';

            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'cf7-file-button';
            button.textContent = 'Datei auswählen';

            var label = document.createElement('span');
            label.className = 'cf7-file-label';
            label.textContent = 'Keine Datei ausgewählt';

            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(button);
            wrapper.appendChild(label);
            wrapper.appendChild(input);

            input.style.position = 'absolute';
            input.style.opacity = '0';
            input.style.width = '0';
            input.style.height = '0';

            button.addEventListener('click', function () {
                input.click();
            });

            input.addEventListener('change', function () {
                if (input.files && input.files.length > 0) {
                    label.textContent = Array.from(input.files).map(function (f) { return f.name; }).join(', ');
                } else {
                    label.textContent = 'Keine Datei ausgewählt';
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initCF7FileInputs);
})();
