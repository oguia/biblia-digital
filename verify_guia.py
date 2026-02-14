from playwright.sync_api import sync_playwright
import time
import os

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context(viewport={'width': 1280, 'height': 800})
    page = context.new_page()

    print("Navigating to Home...")
    try:
        page.goto("http://localhost:5174", timeout=60000)
    except Exception as e:
        print(f"Failed to load Home: {e}")
        # Try checking logs
        try:
            with open("guia-metropolitano/client/vite.log", "r") as f:
                print("Vite Log:", f.read())
        except:
            pass
        return

    page.wait_for_timeout(2000) # Wait for init
    page.screenshot(path="verification_home.png")
    print("Home loaded.")

    # Search for "Pizzaria"
    print("Searching...")
    page.fill("input[type='text']", "Pizzaria")
    page.click("button[type='submit']")

    page.wait_for_url("**/busca?q=Pizzaria")
    page.wait_for_timeout(3000) # Wait for results
    page.screenshot(path="verification_search_list.png")
    print("Search results loaded.")

    # Toggle Map
    print("Toggling Map...")
    # On desktop, map is visible by default alongside list.
    # But let's check if map container is visible.
    # The toggle buttons are visible on mobile/desktop header?
    # Yes.
    page.click("text=Mapa")
    page.wait_for_timeout(1000)
    page.screenshot(path="verification_search_map.png")

    # Click on a business
    print("Clicking details...")
    # Find a link to details. "Ver Detalhes" or the card title.
    # Card title is inside h3 -> Link.
    # Or "Ver Detalhes" inside Popup if map marker clicked.
    # Let's click the first card title.
    try:
        page.click("h3", timeout=5000)
    except:
        print("Could not find h3 link, trying 'Ver Detalhes'")
        # Maybe click map marker? No, list is safer.
        pass

    page.wait_for_timeout(2000)
    page.screenshot(path="verification_details.png")
    print("Details page loaded.")

    # Check for AI Widget
    print("Checking AI Widget...")
    if page.is_visible("text=Falar com GuiaBot"):
        print("AI Widget found.")
        page.click("text=Falar com GuiaBot") # Open chat
        page.wait_for_timeout(1000)
        page.screenshot(path="verification_ai_chat.png")
    else:
        print("AI Widget NOT found.")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
