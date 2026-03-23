import time
from playwright.sync_api import sync_playwright

def verify_password_change():
    print("Starting password verification test...")
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        try:
            # Login as the admin user we just created
            print("Logging in with old password...")
            page.goto("http://localhost:5173/#/login")
            page.fill("input[type='email']", "oguiametropolitano@gmail.com")
            page.fill("input[type='password']", "admin123")
            page.click("button[type='submit']")

            # Wait for dashboard to load
            page.wait_for_selector("text=Logado como", timeout=5000)
            print("Successfully logged in.")

            # Go to Profile page
            print("Navigating to Profile...")
            page.click("text=Meu Perfil")
            page.wait_for_selector("text=Meu Perfil", timeout=5000)

            # Change Password
            print("Changing password...")
            page.fill("input[name='password']", "admin12345")
            page.click("button[type='submit']")
            page.wait_for_selector("text=Perfil atualizado com sucesso", timeout=5000)
            print("Password changed successfully.")

            # Logout
            print("Logging out...")
            page.click("text=Sair")
            page.wait_for_selector("text=Entrar", timeout=5000)

            # Login with new password
            print("Logging in with new password...")
            page.fill("input[type='email']", "oguiametropolitano@gmail.com")
            page.fill("input[type='password']", "admin12345")
            page.click("button[type='submit']")

            page.wait_for_selector("text=Logado como", timeout=5000)
            print("Successfully logged in with NEW password. Test Passed!")

        except Exception as e:
            print(f"Test failed: {e}")
            page.screenshot(path="password_error.png")
            raise e
        finally:
            browser.close()

if __name__ == "__main__":
    verify_password_change()