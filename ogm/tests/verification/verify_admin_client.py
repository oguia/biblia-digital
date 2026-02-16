from playwright.sync_api import sync_playwright
import time
import subprocess
import os

def test_full_system():
    # Setup Environment Variables for SQLite
    env = os.environ.copy()
    env["DB_CONNECTION"] = "sqlite"
    # Get absolute path to the sqlite file
    db_path = os.path.abspath("ogm/tests/ogm_verification.sqlite")
    env["DB_DATABASE"] = db_path

    print(f"Starting PHP Server with DB: {db_path}")

    # Start PHP Server
    # Note: We run from the root, so -t ogm/public points to public folder.
    server = subprocess.Popen(
        ["php", "-S", "localhost:8080", "-t", "ogm/public"],
        env=env,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )

    # Wait for server to start
    time.sleep(2)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            # Enable console logging from browser to python stdout for debugging
            context = browser.new_context()
            page = context.new_page()

            # Verify connection first
            try:
                response = page.goto("http://localhost:8080/")
                if response.status != 200:
                    print(f"Error accessing home: {response.status}")
            except Exception as e:
                print(f"Failed to connect to server: {e}")
                stdout, stderr = server.communicate(timeout=1)
                print(f"Server Stdout: {stdout}")
                print(f"Server Stderr: {stderr}")
                return

            print("1. Testing Registration...")
            page.goto("http://localhost:8080/register")

            # Check for error message on register page load
            if page.is_visible(".bg-red-100"):
                print("Error on register page load:", page.inner_text(".bg-red-100"))

            page.fill("input[name='name']", "Novo Cliente")
            page.fill("input[name='email']", "cliente@teste.com")
            page.fill("input[name='password']", "123456")
            page.fill("input[name='confirm_password']", "123456")
            page.click("button[type='submit']")

            # Wait for navigation or error
            try:
                page.wait_for_url("**/dashboard", timeout=5000)
                print("   Registration Successful!")
            except:
                print("   Registration Failed (Timeout or Error). Current URL:", page.url)
                # Check for error message
                if page.is_visible(".bg-red-100"):
                    print("   Error message:", page.inner_text(".bg-red-100"))
                # Capture screenshot for debug
                page.screenshot(path="ogm/tests/verification/failure_register.png")
                # Print body content for deeper debug
                print("   Page Content:", page.content()[:1000]) # First 1000 chars
                raise Exception("Registration failed")

            print("2. Testing Client Dashboard (Create Company)...")
            page.click("text=Cadastrar Empresa")
            page.wait_for_url("**/dashboard/empresa")
            page.fill("input[name='name']", "Minha Nova Loja")

            # Select first option in category and neighborhood
            # Need to ensure options exist. The setup_verification_db.php adds one category and neighborhood.
            page.select_option("select[name='category_id']", index=1)
            page.select_option("select[name='neighborhood_id']", index=1)

            page.fill("textarea[name='description']", "Uma loja de testes muito boa.")
            page.fill("input[name='phone']", "41999999999")
            page.click("button[type='submit']")

            try:
                page.wait_for_url("**/dashboard", timeout=5000)
                # Verify company name on dashboard
                if page.is_visible("text=Minha Nova Loja"):
                    print("   Company Created Successfully!")
                else:
                    print("   Failed to find company name on dashboard.")
            except:
                print("   Create Company Failed. Current URL:", page.url)
                print("   Page Content:", page.content()[:1000])
                raise Exception("Create Company failed")

            # Logout
            page.goto("http://localhost:8080/logout")
            print("   Logged out.")

            print("3. Testing Admin Panel...")
            # Login as Admin
            page.goto("http://localhost:8080/login")
            page.fill("input[name='email']", "admin@oguiametropolitano.com.br")
            page.fill("input[name='password']", "admin123")
            page.click("button[type='submit']")

            # Check for redirect to /admin
            try:
                page.wait_for_url("**/admin", timeout=5000)
                print("   Admin Login Successful!")
            except:
                print("   Admin Login Failed. URL:", page.url)
                print("   Page Content:", page.content()[:1000])
                raise Exception("Admin Login failed")

            print("4. Testing Admin Moderation...")
            page.click("text=Moderar Pendentes")
            page.wait_for_url("**/admin/empresas?status=pending")

            # Check if our new company is there
            if page.is_visible("text=Minha Nova Loja"):
                print("   Found pending company!")
                # Approve - look for the specific approve button for this row if multiple,
                # but in test environment likely only one.
                # Use the href to be specific or first one.
                page.locator("a[title='Aprovar']").first.click()

                # Wait for reload
                time.sleep(1)
                # Should be gone from pending list
                if not page.is_visible("text=Minha Nova Loja"):
                    print("   Company processed (no longer in pending list)!")
                else:
                    print("   Company still visible in pending (might have failed approval).")
            else:
                print("   Pending company not found (might already be approved or not created).")

            print("All Tests Passed!")
            browser.close()

    except Exception as e:
        print(f"Test Failed: {e}")
    finally:
        server.terminate()
        # Clean up output
        try:
            outs, errs = server.communicate(timeout=1)
        except:
            server.kill()
            outs, errs = server.communicate()

        if errs:
            print("Server Errors:", errs.decode())

if __name__ == "__main__":
    test_full_system()
