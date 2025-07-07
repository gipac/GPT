# Giuseppe Paccione – AI Systems Consulting Site

## Purpose

This repository contains the source code of the website promoting Giuseppe Paccione's AI consulting services. The site presents his approach to semantic architectures and advanced communication protocols, explains available services and lets visitors request a consultation.

## Features

- **Bilingual pages**: English content in the root directory and an Italian version in `it/`.
- **Interactive design**: custom cursor, Three.js canvas background and animated sections.
- **Service and FAQ sections** describing consulting packages.
- **Contact form** that submits details via `contact.php` and shows a thank‑you modal.
- Hidden "consciousness" scoring and easter eggs handled in `script.js`.

## Prerequisites

- Any web server capable of serving static files.
- PHP 7+ configured to send mail so that `contact.php` can deliver messages.
- There are no build or compilation steps; the site runs directly from the provided files.

## Running Locally

1. Clone this repository.
2. Start a PHP server in the project folder:
   ```bash
   php -S localhost:8000
   ```
3. Visit `http://localhost:8000/index.html` in your browser.

You can also use other local servers for static browsing (for example `python -m http.server`), but the contact form will only work when served through PHP.

## How the Contact Form Works

- The form in `index.html` sends a POST request to `contact.php` using `fetch`.
- `contact.php` validates the `name`, `email` and `challenge` fields, then calls `mail()` to forward the request to `liberation.protocol@paccione.it` and logs the attempt in `mail_debug.txt`.
- `script.js` augments the request with extra hidden fields used for on‑page "consciousness" analytics.
- When the server responds, the page displays a confirmation or an error message.
