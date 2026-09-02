import asyncio
from playwright.async_api import async_playwright
import os

DIR = r"C:\Users\ADVAN\brightdor\public\presentasi\assets"
BASE = "http://127.0.0.1:8000"

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        ctx = await browser.new_context(viewport={"width": 1440, "height": 900})
        page = await ctx.new_page()

        # Vendor detail
        print("Vendor detail...")
        await page.goto(f"{BASE}/vendors", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        links = await page.locator("a[wire\\:navigate]").all()
        for link in links:
            h = await link.get_attribute("href")
            if h and "/vendors/" in h and h.count("/") > 4:
                print(f"  Found vendor link: {h}")
                await page.goto(h, wait_until="networkidle")
                await page.wait_for_timeout(2000)
                await page.screenshot(path=os.path.join(DIR, "04_vendor_detail.png"), full_page=True)
                break

        # Forgot password
        print("Forgot password...")
        await page.goto(f"{BASE}/forgot-password", wait_until="networkidle")
        await page.wait_for_timeout(1500)
        print(f"  URL: {page.url}")
        await page.screenshot(path=os.path.join(DIR, "16_forgot_password.png"), full_page=True)

        # Vendor services
        print("Vendor services...")
        ctx2 = await browser.new_context(viewport={"width": 1440, "height": 900})
        p2 = await ctx2.new_page()
        await p2.goto(f"{BASE}/vendor/login", wait_until="networkidle")
        email_input = p2.locator("input[name='email']")
        pass_input = p2.locator("input[name='password']")
        await email_input.first.fill("rina@elegantvenue.id")
        await pass_input.first.fill("password")
        await p2.locator("button[type='submit']").first.click()
        await p2.wait_for_timeout(5000)
        print(f"  After login URL: {p2.url}")
        await p2.goto(f"{BASE}/vendor/services", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        print(f"  Services URL: {p2.url}")
        await p2.screenshot(path=os.path.join(DIR, "15_vendor_services.png"), full_page=True)

        await browser.close()
        print("Done!")

asyncio.run(main())
