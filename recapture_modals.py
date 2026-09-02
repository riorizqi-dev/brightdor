import asyncio
from playwright.async_api import async_playwright
import os

DIR = r"C:\Users\ADVAN\brightdor\public\presentasi\assets"
BASE = "http://127.0.0.1:8000"

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        ctx = await browser.new_context(viewport={"width": 1440, "height": 900}, device_scale_factor=1)
        page = await ctx.new_page()

        # 1. Booking Date Modal
        print("1. Booking Modal...")
        await page.goto(f"{BASE}/vendor/photo-studio-pro-4", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        btn_date = page.locator('button[data-booking-open="date"]').first
        await btn_date.click()
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "05_booking_modal.png"), full_page=False)
        print("   Saved 05_booking_modal.png")

        # 2. Offer Modal
        print("2. Offer Modal...")
        await page.goto(f"{BASE}/vendor/photo-studio-pro-4", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        btn_quote = page.locator('button[data-booking-open="quote"]').first
        await btn_quote.click()
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "06_offer_modal.png"), full_page=False)
        print("   Saved 06_offer_modal.png")

        await browser.close()
        print("Done capturing modals!")

asyncio.run(main())
