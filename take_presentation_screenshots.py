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

        print("1. Home full...")
        await page.goto(f"{BASE}/", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "01_home.png"), full_page=True)

        print("2. Navbar scrolled...")
        await page.goto(f"{BASE}/", wait_until="networkidle")
        await page.evaluate("window.scrollBy(0, 400)")
        await page.wait_for_timeout(1500)
        await page.screenshot(path=os.path.join(DIR, "02_navbar_scrolled.png"), full_page=False)

        print("3. Category listing...")
        await page.goto(f"{BASE}/vendors/fotografer", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "03_category_listing.png"), full_page=True)

        print("3b. Category venue...")
        await page.goto(f"{BASE}/vendors/venue", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "03b_category_venue.png"), full_page=True)

        print("4. Vendor detail...")
        await page.goto(f"{BASE}/vendors", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        first_card = page.locator("a[href*='/vendors/']").first
        href = await first_card.get_attribute("href")
        if href:
            url = f"{BASE}{href}" if href.startswith("/") else href
            await page.goto(url, wait_until="networkidle")
        await page.wait_for_timeout(2000)
        await page.screenshot(path=os.path.join(DIR, "04_vendor_detail.png"), full_page=True)

        print("5. Login empty...")
        await page.goto(f"{BASE}/login", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "07_login_empty.png"), full_page=True)

        print("6. Register...")
        await page.goto(f"{BASE}/register", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "08_register.png"), full_page=True)

        print("7. Admin dashboard...")
        ctx2 = await browser.new_context(viewport={"width": 1440, "height": 900}, device_scale_factor=1)
        p2 = await ctx2.new_page()
        await p2.goto(f"{BASE}/admin/login", wait_until="networkidle")
        await p2.locator("input[type='email']").first.fill("admin@brightdor.test")
        await p2.locator("input[type='password']").first.fill("password")
        await p2.locator("button[type='submit']").first.click()
        await p2.wait_for_timeout(4000)
        await p2.goto(f"{BASE}/admin", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        await p2.screenshot(path=os.path.join(DIR, "09_admin_dashboard.png"), full_page=True)

        print("8. Admin bookings...")
        await p2.goto(f"{BASE}/admin/bookings", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        await p2.screenshot(path=os.path.join(DIR, "10_admin_bookings.png"), full_page=True)

        print("9. Admin users...")
        await p2.goto(f"{BASE}/admin/users", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        await p2.screenshot(path=os.path.join(DIR, "11_admin_users.png"), full_page=True)

        print("10. Admin vendors...")
        await p2.goto(f"{BASE}/admin/vendors", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        await p2.screenshot(path=os.path.join(DIR, "12_admin_vendors.png"), full_page=True)

        print("11. Admin transactions...")
        await p2.goto(f"{BASE}/admin/transactions", wait_until="networkidle")
        await p2.wait_for_timeout(2000)
        await p2.screenshot(path=os.path.join(DIR, "13_admin_transactions.png"), full_page=True)

        print("12. Vendor dashboard...")
        ctx3 = await browser.new_context(viewport={"width": 1440, "height": 900}, device_scale_factor=1)
        p3 = await ctx3.new_page()
        await p3.goto(f"{BASE}/vendor/login", wait_until="networkidle")
        await p3.locator("input[type='email']").first.fill("rina@elegantvenue.id")
        await p3.locator("input[type='password']").first.fill("password")
        await p3.locator("button[type='submit']").first.click()
        await p3.wait_for_timeout(4000)
        await p3.goto(f"{BASE}/vendor", wait_until="networkidle")
        await p3.wait_for_timeout(2000)
        await p3.screenshot(path=os.path.join(DIR, "14_vendor_dashboard.png"), full_page=True)

        print("13. Vendor services...")
        await p3.goto(f"{BASE}/vendor/services", wait_until="networkidle")
        await p3.wait_for_timeout(2000)
        await p3.screenshot(path=os.path.join(DIR, "15_vendor_services.png"), full_page=True)

        print("14. Lupa password...")
        await page.goto(f"{BASE}/forgot-password", wait_until="networkidle")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "16_forgot_password.png"), full_page=True)

        await browser.close()
        print(f"All screenshots saved to {DIR}")

asyncio.run(main())
