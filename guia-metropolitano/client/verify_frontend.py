import time
from playwright.sync_api import sync_playwright

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # Wait for dev server to start
        print("Waiting for server...")
        time.sleep(5)

        try:
            print("Navigating to homepage...")
            page.goto("http://localhost:5173", timeout=10000)

            # Wait for key elements
            page.wait_for_selector("h1", timeout=5000)

            # Take screenshot of Hero section
            print("Taking screenshot...")
            page.screenshot(path="guia_homepage.png", full_page=True)
            print("Screenshot saved to guia_homepage.png")

        except Exception as e:
            print(f"Error: {e}")

        finally:
            browser.close()

if __name__ == "__main__":
    run()
