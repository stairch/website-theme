/**
 * Simple Email Obfuscation
 * Decodes base64 encoded emails and sets mailto links.
 */
function stairObfuscateEmails() {
    const protectedLinks = document.querySelectorAll('.email-protect');

    protectedLinks.forEach(link => {
        try {
            // avoid double processing
            if (link.dataset.processed) return;

            const encoded = link.dataset.email;
            if (!encoded) return;

            const decoded = atob(encoded);

            link.href = `mailto:${decoded}`;

            if (link.dataset.replaceSelector) {
                const target = link.querySelector(link.dataset.replaceSelector);
                if (target) target.textContent = decoded;
            } else if (link.dataset.replaceText === 'true') {
                link.textContent = decoded;
            }

            link.dataset.processed = 'true';
        } catch (e) {
            console.error('Failed to decode email', e);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', stairObfuscateEmails);
} else {
    // DOM already ready
    stairObfuscateEmails();
}
