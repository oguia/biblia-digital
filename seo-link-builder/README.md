# SEO Link Builder SaaS

A semi-automated Backlink & SEO Platform for Shared Hosting (Hostinger).

## Features
- **Project Management**: Users can submit URLs and keywords.
- **Target Discovery**: Admin-controlled Crawler (DuckDuckGo scraper) to find backlink opportunities.
- **Assisted Submission**: "Worker" interface for Admin to manually post links with AI-generated content (Gemini).
- **Credit System**: Monetization via Mercado Pago.

## Installation on Hostinger

### 1. Database Setup
1. Create a MySQL Database in Hostinger Panel.
2. Import `database.sql` via phpMyAdmin.

### 2. Backend Setup
1. Upload the `seo-link-builder/api` folder to `public_html/api`.
2. Edit `api/config.php`:
   - Update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
   - Add your `GEMINI_API_KEY`.
   - Add your `MERCADO_PAGO_ACCESS_TOKEN`.

### 3. Frontend Setup
1. Build the React app locally:
   ```bash
   cd client
   npm install
   npm run build
   ```
2. Upload the contents of `client/dist` to `public_html`.
   - `index.html` should be in the root.
   - `assets/` folder should be in the root.

### 4. Configuration
- Ensure `.htaccess` (if needed) redirects all non-file requests to `index.html` for React Routing.
- Create a `.htaccess` in root:
  ```apache
  <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.html$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.html [L]
  </IfModule>
  ```

## Usage
- Register a new account.
- The first user is usually NOT Admin automatically unless you import the default user from SQL or manually change `role` to `admin` in `users` table.
- Default Admin: `admin@example.com` / `admin123` (CHANGE PASSWORD IMMEDIATELY!).

## Worker Workflow (Admin)
1. Go to **Targets & Discovery**.
2. Run a scan (e.g., "submit site" + "your niche").
3. Add targets.
4. Go to **Submission Worker**.
5. The system will pair a pending Project with an available Target.
6. Use the AI Generator to create unique content.
7. Post the link on the target site (opened in iframe or new tab).
8. Click "Complete" to deduct 1 credit from the user.
