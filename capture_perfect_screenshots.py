import asyncio
from playwright.async_api import async_playwright
import os

DIR = r"C:\Users\ADVAN\brightdor\public\presentasi\assets"
os.makedirs(DIR, exist_ok=True)
BASE = "http://127.0.0.1:8000"

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        ctx = await browser.new_context(viewport={"width": 1440, "height": 900}, device_scale_factor=1)
        page = await ctx.new_page()

        # 1. Vendor Detail
        print("1. Vendor Detail...")
        vendor_url = f"{BASE}/vendor/photo-studio-pro-4"
        await page.goto(vendor_url, wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "04_vendor_detail.png"), full_page=True)
        print("   Saved 04_vendor_detail.png")

        # 2. Booking Modal
        print("2. Booking Modal...")
        await page.goto(vendor_url, wait_until="networkidle")
        await page.wait_for_timeout(1000)
        # click booking date button
        btn_date = page.locator('button[data-booking-open="date"]').first
        if await btn_date.count() > 0:
            await btn_date.click()
            await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "05_booking_modal.png"), full_page=False)
        print("   Saved 05_booking_modal.png")

        # 3. Offer Modal
        print("3. Offer Modal...")
        await page.goto(vendor_url, wait_until="networkidle")
        await page.wait_for_timeout(1000)
        btn_quote = page.locator('button[data-booking-open="quote"]').first
        if await btn_quote.count() > 0:
            await btn_quote.click()
            await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "06_offer_modal.png"), full_page=False)
        print("   Saved 06_offer_modal.png")

        # 4. Lupa Password
        print("4. Lupa Password...")
        await page.goto(f"{BASE}/lupa-password", wait_until="networkidle")
        await page.wait_for_timeout(1500)
        await page.screenshot(path=os.path.join(DIR, "16_forgot_password.png"), full_page=True)
        print("   Saved 16_forgot_password.png")

        # 5. Customer Dashboard (Booking Saya)
        print("5. Customer Dashboard (Booking Saya)...")
        await page.goto(f"{BASE}/login", wait_until="networkidle")
        await page.locator("input#login-email").fill("couple.ext1@brightdor.test")
        await page.locator("input#login-password").fill("password")
        await page.locator("button[type='submit']").click()
        await page.wait_for_timeout(3000)
        await page.goto(f"{BASE}/booking-saya", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "17_customer_bookings.png"), full_page=True)
        print("   Saved 17_customer_bookings.png")

        await browser.close()
        print("All target screenshots captured successfully!")

asyncio.run(main())
