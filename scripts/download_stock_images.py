import os
import urllib.request
import time

IMAGES = {
    'venue': [
        ('venue_1.jpg', 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1200&q=80'),
        ('venue_2.jpg', 'https://images.unsplash.com/photo-1545232979-fbf760773d32?auto=format&fit=crop&w=1200&q=80'),
        ('venue_3.jpg', 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1200&q=80'),
        ('venue_4.jpg', 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80')
    ],
    'catering': [
        ('catering_1.jpg', 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=1200&q=80'),
        ('catering_2.jpg', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80'),
        ('catering_3.jpg', 'https://images.unsplash.com/photo-1535141192574-5d4897c13136?auto=format&fit=crop&w=1200&q=80'),
        ('catering_4.jpg', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80')
    ],
    'dekorasi': [
        ('dekorasi_1.jpg', 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80'),
        ('dekorasi_2.jpg', 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=1200&q=80'),
        ('dekorasi_3.jpg', 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=1200&q=80'),
        ('dekorasi_4.jpg', 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=1200&q=80')
    ],
    'fotografer': [
        ('fotografer_1.jpg', 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=1200&q=80'),
        ('fotografer_2.jpg', 'https://images.unsplash.com/photo-1606800052052-a08af7148866?auto=format&fit=crop&w=1200&q=80'),
        ('fotografer_3.jpg', 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=1200&q=80'),
        ('fotografer_4.jpg', 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=1200&q=80')
    ],
    'videografer': [
        ('videografer_1.jpg', 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=1200&q=80'),
        ('videografer_2.jpg', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=1200&q=80'),
        ('videografer_3.jpg', 'https://images.unsplash.com/photo-1470246973918-29a93221c455?auto=format&fit=crop&w=1200&q=80'),
        ('videografer_4.jpg', 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?auto=format&fit=crop&w=1200&q=80')
    ],
    'mua': [
        ('mua_1.jpg', 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=1200&q=80'),
        ('mua_2.jpg', 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1200&q=80'),
        ('mua_3.jpg', 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80'),
        ('mua_4.jpg', 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=1200&q=80')
    ],
    'wedding_organizer': [
        ('wo_1.jpg', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80'),
        ('wo_2.jpg', 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=1200&q=80'),
        ('wo_3.jpg', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80'),
        ('wo_4.jpg', 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=1200&q=80')
    ],
    'entertainment': [
        ('entertainment_1.jpg', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80'),
        ('entertainment_2.jpg', 'https://images.unsplash.com/photo-1525994886773-080587e161c2?auto=format&fit=crop&w=1200&q=80'),
        ('entertainment_3.jpg', 'https://images.unsplash.com/photo-1520523839898-50712705e499?auto=format&fit=crop&w=1200&q=80'),
        ('entertainment_4.jpg', 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?auto=format&fit=crop&w=1200&q=80')
    ],
    'gaun_jas': [
        ('gaun_jas_1.jpg', 'https://images.unsplash.com/photo-1594552072238-b8a33785b261?auto=format&fit=crop&w=1200&q=80'),
        ('gaun_jas_2.jpg', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1200&q=80'),
        ('gaun_jas_3.jpg', 'https://images.unsplash.com/photo-1546804784-896d0dca3805?auto=format&fit=crop&w=1200&q=80'),
        ('gaun_jas_4.jpg', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=80')
    ],
    'undangan_digital': [
        ('undangan_1.jpg', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80'),
        ('undangan_2.jpg', 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1200&q=80'),
        ('undangan_3.jpg', 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=1200&q=80'),
        ('undangan_4.jpg', 'https://images.unsplash.com/photo-1607344645866-009c320c5ab8?auto=format&fit=crop&w=1200&q=80')
    ],
    'general': [
        ('hero_wedding.jpg', 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1400&q=80'),
        ('couple_1.jpg', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80'),
        ('couple_2.jpg', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80'),
        ('couple_3.jpg', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80')
    ]
}

BASE_DIR = r"C:\Users\ADVAN\brightdor\database\seeders\images"

def download_all():
    total = sum(len(v) for v in IMAGES.values())
    count = 0
    print(f"Starting download of {total} royalty-free stock images...")

    for category, items in IMAGES.items():
        cat_dir = os.path.join(BASE_DIR, category)
        os.makedirs(cat_dir, exist_ok=True)

        for filename, url in items:
            out_path = os.path.join(cat_dir, filename)
            count += 1
            if os.path.exists(out_path) and os.path.getsize(out_path) > 10000:
                print(f"[{count}/{total}] Skipping {category}/{filename} (already exists, {os.path.getsize(out_path)} bytes)")
                continue

            print(f"[{count}/{total}] Downloading {category}/{filename}...")
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            try:
                with urllib.request.urlopen(req, timeout=15) as resp:
                    content = resp.read()
                    with open(out_path, 'wb') as f:
                        f.write(content)
                print(f"  [OK] Saved {os.path.getsize(out_path)} bytes")
            except Exception as e:
                print(f"  [ERROR] Error downloading {filename}: {e}")
            time.sleep(0.5)

    print("\nDownload complete! Generating category default fallbacks...")
    # Also ensure legacy root pngs have high quality replacements
    category_defaults = {
        'venue.png': ('venue', 'venue_1.jpg'),
        'catering.png': ('catering', 'catering_1.jpg'),
        'decoration.png': ('dekorasi', 'dekorasi_1.jpg'),
        'photography.png': ('fotografer', 'fotografer_1.jpg')
    }
    for old_name, (cat, src_file) in category_defaults.items():
        src_path = os.path.join(BASE_DIR, cat, src_file)
        dst_path = os.path.join(BASE_DIR, old_name)
        if os.path.exists(src_path):
            with open(src_path, 'rb') as sf, open(dst_path, 'wb') as df:
                df.write(sf.read())
            print(f"Updated root {old_name} from {cat}/{src_file}")

if __name__ == '__main__':
    download_all()
