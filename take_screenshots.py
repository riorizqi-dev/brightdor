import asyncio
from playwright.async_api import async_playwright
import os

SCREENSHOT_DIR = r"C:\Users\ADVAN\brightdor\screenshots"
os.makedirs(SCREENSHOT_DIR, exist_ok=True)

BASE_URL = "http://127.0.0.1:8000"

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={"width": 1440, "height": 900},
            device_scale_factor=2,
        )
        page = await context.new_page()

        print("Capturing Home Page...")
        await page.goto(f"{BASE_URL}/", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "01_home_top.png"), full_page=False)
        
        print("Capturing Home Page Scrolled...")
        await page.evaluate("window.scrollBy(0, 500)")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "02_home_scrolled.png"), full_page=False)

        print("Capturing Fotografer Category Page...")
        await page.goto(f"{BASE_URL}/vendors/fotografer", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "02b_category_fotografer.png"), full_page=True)

        print("Capturing Login Page...")
        await page.goto(f"{BASE_URL}/login", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        # Fill to simulate some text
        await page.locator('input[type="email"]').first.fill("test@example.com")
        await page.locator('input[type="password"]').first.fill("password123")
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "03_login_autofill_contrast.png"), full_page=False)

        print("Capturing Admin Dashboard...")
        await page.goto(f"{BASE_URL}/admin/login", wait_until="networkidle")
        await page.locator('input[type="email"]').first.fill("admin@brightdor.test")
        await page.locator('input[type="password"]').first.fill("password")
        await page.locator('button[type="submit"]').first.click()
        await page.wait_for_timeout(3000)
        await page.goto(f"{BASE_URL}/admin", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "04_admin_dashboard.png"), full_page=True)

        print("Capturing Vendor Dashboard...")
        context2 = await browser.new_context(
            viewport={"width": 1440, "height": 900},
            device_scale_factor=2,
        )
        page2 = await context2.new_page()
        await page2.goto(f"{BASE_URL}/vendor/login", wait_until="networkidle")
        await page2.locator('input[type="email"]').first.fill("rina@elegantvenue.id")
        await page2.locator('input[type="password"]').first.fill("password")
        await page2.locator('button[type="submit"]').first.click()
        await page2.wait_for_timeout(3000)
        await page2.goto(f"{BASE_URL}/vendor", wait_until="networkidle")
        await page2.wait_for_timeout(2000)
        await page2.screenshot(path=os.path.join(SCREENSHOT_DIR, "05_vendor_dashboard.png"), full_page=True)

        await browser.close()
        print(f"\nAll screenshots saved to {SCREENSHOT_DIR}")

asyncio.run(main())
