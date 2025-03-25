function includeHTML() {
    const elements = document.querySelectorAll('[data-include]');
    elements.forEach(async (element) => {
        const file = element.getAttribute('data-include');
        if (file) {
            try {
                const response = await fetch(file);
                if (response.ok) {
                    const html = await response.text();
                    element.innerHTML = html;
                } else {
                    console.error(`Error loading file: ${file}`);
                }
            } catch (error) {
                console.error(`Error fetching file: ${file}`, error);
            }
        }
    });
}

window.onload = includeHTML;
