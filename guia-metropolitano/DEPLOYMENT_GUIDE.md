# Guia Metropolitano - Deployment Guide (v2)

## 1. Upload Files
1. Extract `guia-metropolitano-v2.zip`.
2. Upload the contents of `api/` to `public_html/api/`.
3. Upload the contents of `client/dist/` to `public_html/`.

## 2. Directory Structure
Ensure your server looks like this:
```
public_html/
├── api/
│   ├── config.php
│   ├── seeder_ai_only.php
│   └── ...
├── assets/ (from client/dist)
├── index.html (from client/dist)
├── .htaccess (CRITICAL for routing)
```

## 3. Configuration
- Edit `api/config.php` and set your `GEMINI_API_KEY`.
- If using MySQL, create a database and update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
- If using SQLite, ensure `api/` folder is writable (`chmod 775`).

## 4. Seeding Data
- Visit `https://yourdomain.com/api/seeder_ai_only.php?cat=Pizzaria` to generate data.
- Or use the CLI if you have SSH access: `php api/seeder_ai_only.php "Pizzaria" "Curitiba"`.

## 5. Troubleshooting
- **404 on Refresh:** Ensure `.htaccess` is present in the root folder.
- **Images not loading:** Check if `loremflickr.com` is accessible or use the seeder to regenerate with valid URLs.
- **API Errors:** Check `api/error_log` or enable display_errors in `api/config.php` temporarily.
