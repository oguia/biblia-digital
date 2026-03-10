import asyncio
from playwright.async_api import async_playwright, expect

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page(viewport={'width': 1280, 'height': 800}) # Desktop view

        # 1. Reset Database first to ensure clean state
        # Simulate curl call via request or just assume it works.
        # Actually let's just use the app as is.

        # 2. Go to Search Page
        print("Navigating to Search...")
        # We need to wait for Vite to start
        try:
            await page.goto("http://localhost:5175/busca?q=Mecânica", timeout=10000)
        except:
            await page.wait_for_timeout(5000)
            await page.goto("http://localhost:5175/busca?q=Mecânica")

        # 3. Check for Toggle Buttons (Should be hidden on desktop)
        # The container has "lg:hidden".
        # We can check if it's visible.
        # But Playwright checks computed visibility.
        # Let's find the buttons by text "Lista" and "Mapa"
        list_btn = page.get_by_role("button", name="Lista")
        map_btn = page.get_by_role("button", name="Mapa")

        # Expect them to be hidden
        if await list_btn.count() > 0:
            print("Checking visibility of toggle buttons...")
            await expect(list_btn).not_to_be_visible()
            print("Toggle buttons are hidden on desktop (Correct).")
        else:
            print("Toggle buttons not found in DOM (Also Correct/Hidden).")

        # 4. Check Bot Widget Z-Index
        print("Checking Bot Widget...")
        # The bot button should be visible
        bot_btn = page.locator("button.fixed.bottom-6.right-6")
        await expect(bot_btn).to_be_visible()

        # Check computed z-index
        z_index = await bot_btn.evaluate("el => getComputedStyle(el).zIndex")
        print(f"Bot Button Z-Index: {z_index}")
        if z_index != "9999":
            print("WARNING: Z-Index is not 9999!")

        # 5. Open Bot
        await bot_btn.click()
        await page.wait_for_timeout(1000) # Wait for animation

        # Take Screenshot of Bot Open over Map/Results
        await page.screenshot(path="verification_bot_desktop.png")
        print("Screenshot taken: verification_bot_desktop.png")

        # 6. Check Mobile View
        print("Checking Mobile View...")
        await page.set_viewport_size({"width": 375, "height": 667})
        await page.wait_for_timeout(500)

        # Toggle buttons should be visible now
        await expect(list_btn).to_be_visible()
        print("Toggle buttons visible on mobile (Correct).")

        await page.screenshot(path="verification_mobile_toggle.png")
        print("Screenshot taken: verification_mobile_toggle.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
