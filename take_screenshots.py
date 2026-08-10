import asyncio
from playwright.async_api import async_playwright
import os

SCREENSHOT_DIR = r"C:\laragon\www\brightdor\screenshots"
os.makedirs(SCREENSHOT_DIR, exist_ok=True)

BASE_URL = "http://127.0.0.1:8000"
ADMIN_EMAIL = "admin@brightdor.test"
ADMIN_PASSWORD = "password"

PAGES = [
    ("dashboard", "/admin"),
    ("vendors", "/admin/vendors"),
    ("vendor-categories", "/admin/vendor-categories"),
    ("services", "/admin/services"),
    ("bookings", "/admin/bookings"),
    ("users", "/admin/users"),
    ("invitation-template-categories", "/admin/invitation-template-categories"),
    ("invitation-templates", "/admin/invitation-templates"),
    ("invitation-orders", "/admin/invitation-orders"),
    ("invitations", "/admin/invitations"),
    ("transactions", "/admin/transactions"),
    ("payouts", "/admin/payouts"),
    ("commission-settings", "/admin/commission-settings"),
    ("blogs", "/admin/blogs"),
    ("testimonials", "/admin/testimonials"),
    ("banners", "/admin/banners"),
    ("faqs", "/admin/faqs"),
    ("galleries", "/admin/galleries"),
    ("settings", "/admin/settings"),
]

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={"width": 1440, "height": 900},
            device_scale_factor=2,
        )
        page = await context.new_page()

        # Login
        print("Navigating to login...")
        await page.goto(f"{BASE_URL}/admin/login", wait_until="networkidle")
        await page.wait_for_timeout(2000)
        
        # Debug: print page content
        title = await page.title()
        url = page.url
        print(f"  Page title: {title}")
        print(f"  Page URL: {url}")
        
        await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "00_login.png"), full_page=False)
        print("  Login page screenshot saved")

        # Fill login form
        try:
            email_input = page.locator('input[type="email"], input[name="email"]').first
            pass_input = page.locator('input[type="password"], input[name="password"]').first
            
            await email_input.fill(ADMIN_EMAIL)
            await pass_input.fill(ADMIN_PASSWORD)
            print("  Filled credentials")
            
            submit_btn = page.locator('button[type="submit"]').first
            await submit_btn.click()
            print("  Clicked submit")
            
            # Wait for navigation
            await page.wait_for_timeout(5000)
            print(f"  After login URL: {page.url}")
            await page.screenshot(path=os.path.join(SCREENSHOT_DIR, "01_after_login.png"), full_page=False)
        except Exception as e:
            print(f"  Login error: {e}")

        # Take screenshots of each page
        for name, path in PAGES:
            url = f"{BASE_URL}{path}"
            print(f"Capturing: {name} -> {url}")
            try:
                resp = await page.goto(url, wait_until="networkidle", timeout=20000)
                await page.wait_for_timeout(2000)
                status = resp.status if resp else "no response"
                print(f"  Status: {status}")
                await page.screenshot(
                    path=os.path.join(SCREENSHOT_DIR, f"{name}.png"),
                    full_page=True,
                )
                print(f"  -> Saved {name}.png")
            except Exception as e:
                print(f"  -> ERROR: {e}")
                try:
                    await page.screenshot(
                        path=os.path.join(SCREENSHOT_DIR, f"{name}.png"),
                        full_page=True,
                    )
                    print(f"  -> Saved anyway")
                except Exception as e2:
                    print(f"  -> Screenshot also failed: {e2}")

        await browser.close()
        print(f"\nAll screenshots saved to {SCREENSHOT_DIR}")

asyncio.run(main())
