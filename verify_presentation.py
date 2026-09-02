import asyncio
from playwright.async_api import async_playwright
import os

DIR = r"C:\Users\ADVAN\brightdor\public\presentasi"
URL = "http://127.0.0.1:8000/presentasi/index.html"

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        ctx = await browser.new_context(viewport={"width": 1440, "height": 900})
        page = await ctx.new_page()

        errors = []
        page.on("console", lambda msg: errors.append(msg.text) if msg.type == "error" else None)

        print("Loading presentation page...")
        await page.goto(URL, wait_until="networkidle")
        await page.wait_for_timeout(2000)

        # 1. Capture Header & Hero
        await page.screenshot(path=os.path.join(DIR, "eval_01_hero.png"))
        print("Captured eval_01_hero.png")

        # 2. Capture Roles
        await page.evaluate("document.getElementById('roles').scrollIntoView()")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "eval_02_roles.png"))
        print("Captured eval_02_roles.png")

        # 3. Capture Booking Modal Feature
        await page.evaluate("document.getElementById('customer-flow').scrollIntoView()")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "eval_03_customer_flow.png"))
        print("Captured eval_03_customer_flow.png")

        # 4. Capture Admin Section
        await page.evaluate("document.getElementById('admin-panel').scrollIntoView()")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "eval_04_admin.png"))
        print("Captured eval_04_admin.png")

        # 5. Capture ERD
        await page.evaluate("document.getElementById('erd-database').scrollIntoView()")
        await page.wait_for_timeout(1000)
        await page.screenshot(path=os.path.join(DIR, "eval_05_erd.png"))
        print("Captured eval_05_erd.png")

        await browser.close()
        print("Console errors:", errors)
        print("Verification complete.")

asyncio.run(main())
